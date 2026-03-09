<?php

namespace App\Traits;

trait ApiResponseTrait
{
    public function successResponse($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'code'    => $code,
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    public function errorResponse($message = 'Error', $code = 400)
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
        ], $code);
    }
}
