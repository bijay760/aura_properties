<?php

namespace App\Helpers;

interface Json
{
    public function response(int $status, bool $error, string|array|null $response, string $message): array;
}
