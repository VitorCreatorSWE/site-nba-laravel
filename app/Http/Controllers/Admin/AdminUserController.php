<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function block(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode bloquear sua própria conta.');
        }

        $user->update(['is_blocked' => !$user->is_blocked]);

        $status = $user->is_blocked ? 'bloqueado' : 'desbloqueado';

        return back()->with('success', "Usuário {$status} com sucesso!");
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', __('messages.cannot_delete_admin'));
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode excluir sua própria conta.');
        }

        $user->delete();

        return back()->with('success', 'Usuário removido com sucesso!');
    }
}