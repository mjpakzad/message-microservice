<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($data = [], $statusCode = 200) {
            $response = [
                'data' => $data,
                'server_time' => now(),
            ];
            return Response::json($response, $statusCode);
        });

        Response::macro('error', function ($data = [], $statusCode = 400) {
            $response = [
                'data' => $data,
                'server_time' => now(),
            ];
            return Response::json($response, $statusCode);
        });
    }
}
