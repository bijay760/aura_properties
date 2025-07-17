<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface ProfileInterface
{
    public function getProfile(): array;
}
