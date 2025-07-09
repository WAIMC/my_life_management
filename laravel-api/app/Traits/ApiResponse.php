namespace App\Traits;

trait ApiResponse {
    /**
    * Render response api
    * 
    * @param mixed $data
    * @param array $error
    * @return Response
    */
    public function renderResponse(mixed $data, array $error): Response
    {
      list($status, $code, $messages) = $error;

      return response()->json([
        'data' => $data,
        'error' => [
          'status' => $status,
          'code' => $code,
          'messages' => $messages
        ]
      ]);
    }

    public static function successResponse($data, $code = 200)
    {
        return $this->renderResponse($data, false, $code, null);
    }

    public static function errorResponse($message, $code)
    {
        return $this->renderResponse(null, true, $code, $message);
    }
}
