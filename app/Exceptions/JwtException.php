<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class JwtException extends Exception
{
    protected $message;
    protected $code;

    public function __construct($message = "Unauthorized", $code = 401)
    {
        parent::__construct($message, $code);
        $this->message = $message;
        $this->code = $code;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request): JsonResponse
    {
        return response()->json([
            'status' => false,
            'code' => $this->code,
            'message' => $this->message,
            'data' => [],
        ], $this->code);
    }
}
