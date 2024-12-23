@extends('frontend::layouts.user')
@section('title')
    Apostas
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('Add Money') }}</h3>
                    <div class="card-header-links">
                        <a href="{{ route('user.deposit.log') }}" class="card-header-link">{{ __('Deposit History') }}</a>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        @foreach ($apostas as $ap)
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>{{ \Carbon\Carbon::parse($ap->created_at)->format('d/m/Y H:i') }}</strong>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-1">Valor: R${{ $ap->valor }}</p>
                                        <p class="mb-1">Time: {{ mb_substr($ap->time1, 0, 3) }} x
                                            {{ mb_substr($ap->time2, 0, 3) }}</p>
                                        <p class="mb-1">Resultado:
                                            {{ mb_substr($ap->time1, 0, 3) }}
                                            @if ($ap->gols_time1 > 0)
                                                {!! str_repeat('<i class="fa-solid fa-futbol"></i>', $ap->gols_time1) !!}
                                            @else
                                                <i class="fa-solid fa-0"></i>
                                            @endif
                                            x
                                            @if ($ap->gols_time2 > 0)
                                                {!! str_repeat('<i class="fa-solid fa-futbol"></i>', $ap->gols_time2) !!}
                                            @else
                                                <i class="fa-solid fa-0"></i>
                                            @endif
                                            {{ mb_substr($ap->time2, 0, 3) }}
                                        </p>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <a href="" class="btn btn-primary btn-sm"><i
                                                        class="fa-solid fa-pen-to-square"></i></a>
                                            </div>
                                            <div class="col-lg-6">
                                                <form action="" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                                            class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        @endsection
