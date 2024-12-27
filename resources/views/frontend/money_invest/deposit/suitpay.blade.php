@extends('frontend::layouts.user')
@section('title')
{{ __('Deposit Now') }}
@endsection
@section('content')
<div class="container padding-top padding-bottom">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="progress-steps-form">
                <div class="card-header">
                    <h5 class="card-title">@lang('Pagar PIX')</h5>
                </div>
                <div class="pt-5 pb-2">
                    <h2 id="counter" class="text-center"></h2>
                </div>
                <div class="card-body">
                    <div class="input-group contact-form-group pb-5 pt-5">
                        <input type="text" id='indicar' value="{{ $paymentCode }}" class="form-control" readonly>
                        <button type="button" class="copytext copy" onclick="copiar()"> <i class="fa fa-copy"></i>
                        </button>
                    </div>
                    <div class="text-center">
                        <h5 class="text-center">@lang('QR Code')</h5>
                        <img src="data:image/jpeg;base64,{{ $paymentCodeBase64 }}" alt="QR Code" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    /* Mover para rodapé */
    function copiar() {
        var copyText = document.getElementById('indicar');
        copyText.select();
        copyText.setSelectionRange(0, 99999);

        document.execCommand('copy');
        copyText.blur();

        var button = $(this);
        var originalContent = button.html();

        button.html('<span>copiado</span>');

        setTimeout(() => {
            button.html(originalContent);
        }, 1500);
    }

    // Confirmar Pagamento 

    // Define o tempo total em segundos (5 minutos)
    let totalTime = 300;

    // Função para iniciar o contador
    function startCounter() {
        let counterElement = document.getElementById('counter');

        let interval = setInterval(function () {
            if (totalTime <= 0) {
                clearInterval(interval);
                counterElement.innerHTML = "Tempo esgotado. Aguarde confirmação manual.";
            } else {
                let minutes = Math.floor(totalTime / 60);
                let seconds = totalTime % 60;
                counterElement.innerHTML =
                    `Aguardando confirmação: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                totalTime--;

                // A cada 5 segundos, verifica o status da transação
                if (totalTime % 5 === 0) {
                    checkTransactionStatus();
                }
            }
        }, 1000);
    }

    // Função para verificar o status da transação
    function checkTransactionStatus() {

        let referenceCode = document.getElementById('indicar').value;
        // Faça uma requisição AJAX ao servidor para verificar o status da transação
        fetch('{{ route('user.deposit.confirmar.zendry') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ referenceCode: referenceCode })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'paid') {
                    window.location.href = '{{ url()->previous() }}';
                }
            })
            .catch(error => console.error('Erro ao verificar transação:', error));
    }
    window.onload = startCounter;
</script>
@endsection
@push('style')
    <style>
        .copytext {
            border-right: 2px solid #a7a7a7;
            border-top: 2px solid #a7a7a7;
            border-bottom: 2px solid #a7a7a7;
            padding: 5px 25px;
        }
    </style>
@endpush