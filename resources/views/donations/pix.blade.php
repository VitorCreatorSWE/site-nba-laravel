@extends('layouts.app')

@section('title', __('messages.pix_qr_code'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-4"><i class="bi bi-check-circle text-success"></i> {{ __('messages.thank_you') }}</h3>

                    <div class="mb-4">
                        <div class="h2 text-primary">{{ $donation->amount_formatted }}</div>
                        <p class="text-muted">Transação: {{ $donation->transaction_id }}</p>
                    </div>

                    <div class="mb-4">
                        @if($donation->qr_code_image)
                            <img src="data:image/png;base64,{{ base64_encode($donation->qr_code_image) }}"
                                alt="QR Code Pix" class="img-fluid" style="max-width:300px;">
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">{{ __('messages.pix_code') }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="pixCode" value="{{ $donation->qr_code_payload }}" readonly>
                            <button class="btn btn-outline-primary" type="button" onclick="copyPixCode()">
                                <i class="bi bi-clipboard"></i> {{ __('messages.copy_code') }}
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Copie o código Pix acima e faça o pagamento pelo aplicativo do seu banco.
                        Após o pagamento, o site será atualizado automaticamente.
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary flex-fill">
                            {{ __('messages.back') }}
                        </a>
                        <button class="btn btn-success flex-fill" onclick="checkPayment()">
                            <i class="bi bi-check-circle"></i> {{ __('messages.confirm_payment') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyPixCode() {
    const pixCode = document.getElementById('pixCode');
    pixCode.select();
    document.execCommand('copy');

    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-check"></i> Copiado!';
    setTimeout(() => btn.innerHTML = originalText, 2000);
}

function checkPayment() {
    fetch('{{ route("donations.check", $donation) }}')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'confirmed') {
                alert('{{ __("messages.donation_confirmed") }}');
                window.location.href = '{{ route("donations.index") }}';
            } else {
                alert('Pagamento ainda não confirmado. Tente novamente em alguns instantes.');
            }
        });
}

setInterval(checkPayment, 10000);
</script>
@endsection