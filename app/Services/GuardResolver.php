<?php

namespace App\Services;

interface GuardResolver
{
    public function guard(): string;
    public function set(string $guard): void;
}


