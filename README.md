# 🏀 Tudo NBA Basketball

Website completo sobre NBA desenvolvido em Laravel 11 com autenticação Breeze (Blade), MySQL, Redis, Bootstrap 5 e integração com APIs externas.

## Funcionalidades

### Páginas
- **Home** - Destaques de notícias, próximos jogos, jogos ao vivo
- **Notícias** - Listagem de artigos com filtros e busca
- **Horário de Jogos** - Calendário de jogos com filtro por time
- **Estatísticas** - Grid de times com detalhes de jogadores e estatísticas
- **Doações** - Sistema de doações via Pix com QR Code
- **Login/Registro** - Autenticação de usuários com papéis (admin/user)

### Recursos
- ✅ Modo Claro/Escuro com persistência
- ✅ Internacionalização (Português/Inglês)
- ✅ Autenticação com Laravel Breeze
- ✅ Painel Administrativo completo
- ✅ Integração com APIs balldontlie.io e API-Sports
- ✅ Doações via Pix com QR Code
- ✅ Notificações por e-mail (SMTP)
- ✅ Docker para deploy

## Stack Tecnológica

- **Backend:** PHP 8.2+ / Laravel 11
- **Autenticação:** Laravel Breeze (Blade)
- **Banco de Dados:** MySQL 8
- **Cache/Filas:** Redis
- **Frontend:** Blade + Bootstrap 5 + JavaScript vanilla
- **Pagamento:** Pix (chillerlan/php-qrcode)
- **E-mail:** SMTP Gmail
- **APIs:** balldontlie.io e API-Sports
- **Deploy:** Docker Compose

## Instalação

### Pré-requisitos
- PHP 8.2+
- Composer
- Node.js (npm)
- MySQL 8
- Redis

### Passo a passo

1. Clone o repositório:
```bash
git clone https://github.com/seu-usuario/nba-website.git
cd nba-website
```

2. Instale as dependências:
```bash
composer install
npm install
```

3. Configure o ambiente:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure o banco de dados no `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nba_website
DB_USERNAME=root
DB_PASSWORD=
```

5. Configure as APIs no `.env`:
```
BALLDONTLIE_API_KEY=sua_chave_api
APISPORTS_KEY=sua_chave_api
```

6. Execute as migrations:
```bash
php artisan migrate
```

7. Crie o usuário admin:
```bash
php artisan tinker
```
```php
App\Models\User::create(['name' => 'Admin', 'email' => 'admin@email.com', 'password' => bcrypt('password'), 'role' => 'admin', 'email_verified_at' => now()]);
```

8. Sincronize os dados da NBA:
```bash
php artisan nba:sync
```

9. Inicie o servidor:
```bash
php artisan serve
```

## Docker

Para usar com Docker:

```bash
docker-compose up -d
docker-compose exec app php artisan migrate
docker-compose exec app php artisan nba:sync
```

Acesse: http://localhost:8080

## Estrutura do Projeto

```
nba-website/
├── app/
│   ├── Console/Commands/     # Comandos Artisan
│   ├── Http/
│   │   ├── Controllers/      # Controladores
│   │   └── Middleware/       # Middleware (Admin, Locale)
│   ├── Mail/                 # Mailables
│   ├── Models/               # Models Eloquent
│   └── Services/             # Serviços (APIs, Pix)
├── database/migrations/      # Migrations
├── lang/                     # Arquivos de tradução (pt/en)
├── resources/views/          # Views Blade
│   ├── admin/                # Painel administrativo
│   ├── donations/            # Páginas de doação
│   ├── games/                # Páginas de jogos
│   ├── posts/                # Páginas de notícias
│   └── stats/                # Páginas de estatísticas
├── routes/web.php            # Rotas
├── docker-compose.yml        # Configuração Docker
└── .env                      # Variáveis de ambiente
```

## APIs Externas

### balldontlie.io
- Dados históricos de times, jogadores e estatísticas
- Sincronização diária via Scheduler

### API-Sports
- Dados ao vivo e calendário de jogos
- Cache de 60 segundos para dados ao vivo

## Comandos Úteis

```bash
# Sincronizar dados da NBA
php artisan nba:sync

# Sincronizar apenas times
php artisan nba:sync --type=teams

# Sincronizar apenas jogos
php artisan nba:sync --type=games

# Iniciar fila de jobs
php artisan queue:work redis

# Rodar agendador
php artisan schedule:run
```

## Licença

MIT License