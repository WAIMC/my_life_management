namespace App\Traits;

trait ApiResponse {
    /**
    * Render response api
    * 
    * @param mixed $data
    * @param array $error
    * @return Response
    */
    public static function renderResponse(mixed $data, array $error): Response
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
}
