<?php
namespace App\Helpers;
class ApiResponse
{
    public static function success($message, $data = [], $statusCode )
    {
        return response()->json([
            'success' => true,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
    public static function error($message, $errors = [], $statusCode )
    {
        return response()->json([
            'success' => false,
            'statusCode' => $statusCode,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
}
