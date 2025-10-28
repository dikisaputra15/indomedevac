<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class JwtLoginMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kalau user sudah login, lanjut saja
        if (Auth::check()) {
            return $next($request);
        }

        $token = $request->query('token');

        if ($token) {
            try {
                $secret = env('JWT_AUTH_SECRET_KEY', 'Chelsea123!@#');
                $decoded = JWT::decode($token, new Key($secret, 'HS256'));

                $validIssuers = [
                    'https://id.concordreview.com',
                    'https://id.concordcmt.com',
                ];

                if (!isset($decoded->iss) || !in_array($decoded->iss, $validIssuers)) {
                    return response('Issuer tidak valid', 403);
                }

                if (isset($decoded->exp) && $decoded->exp < time()) {
                    return response('Token sudah kadaluarsa', 403);
                }

                $email = $decoded->data->user->email ?? null;
                if (!$email) {
                    return response('Email tidak ditemukan dalam token', 403);
                }

                $user = User::where('email', $email)->first();

                if (!$user) {
                    return response('Token tidak valid (user tidak ditemukan)', 403);
                }

                Auth::login($user);

                $cleanUrl = $request->url();
                return redirect()->to($cleanUrl);

            } catch (\Exception $e) {
                return response('Token error: ' . $e->getMessage(), 403);
            }
        }

        return $next($request);
    }
}
