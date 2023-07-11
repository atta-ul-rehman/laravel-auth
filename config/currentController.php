<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

return [
    'controllerName' => function () {
        $controllerAction = Str::afterLast(Route::getCurrentRoute()->getAction()['controller'], '\\');
        return explode('@', $controllerAction)[0];
    },
    'controllerMethod' => function () {
        $controllerAction = Str::afterLast(Route::getCurrentRoute()->getAction()['controller'], '\\');
        return explode('@', $controllerAction)[1];
    }
];
