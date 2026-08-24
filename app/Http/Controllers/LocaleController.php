<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function __invoke(Request $request)
    {
        $locale = $request->input('locale', 'pt');

        if (!in_array($locale, ['pt', 'en'])) {
            $locale = 'pt';
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }

        return redirect()->back();
    }
}