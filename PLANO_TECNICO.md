# 🏀 Tudo NBA Basketball — Plano Técnico Completo

## 1. Visão Geral

Site de portfólio pessoal em Laravel 11 para demonstrar habilidades em back-end, com tema NBA (análises, opiniões, cobertura de jogos).

---

## 2. Modelagem do Banco de Dados

### 2.1 Tabelas Principais

```
users
├── id (PK, bigint, auto_increment)
├── name (string, 255)
├── email (string, 255, unique)
├── email_verified_at (datetime, nullable)
├── password (string, 255)
├── role (enum: 'admin', 'user') DEFAULT 'user'
├── avatar (string, 255, nullable)
├── is_blocked (boolean) DEFAULT false
├── locale (enum: 'pt', 'en') DEFAULT 'pt'
├── theme (enum: 'light', 'dark') DEFAULT 'light')
├── notify_email (boolean) DEFAULT true
├── remember_token (string, 100, nullable)
├── created_at (timestamp)
├── updated_at (timestamp)
└── INDEX: email

posts
├── id (PK, bigint, auto_increment)
├── user_id (FK → users.id, CASCADE)
├── title (string, 255)
├── slug (string, 255, unique)
├── content (longText)
├── excerpt (text, nullable)
├── cover_image (string, 500, nullable)
├── category (enum: 'noticia', 'jogo', 'estatistica', 'recente', 'analise')
├── status (enum: 'draft', 'published') DEFAULT 'draft'
├── published_at (datetime, nullable)
├── views_count (integer) DEFAULT 0
├── created_at (timestamp)
├── updated_at (timestamp)
├── INDEX: slug, category, status, published_at
└── FULLTEXT: title, content

teams
├── id (PK, bigint, auto_increment)
├── api_id (integer, unique)
├── name (string, 255)
├── abbreviation (string, 10)
├── city (string, 255)
├── state (string, 255, nullable)
├── conference (enum: 'East', 'West')
├── division (string, 255)
├── arena_name (string, 255, nullable)
├── arena_capacity (integer, nullable)
├── arena_location (string, 255, nullable)
├── founded_year (integer, nullable)
├── championships_count (integer) DEFAULT 0
├── logo_url (string, 500, nullable)
├── created_at (timestamp)
├── updated_at (timestamp)
└── INDEX: abbreviation, conference

players
├── id (PK, bigint, auto_increment)
├── api_id (integer, unique)
├── team_id (FK → teams.id, SET NULL, nullable)
├── full_name (string, 255)
├── nickname (string, 255, nullable)
├── position (string, 50)
├── height_cm (decimal, 5,2, nullable)
├── weight_kg (decimal, 5,2, nullable)
├── wingspan_cm (decimal, 5,2, nullable)
├── birth_date (date, nullable)
├── nationality (string, 100, nullable)
├── jersey_number (string, 5, nullable)
├── photo_url (string, 500, nullable)
├── created_at (timestamp)
├── updated_at (timestamp)
├── INDEX: full_name, team_id
└── FOREIGN KEY: team_id

player_team_history
├── id (PK, bigint, auto_increment)
├── player_id (FK → players.id, CASCADE)
├── team_id (FK → teams.id, CASCADE)
├── start_date (date)
├── end_date (date, nullable)
├── created_at (timestamp)
├── updated_at (timestamp)
└── INDEX: player_id, team_id

games
├── id (PK, bigint, auto_increment)
├── api_id (integer, unique, nullable)
├── home_team_id (FK → teams.id, CASCADE)
├── away_team_id (FK → teams.id, CASCADE)
├── game_date (datetime)
├── arena (string, 255, nullable)
├── status (enum: 'scheduled', 'live', 'finished') DEFAULT 'scheduled'
├── home_score (integer) DEFAULT 0
├── away_score (integer) DEFAULT 0
├── season (string, 9)
├── highlights (json, nullable)
├── created_at (timestamp)
├── updated_at (timestamp)
├── INDEX: game_date, status, season
└── FOREIGN KEYS: home_team_id, away_team_id

game_stats
├── id (PK, bigint, auto_increment)
├── game_id (FK → games.id, CASCADE)
├── team_id (FK → teams.id, CASCADE)
├── field_goals_made (integer) DEFAULT 0
├── field_goals_attempted (integer) DEFAULT 0
├── three_pointers_made (integer) DEFAULT 0
├── three_pointers_attempted (integer) DEFAULT 0
├── free_throws_made (integer) DEFAULT 0
├── free_throws_attempted (integer) DEFAULT 0
├── rebounds (integer) DEFAULT 0
├── assists (integer) DEFAULT 0
├── steals (integer) DEFAULT 0
├── blocks (integer) DEFAULT 0
├── turnovers (integer) DEFAULT 0
├── points (integer) DEFAULT 0
├── created_at (timestamp)
├── updated_at (timestamp)
└── INDEX: game_id, team_id

player_stats_career
├── id (PK, bigint, auto_increment)
├── player_id (FK → players.id, CASCADE, unique)
├── games_played (integer) DEFAULT 0
├── points_per_game (decimal, 5,2) DEFAULT 0
├── rebounds_per_game (decimal, 5,2) DEFAULT 0
├── assists_per_game (decimal, 5,2) DEFAULT 0
├── steals_per_game (decimal, 5,2) DEFAULT 0
├── blocks_per_game (decimal, 5,2) DEFAULT 0
├── field_goal_percentage (decimal, 5,2) DEFAULT 0
├── three_point_percentage (decimal, 5,2) DEFAULT 0
├── free_throw_percentage (decimal, 5,2) DEFAULT 0
├── created_at (timestamp)
├── updated_at (timestamp)
└── FOREIGN KEY: player_id

player_stats_season
├── id (PK, bigint, auto_increment)
├── player_id (FK → players.id, CASCADE)
├── season (string, 9)
├── games_played (integer) DEFAULT 0
├── points_per_game (decimal, 5,2) DEFAULT 0
├── rebounds_per_game (decimal, 5,2) DEFAULT 0
├── assists_per_game (decimal, 5,2) DEFAULT 0
├── field_goal_percentage (decimal, 5,2) DEFAULT 0
├── three_point_percentage (decimal, 5,2) DEFAULT 0
├── free_throw_percentage (decimal, 5,2) DEFAULT 0
├── created_at (timestamp)
├── updated_at (timestamp)
├── INDEX: player_id, season
└── FOREIGN KEY: player_id

comments
├── id (PK, bigint, auto_increment)
├── user_id (FK → users.id, CASCADE)
├── post_id (FK → posts.id, CASCADE)
├── parent_id (FK → comments.id, SET NULL, nullable) ← replies
├── content (text)
├── approved (boolean) DEFAULT false
├── created_at (timestamp)
├── updated_at (timestamp)
└── INDEX: post_id, approved

donations
├── id (PK, bigint, auto_increment)
├── user_id (FK → users.id, SET NULL, nullable)
├── amount (decimal, 10,2)
├── transaction_id (string, 255, unique)
├── status (enum: 'pending', 'confirmed', 'failed') DEFAULT 'pending'
├── qr_code_payload (text, nullable)
├── qr_code_image (text, nullable)
├── donor_name (string, 255, nullable)
├── donor_email (string, 255, nullable)
├── message (text, nullable)
├── paid_at (datetime, nullable)
├── created_at (timestamp)
├── updated_at (timestamp)
├── INDEX: status, transaction_id
└── FOREIGN KEY: user_id

notifications
├── id (PK, uuid)
├── type (string, 255)
├── notifiable_type (string, 255)
├── notifiable_id (bigint)
├── data (json)
├── read_at (datetime, nullable)
├── created_at (timestamp)
├── updated_at (timestamp)
└── INDEX: notifiable_type, notifiable_id

jobs (Redis)
├── id (PK, bigint)
├── queue (string)
├── payload (longText)
├── attempts (tinyint)
├── reserved_at (unsignedBigInteger, nullable)
├── available_at (unsignedBigInteger)
├── created_at (unsignedBigInteger)
└── INDEX: queue, reserved_at, available_at

failed_jobs
├── id (PK, bigint)
├── uuid (string, unique)
├── connection (text)
├── queue (text)
├── payload (longText)
├── exception (longText)
├── failed_at (timestamp)
└── INDEX: uuid

cache
├── key (string, unique)
├── value (mediumText)
├── expiration (integer)
└── INDEX: key

sessions
├── id (string, unique)
├── user_id (bigint, nullable)
├── ip_address (string, 45, nullable)
├── user_agent (text, nullable)
├── payload (longText)
├── last_activity (integer)
└── INDEX: user_id, last_activity
```

---

## 3. Estrutura de Rotas

```php
// routes/web.php

// ===== PÚBLICAS =====
Route::get('/', [HomeController::class, 'index'])->name('home');

// Notícias
Route::get('/noticias', [PostController::class, 'index'])->name('posts.index');
Route::get('/noticias/{post:slug}', [PostController::class, 'show'])->name('posts.show');

// Jogos
Route::get('/jogos', [GameController::class, 'index'])->name('games.index');
Route::get('/jogos/{game}', [GameController::class, 'show'])->name('games.show');

// Estatísticas
Route::get('/estatisticas', [StatsController::class, 'index'])->name('stats.index');
Route::get('/estatisticas/{team:abbreviation}', [StatsController::class, 'showTeam'])->name('stats.team');
Route::get('/estatisticas/jogador/{player}', [StatsController::class, 'showPlayer'])->name('stats.player');

// Análises
Route::get('/analises', [AnalysisController::class, 'index'])->name('analysis.index');
Route::get('/analises/{post:slug}', [AnalysisController::class, 'show'])->name('analysis.show');

// Doações
Route::get('/doacoes', [DonationController::class, 'index'])->name('donations.index');
Route::post('/doacoes/gerar-pix', [DonationController::class, 'generatePix'])->name('donations.generate');
Route::post('/doacoes/webhook', [DonationController::class, 'webhook'])->name('donations.webhook');

// ===== AUTENTICAÇÃO =====
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('forgot-password', [PasswordResetLinkRequestController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkRequestController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('verify-email', [EmailVerificationPromptController::class, 'create'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware('signed')->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'create'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Comentários (usuários logados)
    Route::post('/posts/{post}/comentarios', [CommentController::class, 'store'])->name('comments.store');
});

// ===== LOCALE & THEME =====
Route::post('/locale', LocaleController::class)->name('locale');
Route::post('/theme', ThemeController::class)->name('theme');

// ===== ADMIN =====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Posts (Notícias, Análises, Recentes)
    Route::resource('posts', AdminPostController::class);

    // Jogos
    Route::resource('games', AdminGameController::class);

    // Estatísticas
    Route::resource('stats', AdminStatsController::class);

    // Times
    Route::resource('teams', AdminTeamController::class);

    // Jogadores
    Route::resource('players', AdminPlayerController::class);

    // Comentários
    Route::get('/comentarios', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::post('/comentarios/{comment}/aprovar', [AdminCommentController::class, 'approve'])->name('comments.approve');
    Route::delete('/comentarios/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');

    // Usuários
    Route::get('/usuarios', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/usuarios/{user}/bloquear', [AdminUserController::class, 'block'])->name('users.block');
    Route::delete('/usuarios/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Doações
    Route::get('/doacoes', [AdminDonationController::class, 'index'])->name('donations.index');
    Route::post('/doacoes/{donation}/confirmar', [AdminDonationController::class, 'confirm'])->name('donations.confirm');

    // API Sync
    Route::post('/sync/teams', [AdminSyncController::class, 'syncTeams'])->name('sync.teams');
    Route::post('/sync/players', [AdminSyncController::class, 'syncPlayers'])->name('sync.players');
    Route::post('/sync/games', [AdminSyncController::class, 'syncGames'])->name('sync.games');
});
```

---

## 4. Estrutura de Controllers

```
app/Http/Controllers/
├── Auth/
│   ├── AuthenticatedSessionController.php
│   ├── RegisteredUserController.php
│   ├── PasswordResetLinkRequestController.php
│   ├── NewPasswordController.php
│   ├── EmailVerificationPromptController.php
│   ├── VerifyEmailController.php
│   ├── EmailVerificationNotificationController.php
│   └── ConfirmablePasswordController.php
├── HomeController.php
├── PostController.php
├── GameController.php
├── StatsController.php
├── AnalysisController.php
├── DonationController.php
├── CommentController.php
├── LocaleController.php
├── ThemeController.php
├── ProfileController.php
├── Admin/
│   ├── AdminDashboardController.php
│   ├── AdminPostController.php
│   ├── AdminGameController.php
│   ├── AdminStatsController.php
│   ├── AdminTeamController.php
│   ├── AdminPlayerController.php
│   ├── AdminCommentController.php
│   ├── AdminUserController.php
│   ├── AdminDonationController.php
│   └── AdminSyncController.php
```

---

## 5. Estrutura de Views

```
resources/views/
├── layouts/
│   ├── app.blade.php              ← Layout principal (nav, footer, theme)
│   └── guest.blade.php            ← Layout para visitantes
├── home.blade.php
├── posts/
│   ├── index.blade.php
│   └── show.blade.php
├── games/
│   ├── index.blade.php
│   └── show.blade.php
├── stats/
│   ├── index.blade.php
│   ├── team.blade.php
│   └── player.blade.php
├── analysis/
│   ├── index.blade.php
│   └── show.blade.php
├── donations/
│   └── index.blade.php
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   ├── forgot-password.blade.php
│   ├── reset-password.blade.php
│   ├── verify-email.blade.php
│   └── confirm-password.blade.php
├── profile/
│   ├── edit.blade.php
│   └── partials/
│       ├── update-profile-information-form.blade.php
│       └── update-password-form.blade.php
├── components/
│   ├── post-card.blade.php
│   ├── game-card.blade.php
│   ├── team-card.blade.php
│   ├── player-card.blade.php
│   └── comment.blade.php
├── emails/
│   ├── welcome.blade.php
│   ├── new-comment.blade.php
│   └── donation-confirmed.blade.php
└── admin/
    ├── dashboard.blade.php
    ├── posts/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── games/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── stats/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── teams/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── players/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── comments/
    │   └── index.blade.php
    ├── users/
    │   └── index.blade.php
    └── donations/
        └── index.blade.php
```

---

## 6. Arquitetura de Integração com API Externa

### 6.1 Services

```
app/Services/
├── BalldontlieService.php      ← dados de jogadores e times
├── ApiSportsService.php        ← jogos e placares
├── PixService.php              ← geração de QR Code Pix
└── NbaSyncService.php          ← orquestra sincronização
```

### 6.2 Jobs

```
app/Jobs/
├── SyncTeamsJob.php
├── SyncPlayersJob.php
├── SyncGamesJob.php
├── SendWelcomeEmailJob.php
├── SendNewCommentEmailJob.php
├── SendDonationConfirmedEmailJob.php
└── ProcessDonationWebhookJob.php
```

### 6.3 Scheduler

```php
// routes/console.php
Schedule::command('nba:sync --type=teams')->daily();
Schedule::command('nba:sync --type=players')->daily();
Schedule::command('nba:sync --type=games')->daily();
```

### 6.4 Fluxo de Sincronização

```
[Scheduler diário] → [SyncTeamsJob] → [BalldontlieService::getTeams()] → [Team::updateOrCreate()]
                                    → [SyncPlayersJob] → [BalldontlieService::getPlayers()] → [Player::updateOrCreate()]
                                    → [SyncGamesJob] → [ApiSportsService::getGames()] → [Game::updateOrCreate()]
```

### 6.5 Fluxo de Doação Pix

```
[Usuário clica "Gerar Pix"]
    → [DonationController::generatePix()]
    → [PixService::generatePayload()]
    → [PixService::generateQrCode()]
    → [Donation::create()]
    → [Exibe QR Code ao usuário]

[Webhook do gateway recebido]
    → [DonationController::webhook()]
    → [ProcessDonationWebhookJob]
    → [Donation::update(status=confirmed)]
    → [SendDonationConfirmedEmailJob]
```

---

## 7. Middlewares

```
app/Http/Middleware/
├── EnsureUserIsAdmin.php       ← verifica role === 'admin'
├── LocaleMiddleware.php        ← define idioma (pt/en)
└── ThemeMiddleware.php         ← define tema (light/dark)
```

---

## 8. Mailables

```
app/Mail/
├── WelcomeMail.php
├── NewCommentMail.php
└── DonationConfirmedMail.php
```

---

## 9. Traduções

```
lang/
├── pt/
│   └── messages.php
└── en/
    └── messages.php
```

---

## 10. Plano de Desenvolvimento em Fases

### Fase 1 — Fundação (Semana 1)
- [x] Criar projeto Laravel 11
- [x] Instalar Breeze (Blade)
- [x] Configurar MySQL
- [x] Criar migrations e models
- [x] Configurar autenticação
- [x] Middleware admin
- [x] Layout base (Bootstrap 5)

### Fase 2 — Páginas Públicas (Semana 2)
- [ ] Home com destaques
- [ ] Listagem de notícias
- [ ] Página de notícia
- [ ] Listagem de jogos
- [ ] Página de jogo
- [ ] Estatísticas por time
- [ ] Ficha de jogador
- [ ] Análises/Opiniões

### Fase 3 — Painel Admin (Semana 3)
- [ ] Dashboard com estatísticas
- [ ] CRUD Notícias
- [ ] CRUD Jogos
- [ ] CRUD Estatísticas
- [ ] CRUD Análises
- [ ] Gerenciar Comentários
- [ ] Gerenciar Usuários
- [ ] Gerenciar Doações

### Fase 4 — APIs e Sincronização (Semana 4)
- [ ] Integrar balldontlie.io
- [ ] Integrar API-Sports
- [ ] Jobs de sincronização
- [ ] Scheduler
- [ ] Cache Redis

### Fase 5 — Doações e Notificações (Semana 5)
- [ ] Sistema de doação Pix
- [ ] Webhook de confirmação
- [ ] E-mails de notificação
- [ ] Filas Redis

### Fase 6 — Extras (Semana 6)
- [ ] Modo claro/escuro
- [ ] Internacionalização PT/EN
- [ ] Docker Compose
- [ ] Testes
- [ ] Deploy

---

## 11. Comandos Úteis

```bash
# Sincronizar dados da NBA
php artisan nba:sync --type=teams
php artisan nba:sync --type=players
php artisan nba:sync --type=games

# Criar admin
php artisan db:seed --class=AdminUserSeeder

# Limpar caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rodar filas
php artisan queue:work redis

# Rodar agendador
php artisan schedule:run
```

---

## 12. Credenciais de Teste

```
Admin:
  Email: admin@tudonba.com
  Senha: Admin@123

MySQL:
  Host: 127.0.0.1
  Port: 3306
  Database: nba_website
  User: root
  Password: (vazio)
```