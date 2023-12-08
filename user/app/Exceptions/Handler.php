<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function (AuthenticationException $e, $request) {
            throw_if ($request->is('api/*'), AuthentionFaildException::class);

        });
        $this->renderable(function (ValidationException $e, $request) {
            if($request->is('api/*')) {
                return response()->error([$e->errors()], $e->status);
            }
        });
    }
}
