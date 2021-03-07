<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request, $page) {
        return view('account.edit', [ 'account' => null ]);
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        if($request->isMethod('POST')) {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return redirect()->intended('dashboard');
            }

            $errors = [
                'email' => 'The provided credentials do not match our records.',
            ];
        }
        return view('account.login', []);
    }

    /**
     * Handle an signup attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        if($request->isMethod('POST')) {
            $credentials = $request->only('email', 'password');
//
//            if (Auth::attempt($credentials)) {
//                $request->session()->regenerate();
//
//                return redirect()->intended('dashboard');
//            }

            $errors = [
                'email' => 'The provided credentials do not match our records.',
            ];
        }
        return view('account.register', []);
    }

    /**
     * Handle an recover attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function recover(Request $request)
    {
        if($request->isMethod('POST')) {
            $credentials = $request->only('email', 'password');
//
//            if (Auth::attempt($credentials)) {
//                $request->session()->regenerate();
//
//                return redirect()->intended('dashboard');
//            }

            $errors = [
                'email' => 'The provided credentials do not match our records.',
            ];
        }
        return view('account.recover', []);
    }
}
