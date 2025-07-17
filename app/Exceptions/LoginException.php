<?php

namespace App\Exceptions;

use Exception;

class LoginException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }

    /**
     * Get the underlying response instance.
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function getResponse()
    {
        $message = $this->message;
        $data = [];
        $code = $this->code;
        $status = false;
        return response()->json(compact('code', 'status', 'data', 'message'), $this->code);
    }
}
