@extends('layouts.app')

@section('title', __('messages.donation_title'))

@section('content')
<div class="d-flex align-items-center justify-content-center" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('images/apoiador-bg.jpg') }}') center/cover no-repeat; min-height: calc(100vh - 56px); width: 100%;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0"><span style="font-size:1.2em;">&#x1F3C0;</span> {{ __('messages.donation_title') }}</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted text-center">{{ __('messages.donation_description') }}</p>

                        @if(session('error'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>{{ session('error') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('info'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                {{ session('info') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('donations.generate') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="amount" class="form-label text-center d-block">
                                    {{ __('messages.donation_amount') }} (R$)
                                </label>
                                <input type="number"
                                       name="amount"
                                       id="amount"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       min="1"
                                       step="0.01"
                                       placeholder="10.00"
                                       value="{{ old('amount') }}"
                                       required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-qr-code me-2"></i>{{ __('messages.donation_generate') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
