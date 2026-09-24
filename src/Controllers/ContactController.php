<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\RateLimiter;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Models\ContactMessage;
use App\Models\SeoMeta;
use App\Models\SocialLink;

final class ContactController
{
    public function show(Request $req): Response
    {
        return Response::view('pages/contact', [
            'socials' => SocialLink::visible(),
            'seo' => SeoMeta::forPage('contact'),
            'sent' => Session::flashGet('sent') === true,
            'errors' => Session::flashGet('errors') ?? [],
            'page' => 'contact',
        ]);
    }

    public function submit(Request $req): Response
    {
        $back = lurl('/contact');

        // Honeypot — bots fill hidden fields; humans never see "website".
        if (!empty($req->body['website'])) {
            return Response::redirect($back);
        }

        $throttleKey = 'contact:' . $req->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            Session::flash('errors', ['form' => ['throttle']]);
            return Response::redirect($back);
        }

        $validator = Validator::make($req->body, [
            'name' => 'required|string|min:2|max:120',
            'email' => 'required|email|max:190',
            'message' => 'required|string|min:10|max:5000',
        ]);

        if ($validator->fails()) {
            Session::flash('errors', $validator->errors());
            Session::flashInput($req->body);
            return Response::redirect($back);
        }

        $data = $validator->validated();
        ContactMessage::create([
            'name' => trim((string) $data['name']),
            'email' => mb_strtolower(trim((string) $data['email'])),
            'message' => trim((string) $data['message']),
            'locale' => locale(),
            'ip_hash' => hash('sha256', $req->ip() . '|' . (string) env('APP_KEY', 'local')),
        ]);

        RateLimiter::hit($throttleKey, 3600);
        Session::flash('sent', true);
        return Response::redirect($back . '?sent=1');
    }
}
