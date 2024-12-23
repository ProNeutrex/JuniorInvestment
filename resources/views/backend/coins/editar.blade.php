@extends('backend.layouts.app')
@section('title')
    Configurar vendas de Coins
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-auto">
                        <a href="{{route('admin.coins.index')}}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Retornar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="col-xl-12 col-md-12">
                <div class="scard-body">
                    <form method="post" action="{{ route('admin.coins.atualizar')}}">
                        @csrf
                        <div class="mb-3">
                            <label for="valor" class="form-label">Status</label>
                            <select class="form-select" aria-label="Default select example" name="status">
                                <option value="1" @if($ap->status == 1) selected @endif>Ativo</option>
                                <option value="0" @if($ap->status == 0) selected @endif>Inativo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Valor por Coin</label>
                            <input type="text" class="form-control" id="valor" name="valor" value="{{$ap->valor}}">
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Nome da Coin</label>
                            <input type="text" class="form-control" id="quantia" name="nome" value="{{$ap->nome}}">
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-sm">Editar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
