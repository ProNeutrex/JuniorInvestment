@extends('backend.layouts.app')
@section('title')
    Criar uma nova aposta
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">Apostas</h2>
                        </div>
                    </div>
                    <div class="col-auto">
                        <a href="{{route('admin.apostas.index')}}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Retornar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="col-xl-12 col-md-12">
                <div class="scard-body">
                    <form method="post" action="{{ route('admin.apostas.cadastrar') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="valor" class="form-label">Lucro %</label>
                            <input type="number" class="form-control" id="valor" name="valor" placeholder="EX: 1">
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Breve descrição da partida"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="time1" class="form-label">Qual time?</label>
                                    <input type="text" class="form-control" id="time1" name="time1"
                                        placeholder="Time 1">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="time2" class="form-label">Contra quem?</label>
                                    <input type="text" class="form-control" id="time2" name="time2"
                                        placeholder="Time 2">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-sm">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
