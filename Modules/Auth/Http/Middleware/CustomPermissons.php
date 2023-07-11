<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class customPermissons
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /*
            Controller name and Action name
        */
        $controllerMethod = config('currentController.controllerMethod')();
        $controllerName = config('currentController.controllerName')();
        /*
            Check if the controller and action Exists in Configurations
        */
        $permissions = config('customPermissions', []);
        $user = $request?->user();

        $response = null;
        
        if (!array_key_exists($controllerName, $permissions))
        $response = 'The requested controller does not exist in Configration'; 
        else if(!array_key_exists($controllerMethod, $permissions[$controllerName]))
        $response = "This request is not registered with the configration service";
        else if ($user == null)
        $response = 'User is not logged in';
        else if (!$user->can($permissions[$controllerName][$controllerMethod][0]))
        $response = 'User do not have required permissions';
        else 
        $response = "Internal server error occurred";
        
        
        if (
            !$user ||
            !array_key_exists($controllerName, $permissions) ||
            !array_key_exists($controllerMethod, $permissions[$controllerName]) ||
            empty($permissions[$controllerName][$controllerMethod]) ||
            !$user->can($permissions[$controllerName][$controllerMethod][0])) {
            return response()->json(
                [
                    'success' => false,
                    'Message' => $response,
                ],
                Response::HTTP_FORBIDDEN
            );
        }
        return $next($request);
    }
}
