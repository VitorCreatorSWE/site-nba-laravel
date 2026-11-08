<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MakeUser extends Command
{
    protected $signature = 'make:user
                            {--name= : Nome do usuario}
                            {--email= : E-mail (unico, obrigatorio)}
                            {--password= : Senha (omitir para gerar uma aleatoria)}
                            {--admin : Define o papel como admin em vez de user}';

    protected $description = 'Cria um usuario no banco (admin ou usuario comum)';

    public function handle(): int
    {
        $email = trim((string) $this->option('email'));

        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Informe um e-mail valido com --email.');

            return self::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->error("Ja existe um usuario com o e-mail {$email}.");

            return self::FAILURE;
        }

        $generated = $this->option('password') === null;
        $password = $generated ? Str::password(16) : (string) $this->option('password');

        $name = trim((string) $this->option('name')) ?: Str::before($email, '@');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $this->option('admin') ? 'admin' : 'user',
            'locale' => 'pt',
            'email_verified_at' => now(),
        ]);

        $this->info("Usuario #{$user->id} criado com o papel {$user->role}.");

        if ($generated) {
            $this->newLine();
            $this->line('  e-mail ..... ' . $user->email);
            $this->line('  senha ...... ' . $password);
            $this->newLine();
            $this->warn('Anote a senha: ela nao sera exibida de novo e nao tem valor padrao.');
        }

        $this->newLine();
        $this->line('Use "php artisan nba:generate-news" para gerar as noticias.');

        return self::SUCCESS;
    }
}
