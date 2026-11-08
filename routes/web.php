<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminGameController;
use App\Http\Controllers\Admin\AdminStatsController;
use App\Http\Controllers\Admin\AdminTeamController;
use App\Http\Controllers\Admin\AdminPlayerController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDonationController;
use App\Http\Controllers\Admin\AdminSyncController;
use App\Http\Controllers\Admin\AdminApiController;
use Illuminate\Support\Facades\Route;

// ===== PÚBLICAS =====
Route::get('/', [HomeController::class, 'index'])->name('home');

// Notícias
Route::get('/noticias', [PostController::class, 'index'])->name('noticias.index');
Route::get('/noticias/{post:slug}', [PostController::class, 'show'])->name('noticias.show');
    Route::get('/recentes', [PostController::class, 'recentes'])->name('recentes.index');
    Route::get('/recentes/{post:slug}', [PostController::class, 'showRecente'])->name('recentes.show');

// Jogos
Route::get('/jogos', [GameController::class, 'index'])->name('jogos.index');
Route::get('/jogos/{game}', [GameController::class, 'show'])->name('jogos.show');

// Estatísticas
Route::get('/estatisticas', [StatsController::class, 'index'])->name('estatisticas.index');
Route::get('/estatisticas/{team:abbreviation}', [StatsController::class, 'showTeam'])->name('estatisticas.team');

// Jogadores
Route::get('/jogadores', [PlayerController::class, 'index'])->name('jogadores.index');
Route::get('/jogadores/{player:slug}', [PlayerController::class, 'show'])->name('jogadores.show');

// Doações
Route::get('/doacoes', [DonationController::class, 'index'])->name('donations.index');
Route::post('/doacoes/gerar-pix', [DonationController::class, 'generatePix'])->name('donations.generate');
Route::post('/doacoes/webhook', [DonationController::class, 'webhook'])->name('donations.webhook');

// Locale & Theme
Route::post('/locale', LocaleController::class)->name('locale');
Route::post('/theme', ThemeController::class)->name('theme');

// ===== AUTENTICAÇÃO =====
Route::middleware('auth')->group(function () {
    Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Comentários (usuários logados)
    Route::post('/posts/{post}/comentarios', [CommentController::class, 'store'])->name('comments.store');
});

// ===== ADMIN =====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('posts', AdminPostController::class);
    Route::resource('games', AdminGameController::class);
    Route::resource('stats', AdminStatsController::class);
    Route::resource('teams', AdminTeamController::class);

    // Somente edicao: jogadores sao criados pela sincronizacao das APIs.
    Route::get('/jogadores', [AdminPlayerController::class, 'index'])->name('players.index');
    Route::get('/jogadores/{player}/editar', [AdminPlayerController::class, 'edit'])->name('players.edit');
    Route::put('/jogadores/{player}', [AdminPlayerController::class, 'update'])->name('players.update');

    Route::get('/comentarios', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::post('/comentarios/{comment}/aprovar', [AdminCommentController::class, 'approve'])->name('comments.approve');
    Route::delete('/comentarios/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comentarios/{comment}/excluir', [AdminCommentController::class, 'destroy'])->name('comments.delete');

    Route::get('/usuarios', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/usuarios/{user}/bloquear', [AdminUserController::class, 'block'])->name('users.block');
    Route::delete('/usuarios/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    Route::get('/doacoes', [AdminDonationController::class, 'index'])->name('donations.index');

    Route::post('/sync/teams', [AdminSyncController::class, 'syncTeams'])->name('sync.teams');
    Route::post('/sync/games', [AdminSyncController::class, 'syncGames'])->name('sync.games');

    Route::get('/api/search-teams', [AdminApiController::class, 'searchTeams'])->name('api.search-teams');
    Route::get('/api/team-info', [AdminApiController::class, 'getTeamInfo'])->name('api.team-info');
});

require __DIR__.'/auth.php';
