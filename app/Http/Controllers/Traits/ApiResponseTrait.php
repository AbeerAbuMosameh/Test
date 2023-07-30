<?php

namespace App\Http\Controllers\Traits;

trait ApiResponseTrait
{
    protected function successResponse($data, $message = null, $code = 200)
    {
        return response()->json([
            'status' => trans('validation.success'),
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($data, $message = null, $code)
    {
        return response()->json([
            'status' => trans('validation.error'),
            'message' => $message,
            'data' => $data
        ], $code);
    }


}
