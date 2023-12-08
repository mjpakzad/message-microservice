<?php

namespace App\Http\Middleware;

use App\Service\ServiceManagement\UserServiceInterface;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserISAuthenticated
{
    public function __construct(public UserServiceInterface $userService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @throws \Throwable
     */
    public function handle(Request $request, Closure $next): Response
    {
        throw_unless($this->userService->isAuthenticated(), AuthenticationException::class);
        return $next($request);
    }
}
