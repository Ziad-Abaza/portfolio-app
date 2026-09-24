<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\RateLimiter;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;

final class AuthController
{
    public function loginPage(Request $req): Response
    {
        return Response::view('admin/login', [
            'error' => Session::flashGet('error'),
        ], 200, layout: null);
    }

    public function login(Request $req): Response
    {
        $key = 'login:' . $req->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            Session::flash('error', 'Too many attempts. Try again in ' . RateLimiter::availableIn($key) . 's.');
            return Response::redirect('/admin/login');
        }

        $validator = Validator::make($req->body, [
            'email' => 'required|email|max:190',
            'password' => 'required|string|max:200',
        ]);
        $data = $validator->validated();

        if ($validator->fails() || !Auth::attempt((string) $data['email'], (string) $data['password'])) {
            RateLimiter::hit($key, 300);
            Session::flash('error', 'Invalid credentials.');
            return Response::redirect('/admin/login');
        }

        RateLimiter::clear($key);
        return Response::redirect('/admin');
    }

    public function logout(Request $req): Response
    {
        Auth::logout();
        return Response::redirect('/admin/login');
    }
}
