<?php

namespace App\Http\Middleware;

use App\Constants\CommonVal;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class GenerateResponseMiddleware
{
    use ApiResponse;

    /**
     * Standardize API responses across the application.
     *
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        // Ensure the request expects a JSON response
        $request->headers->set('Accept', 'application/json');

        // Process the request
        $response = $next($request);

        // Get the status code from the response or default to 200 OK
        $statusCode = method_exists($response, 'getStatusCode')
            ? $response->getStatusCode()
            : Response::HTTP_OK;

        if ($statusCode >= CommonVal::HTTP_BAD_REQUEST) {
            return $response;
        }

        // Process different response types
        $responseApi = $this->processResponse($response, $request, $statusCode);

        foreach ($response->headers->getCookies() as $cookie) {
            $responseApi->headers->setCookie($cookie);
        }

        // Copy non-cookie headers
        foreach ($response->headers->all() as $key => $values) {
            if (strtolower($key) !== 'set-cookie') {
                foreach ($values as $v) {
                    $responseApi->headers->set($key, $v, false);
                }
            }
        }

        return $responseApi;
    }

    /**
     * Process the response based on its type.
     *
     * @param mixed $response
     * @param Request $request
     * @param int $statusCode
     * @return JsonResponse
     */
    private function processResponse(mixed $response, Request $request, int $statusCode): JsonResponse
    {
        if ($response instanceof JsonResource) { // Handle JsonResource responses (Laravel API Resources)
            $response = $response->resolve($request);
        } else if ($response instanceof JsonResponse) { // Handle existing JsonResponse objects
            // We only extract the data, not cookies or headers
            $response = $response->getData(true);
        } else if (method_exists($response, 'getContent')) { // Handle responses with getContent method (like regular Response objects)
            // Try to decode content as JSON
            $decoded = json_decode($response->getContent(), true);

            // Return decoded content if it's valid JSON, otherwise return as is
            $response = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $response->getContent();
        }

        // Check if '_cookie' key exists in the response array
        $cookie = null;
        if (is_array($response) && array_key_exists('_cookie', $response)) {
            $cookie = $response['_cookie'];
            unset($response['_cookie']);
        }

        $responseApi = self::successResponse($response, $statusCode);

        if ($cookie) {
            $responseApi = $responseApi->withCookie($cookie);
        }

        return $responseApi;
    }
}
