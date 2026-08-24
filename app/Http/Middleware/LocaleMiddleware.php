<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        } elseif (auth()->check()) {
            app()->setLocale(auth()->user()->locale);
        }

        if (!session()->has('theme') && auth()->check()) {
            session(['theme' => auth()->user()->theme_preference ?? 'light']);
        }

        return $next($request);
    }
}
