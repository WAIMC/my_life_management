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

        // Keep cookie + header original
        $cookies = $response->headers->getCookies();
        $headers = $response->headers->all();

        // Process different response types
        $responseApi = $this->processResponse($response, $request, $statusCode);

        foreach ($cookies as $cookie) {
            $responseApi->headers->setCookie($cookie);
        }
        foreach ($headers as $key => $values) {
            foreach ($values as $v) {
                $responseApi->headers->set($key, $v, false);
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
        // Handle JsonResource responses (Laravel API Resources)
        if ($response instanceof JsonResource) {
            return self::successResponse($response->resolve($request), $statusCode);
        }

        // Handle existing JsonResponse objects
        if ($response instanceof JsonResponse) {
            return self::successResponse($response->getData(true), $statusCode);
        }

        // Handle responses with getContent method (like regular Response objects)
        if (method_exists($response, 'getContent')) {
            return $this->processContentResponse($response->getContent(), $statusCode);
        }

        // Handle any other response type
        return self::successResponse($response, $statusCode);
    }

    /**
     * Process response content, attempt to decode JSON.
     *
     * @param mixed $content
     * @param int $statusCode
     * @return JsonResponse
     */
    private function processContentResponse(mixed $content, int $statusCode): JsonResponse
    {
        // Try to decode content as JSON
        $decoded = json_decode($content, true);

        // Return decoded content if it's valid JSON, otherwise return as is
        $data = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $content;

        return self::successResponse($data, $statusCode);
    }
}
