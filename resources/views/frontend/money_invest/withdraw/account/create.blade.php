@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Account Create') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('Add New Withdraw Account') }}</h3>
                    <div class="card-header-links">
                        <a href="{{ route('user.withdraw.account.index') }}"
                           class="card-header-link">{{ __('Withdraw Account') }}</a>
                    </div>
                </div>
                <div class="site-card-body">
                    <div class="progress-steps-form">
                        <form action="{{ route('user.withdraw.account.store') }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row selectMethodRow">
                                <div class="col-lg-12 selectMethodCol">
                                    <label for="exampleFormControlInput1"
                                           class="form-label">{{ __('Choice Method:') }}</label>
                                    <div class="input-group">
                                        <select name="withdraw_method_id" id="selectMethod"
                                                class="site-nice-select">
                                            <option selected>{{ __('Select Method') }}</option>
                                            @foreach($withdrawMethods as $raw)
                                                <option value="{{ $raw->id }}">{{ $raw->name }}
                                                    ({{ ucwords($raw->type) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="additionalFields"></div> <!-- Container para campos adicionais -->
                            <div class="buttons">
                                <button type="submit" class="site-btn blue-btn">
                                    {{ __('Add New Withdraw Account') }}<i
                                        class="anticon anticon-double-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#selectMethod").on('change', function (e) {
            "use strict"
            e.preventDefault();

            // Limpar campos adicionais
            $('.additionalFields').empty();

            var id = $(this).val();

            // Verificar se o método selecionado é igual a 3
            if (id == 3 || id == 5) {
                // Adicionar campo Chave PIX
                $('.additionalFields').append(`
                    <div class="col-lg-12">
                        <label for="pixKey" class="form-label">{{ __('Chave PIX:') }}</label>
                        <input type="text" name="chave" id="pixKey" class="form-control" required>
                    </div>
                `);

                // Adicionar select com opções
                $('.additionalFields').append(`
                    <div class="col-lg-12">
                        <label for="keyType" class="form-label">{{ __('Tipo de Chave:') }}</label>
                        <div class="input-group">
                            <select name="tipo" id="keyType" class="site-nice-select">
                                <option value="CPF">{{ __('CPF') }}</option>
                                <option value="E-mail">{{ __('E-mail') }}</option>
                                <option value="Telefone">{{ __('Celular') }}</option>
                                <option value="aleatoria">{{ __('Aleatória') }}</option>
                            </select>
                        </div>
                    </div>
                `);
            }
        });
    </script>
@endsection
