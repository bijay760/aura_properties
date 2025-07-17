<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
    protected $data;

    public function __construct($message, $code = 400, $data = [])
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'code' => $this->getCode(),
            'status' => false,
            'data' => $this->data,
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
