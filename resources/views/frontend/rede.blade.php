@extends('frontend::pages.index')
<!-- titulo -->
@section('title')
    Rede
@endsection
@section('page-content')
    <section class="section-style-2 light-blue-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Dados da Tabela :</h2>
                    <table class="table bg-dark text-white">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Ordem</th>
                                <th>Recompensa</th>
                                <th>Data de Criação</th>
                                <th> Data de Atualização</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->the_order }}</td>
                                    <td>{{ $item->bounty }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
