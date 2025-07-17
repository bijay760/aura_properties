<?php

namespace App\Helpers;

interface Auth
{
    public function signIn(array $data): string;
    public function check(string $token): void;
    public function getData(): string;
}
