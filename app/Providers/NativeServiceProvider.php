<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NativeServiceProvider extends ServiceProvider
{
    public function plugins(): array
    {
        return [];  // Scanner is built-in, no plugin registration needed
    }

    public function boot(): void
    {
        //
    }
}