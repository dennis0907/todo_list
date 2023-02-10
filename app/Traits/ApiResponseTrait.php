<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


trait ApiResponseTrait
{
    public function errorResponse($message, $status, $code = null)
    {
        $code = $code ?? $status;

        return response()->json(
            [
                'message' => $message,
                'code' => $code
            ],
            $status
        );
    }
}
