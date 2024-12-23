@extends('backend.layouts.app')
@section('title')
    Coins
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">Compras Coins</h2>
                        </div>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.coins.editar') }}" class="btn btn-primary btn-sm"><i
                                class="fa-solid fa-plus"></i>Configurar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="col-xl-12 col-md-12">
                <div class="site-card">
                    <div class="site-card-body table-responsive shadow">
                        <div class="site-datatable">
                            <table id="dataTable" class="display data-table">
                                <thead>
                                    <tr>
                                        <th>Dia</th>
                                        <th>Quantia</th>
                                        <th>Valor</th>
                                        <th>Carteira</th>
                                        <th>Status</th>
                                        <th>Usuário</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($coins) > 0)
                                        @foreach ($coins as $ap)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($ap->created_at)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>{{ $ap->quantia }}</td>
                                                <td>{{ $ap->valor }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($ap->carteira, 10) }}<button
                                                        class="btn btn-icon btn-primary"
                                                        onclick="copiarParaAreaTransferencia('{{ $ap->carteira }}')">Copiar</button>
                                                </td>

                                                <script>
                                                    function copiarParaAreaTransferencia(texto) {
                                                        var textarea = document.createElement('textarea');
                                                        textarea.value = texto;
                                                        document.body.appendChild(textarea);
                                                        textarea.select();
                                                        textarea.setSelectionRange(0, 99999);
                                                        document.execCommand('copy');
                                                        document.body.removeChild(textarea);
                                                        alert('Endereço da carteira copiado: ' + texto);
                                                    }
                                                </script>

                                                <td>
                                                    @if ($ap->status == 0)
                                                        <span class="badge bg-warning">Pendente</span>
                                                    @elseif($ap->status == 1)
                                                        <span class="badge bg-success">Enviado</span>
                                                    @elseif($ap->status == 2)
                                                        <span class="badge bg-danger">Negado</span>
                                                    @endif
                                                </td>
                                                <td>{{ $ap->username }}<br>
                                                    <a target="_black" href="{{ route('admin.user.edit', $ap->usuario) }}"
                                                        class="btn btn-primary btn-sm">Ver Perfil</a>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col">
                                                            <form action="{{ route('admin.coins.index.deletar', $ap->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                                        class="fa-solid fa-trash"></i></button>
                                                            </form>
                                                        </div>
                                                        <div class="col">
                                                            @if ($ap->status != 1)
                                                                <form
                                                                    action="{{ route('admin.coins.index.aprovar', $ap->id) }}"
                                                                    method="GET">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-primary btn-sm">Já
                                                                        enviei</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4">Nenhuma compra de coins foi realizada ainda.</td>
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
@endsection
