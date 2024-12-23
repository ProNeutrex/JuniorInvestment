@php
    $investors = \App\Models\Invest::with('schema')
        ->latest()
        ->take(6)
        ->get();
    $withdraws = \App\Models\Transaction::where('type', \App\Enums\TxnType::Withdraw)
        ->take(6)
        ->latest()
        ->get();
@endphp
<section class="container py-5 mt-5 mb-lg-3 mb-xl-4 mb-xxl-5">
    <div class="text-center pb-3 pt-lg-2 pt-xl-4 my-1 my-sm-3 my-lg-4">
        <h1 class="display-2">{{ $data['title_small'] }}</h1>
        <p class="fs-lg mb-0">{{ $data['title_big'] }}</p>
    </div>
    <div class="row pb-4 overflow-auto">
        <div class="col-xl-6 col-lg-12 mb-3">
            <div class="card" data-aos="fade-right" data-aos-duration="2000">
                <div class="card-header">
                    <h3 class="title">{{ __('Recent Investors') }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Investor') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Profit') }}</th>
                                    <th>{{ __('Investment Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($investors as $investor)
                                    @php
                                        $calculateInterest = ($investor->interest * $investor->invest_amount) / 100;
                                        $interest = $investor->interest_type != 'percentage' ? $investor->interest : $calculateInterest;
                                    @endphp

                                    <tr>
                                        <td>{{ $investor->user->full_name }}</td>
                                        <td>{{ $investor->created_at }}</td>
                                        <td>
                                            @if ($investor->user->status)
                                                <span class="site-badge badge-success">{{ __('Active') }}</span>
                                            @else
                                                <span class="site-badge badge-pending">{{ __('DeActive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="net in">+{{ $investor->already_return_profit * $interest }}
                                                {{ $currency }}</span>
                                        </td>
                                        <td>
                                            <span class="total">{{ $investor->invest_amount }}
                                                {{ $currency }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12 mb-3">
            <div class="card" data-aos="fade-left" data-aos-duration="2000">
                <div class="card-header">
                    <h3 class="title">{{ __('Recent Withdraws') }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($withdraws as $withdraw)
                                    <tr>
                                        <td>{{ $withdraw->description }}</td>
                                        <td>{{ $withdraw->created_at }}</td>
                                        <td>
                                            @if ($withdraw->status == \App\Enums\TxnStatus::Success)
                                                <span class="site-badge badge-success">{{ __('Success') }}</span>
                                            @elseif($withdraw->status == \App\Enums\TxnStatus::Failed)
                                                <span class="site-badge badge-failed">{{ __('Cancelled') }}</span>
                                            @else
                                                <span class="site-badge badge-pending">{{ __('Pending') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($withdraw->status == \App\Enums\TxnStatus::Success)
                                                <span class="net in">+{{ $withdraw->final_amount }}
                                                    {{ $currency }}</span>
                                            @elseif($withdraw->status == \App\Enums\TxnStatus::Failed)
                                                <span class="net out">-{{ $withdraw->final_amount }}
                                                    {{ $currency }}</span>
                                            @endif
                                            <span class="total">{{ $withdraw->final_amount }}
                                                {{ $currency }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
