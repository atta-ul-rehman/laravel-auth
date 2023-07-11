<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Modules\Auth\Entities\Message;
use Modules\Auth\Events\NewMessageEvent;
use Illuminate\Support\Facades\View;
use Modules\Auth\Http\Requests\userLoginVal;

class ChatMessageController extends Controller
{
    public function authenticate(userLoginVal $request): RedirectResponse
    {
        $credentials = $request->validated();
        if (Auth::guard('web')->attempt($credentials)) {
            
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'
        ])->withInput();
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
 
    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('auth::chat');
//         ->with('messages', [
//   [
//     'user' => 'ahmad',
//     'message' => 'one',
//   ],
//   [
//     'user' => 'karim',
//     'message' => 'two',
//   ],
// ]);
    }

    public function login()
    {
        return View::make('auth::auth.login');
    }

    public function register()
    {
        return View::make('auth::auth.register');
    }

      /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        $user = $request->input('username');
        $message = $request->input('message');

        event(new NewMessageEvent($user, $message));

        return ['status' => 'success'];
    }
    
}
