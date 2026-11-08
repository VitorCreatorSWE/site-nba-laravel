<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $allowedEmails = ['admin@basq.nba', 'usuario@basq.nba'];

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                'unique:'.User::class,
                function ($attribute, $value, $fail) use ($allowedEmails) {
                    if (!in_array($value, $allowedEmails, true) && !str_ends_with($value, '@gmail.com')) {
                        $fail(__('messages.email_gmail_only'));
                    }
                },
            ],
            'password' => ['required', 'confirmed', 'min:6'],
        ], [
            'name.unique' => __('messages.name_unique'),
            'email.unique' => __('messages.email_unique'),
            'password.min' => __('messages.password_min_6'),
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
