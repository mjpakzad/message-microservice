<?php

namespace App\Service\ServiceManagement;

use Illuminate\Support\Facades\Http;

class UserService
{
    protected array $headers = [];

    public function __construct()
    {
        $this->headers = [
            'Accept' => 'application/json',
            'Content-type' => 'application/json',
            'Authorization' => request()->headers->get('Authorization'),
        ];
    }

    /**
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        $response = Http::withHeaders($this->headers)->post(config('microservice.user.endpoint') . 'login');
        return $response->successful();
    }

    /**
     * @return mixed
     */
    public function me(): mixed
    {
        $response = Http::withHeaders($this->headers)->get(config('microservice.user.endpoint') . 'me');
        return $response->json();
    }

    public function getUserId(): int
    {
        $user = $this->me();
        return $user['data']['user_id'];
    }
}
