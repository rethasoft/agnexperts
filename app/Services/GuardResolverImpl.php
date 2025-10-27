<?php

namespace App\Services;

class GuardResolverImpl implements GuardResolver
{
    private ?string $resolved = null;

    public function set(string $guard): void
    {
        $this->resolved = $guard;
    }

    public function guard(): string
    {
        return $this->resolved ?? 'tenant';
    }
}


