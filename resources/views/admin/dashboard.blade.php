@extends('layouts.app')

@section('title', __('messages.admin_dashboard'))

@section('content')
<div class="container py-5">
    <h1 class="mb-4">📊 {{ __('messages.admin_dashboard') }}</h1>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-newspaper" style="font-size:2rem;"></i>
                    <div class="h2 mt-2">{{ $stats['total_posts'] }}</div>
                    <p class="mb-0">{{ __('messages.posts') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="bi bi-calendar3" style="font-size:2rem;"></i>
                    <div class="h2 mt-2">{{ $stats['total_games'] }}</div>
                    <p class="mb-0">{{ __('messages.games') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="bi bi-people" style="font-size:2rem;"></i>
                    <div class="h2 mt-2">{{ $stats['total_users'] }}</div>
                    <p class="mb-0">{{ __('messages.users') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <i class="bi bi-heart-fill" style="font-size:2rem;"></i>
                    <div class="h2 mt-2">R$ {{ number_format($stats['total_donations'], 2, ',', '.') }}</div>
                    <p class="mb-0">{{ __('messages.donations_label') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="h3">{{ $stats['total_comments'] }}</div>
                    <p class="mb-0 text-muted">{{ __('messages.comments') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="h3">{{ $stats['total_teams'] }}</div>
                    <p class="mb-0 text-muted">{{ __('messages.teams') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="h3">{{ $stats['draft_posts'] }}</div>
                    <p class="mb-0 text-muted">{{ __('messages.drafts') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('messages.recent_posts') }}</h5>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.see_all_f') }}</a>
                </div>
                <div class="card-body">
                    @forelse($recentPosts as $post)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <strong>{{ Str::limit($post->title, 40) }}</strong>
                                <br>
                                <small class="text-muted">{{ $post->user->name }} • {{ $post->created_at->format('d/m/Y') }}</small>
                            </div>
                            <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'secondary' }}">
                                {{ $post->status === 'published' ? __('messages.status_published') : __('messages.status_draft') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted text-center">{{ __('messages.no_posts') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('messages.recent_comments') }}</h5>
                    <a href="{{ route('admin.comments.index') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.see_all_m') }}</a>
                </div>
                <div class="card-body">
                    @forelse($recentComments as $comment)
                        <div class="mb-3 pb-3 border-bottom">
                            <strong>{{ $comment->user->name }}</strong>
                            <small class="text-muted d-block">{{ Str::limit($comment->content, 50) }}</small>
                        </div>
                    @empty
                        <p class="text-muted text-center">{{ __('messages.no_comments') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('messages.donations_recent') }}</h5>
                    <a href="{{ route('admin.donations.index') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.see_all_f') }}</a>
                </div>
                <div class="card-body">
                    @forelse($recentDonations as $donation)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <strong>{{ $donation->donor_name ?? __('messages.anonymous') }}</strong>
                                <br>
                                <small class="text-muted">{{ $donation->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <span class="badge bg-{{ $donation->status === 'confirmed' ? 'success' : 'warning' }}">
                                {{ $donation->amount_formatted }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted text-center">{{ __('messages.no_donations') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-3">
        <div class="col-md-3">
            <a href="{{ route('admin.posts.index') }}" class="card text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-newspaper text-primary" style="font-size:2rem;"></i>
                    <h5 class="mt-2">{{ __('messages.posts') }}</h5>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.games.index') }}" class="card text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-calendar3 text-success" style="font-size:2rem;"></i>
                    <h5 class="mt-2">{{ __('messages.games') }}</h5>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.teams.index') }}" class="card text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-shield-shaded text-info" style="font-size:2rem;"></i>
                    <h5 class="mt-2">{{ __('messages.teams') }}</h5>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.players.index') }}" class="card text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-people text-warning" style="font-size:2rem;"></i>
                    <h5 class="mt-2">{{ __('messages.players') }}</h5>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4 mt-3">
        <div class="col-md-3">
            <a href="{{ route('admin.users.index') }}" class="card text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-person-gear text-danger" style="font-size:2rem;"></i>
                    <h5 class="mt-2">{{ __('messages.manage_users') }}</h5>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
