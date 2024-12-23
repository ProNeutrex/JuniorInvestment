@extends('frontend::layouts.user')
@section('title')
    {{ __('Deposit Now') }}
@endsection
@section('content')
<div class="container padding-top padding-bottom">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="custom--card">
                <div class="card-header">
                    <h5 class="card-title">@lang('Pagar PIX')</h5>
                </div>
                <div class="card-body">
                    <div class="input-group contact-form-group pb-5 pt-5">
                        <input type="text" id='indicar' value="{{ $paymentCode }}" class="form-control" readonly>
                        <button type="button" class="bor copytext copy" onclick="copiar()"> <i class="fa fa-copy"></i>
                        </button>
                    </div>
                    <div>
                        <h5 class="text-center">@lang('QR Code')</h5>
                        <img src="data:image/jpeg;base64,{{ $paymentCodeBase64 }}" alt="QR Code" class="img-fluid">
                    </div>
                    <div class="input-group contact-form-group pt-5">
                        <a href="/user/deposit/history" class="btn w-100">@lang('Meus depósitos')</a>
                    </div>
                    <p>Seu depósito deve ser confirmado em até 5 minutos. Se não ocorre automaticamente, aguarde
                        confirmação manual em até 24 horas úteis.</p>
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
        var copyText = document.getElementById("indicar");
        navigator.permissions.query({
            name: 'clipboard-write'
        }).then(result => {
            if (result.state == 'granted' || result.state == 'prompt') {
                navigator.clipboard.writeText(copyText.value)
                    .then(() => {
                        alert("COPIE e cole Copiado com sucesso: " + copyText.value);
                    })
                    .catch(err => {
                        console.error('Erro ao copiar o texto', err);
                    });
            } else {
                alert('Permissão para a área de transferência negada.');
            }
        });
    }
</script>
@endsection