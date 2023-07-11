<?php

namespace Modules\Auth\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\Auth\Http\Controllers\Controller;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/';
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
}
