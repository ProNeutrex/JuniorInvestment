@extends('frontend::layouts.user')
@section('title')
    Coin
@endsection
@section('content')

    @if ($conf->status == 1)
        <div class="row">
            <div class="col-xl-12">
                <div class="site-card">
                    <div class="site-card-header">
                        <h3 class="title">{{ $conf->nome }}</h3>
                        <div class="card-header-links">
                            <a href="/user/deposit" class="card-header-link">Depositar fundos</a>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('user.coin.buy') }}">
                        @csrf
                        <div class="progress-steps-form">
                            <div class="transaction-list table-responsive">
                                <table class="table preview-table">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="historico">
                                                    <div class="saldo">{{ $conf->valor . $currency }}</div>
                                                    <div class="data">Por cada {{ $conf->nome }}:</div>
                                                    <hr>
                                                    <div class="site-card-body">
                                                        <ul>
                                                            <li class="">
                                                                <div class=" mb-0 mb-3">
                                                                    <label>Quantidade</label>
                                                                    <input type="text" name="coins"
                                                                        class="form-control" min="50"
                                                                        placeholder="Quantas {{ $conf->nome }}, quer comprar?"
                                                                        required>
                                                                </div>
                                                                <div class=" mb-3">
                                                                    <label>Wallet BEP20</label>
                                                                    <input type="text" name="carteira"
                                                                        class="form-control" min="50"
                                                                        placeholder="Endereço Wallet BEP20"
                                                                        required>
                                                                        <small>Endereço da sua carteira BEP20</small>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="historico">
                                                    <div class="data">Confirmar compra</div>
                                                    <hr>
                                                    <div class="input-group mb-0">
                                                        <select class="site-nice-select" aria-label="Default select example"
                                                            name="wallet" required id="selectWallet">
                                                            <option value="main">Carteira principal
                                                                {{ $user->balance . $currency }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="site-card-body">
                                                        <button type="submit"
                                                            class="btn btn-outline-warning shadow">Comprar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="site-card">
                    <div class="site-card-body">
                        <div class="site-table">
                            <div class="site-card-header">
                                <h3 class="title">Histórico de compras</h3>
                            </div>
                            <div class="site-card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">Data</th>
                                                <th scope="col">Quantidade</th>
                                                <th scope="col">Valor</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($coins->count() > 0)
                                                @foreach ($coins as $coin)
                                                    <tr>
                                                        <td><strong
                                                                class="red-color">{{ $coin->created_at->format('d/m/Y') }}</strong>
                                                        </td>
                                                        <td><strong class="red-color">{{ $coin->quantia }}</strong></td>
                                                        <td><strong
                                                                class="red-color">{{ $coin->valor . $currency }}</strong>
                                                        </td>
                                                        <td>
                                                            @if ($coin->status == 0)
                                                                <span class="badge bg-warning">Pendente</span>
                                                            @elseif($coin->status == 1)
                                                                <span class="badge bg-success">Enviado</span>
                                                            @elseif($coin->status == 2)
                                                                <span class="badge bg-danger">Negado</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4">Nenhuma compra realizada</td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="main-content">
            <div class="site-card">
                <div class="alert alert-info">
                    <h3 class="text-center">Aviso</h3>
                    <p class="text-center">As compras de coins estão desativadas no momento.</p>
                </div>
            </div>
        </div>
    @endif
@endsection
