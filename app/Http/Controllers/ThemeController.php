<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function __invoke(Request $request)
    {
        $theme = $request->input('theme', 'light');

        if (!in_array($theme, ['light', 'dark'])) {
            $theme = 'light';
        }

        session(['theme' => $theme]);

        if (auth()->check()) {
            auth()->user()->update(['theme_preference' => $theme]);
        }

        return response()->json(['theme' => $theme]);
    }
}