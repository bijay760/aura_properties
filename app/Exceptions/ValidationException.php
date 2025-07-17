<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

class ValidationException extends \Illuminate\Validation\ValidationException
{
    public function getResponse(): JsonResponse
    {
        return response()->json([
            'code'    => 422,
            'status'  => false,
            'message' => 'Validation failed.',
            'errors'  => $this->errors(), // ✅ contains all field-specific errors
            'data'    => []
        ], 422);
    }
}
