<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return redirect()->route('login');
    }

    protected function unauthenticated($request, array $guards)
    {
        if (Auth::guest()) {
            return redirect('/login');
        }

        if ($request->route()->getPrefix() == 'api') {
            abort(response()->json(
                [
                    'status' => '401',
                    'message' => 'UnAuthourized',
                ],
                401
            ));
        }
    }
}