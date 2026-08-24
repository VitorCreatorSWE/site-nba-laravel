<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(User $user, Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        $user->update($validated);

        return back()->with('success', 'Papel do usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode excluir sua própria conta.');
        }

        $user->delete();

        return back()->with('success', 'Usuário removido com sucesso!');
    }
}