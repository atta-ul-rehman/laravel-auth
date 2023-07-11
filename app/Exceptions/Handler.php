<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
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
        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
                return response()->json(
                    [
                        'success' => false,
                        'Message' => $e->getMessage(),
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
        });
        
        $this->renderable(function (\Illuminate\Database\QueryException $e, $request) {
            return response()->json(
                [
                    'success' => false,
                    'Message' => $e->getMessage(),
                ],
                Response::HTTP_UNAUTHORIZED
            );
    });

        
    }
}
