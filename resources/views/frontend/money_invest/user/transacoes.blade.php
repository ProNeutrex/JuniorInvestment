@extends('frontend::layouts.user')
@section('title')
    Transações
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">Transações Histórico</h3>
                </div>
                <div class="site-tab-bars">
                    <ul>
                        <li class="{{ isActive('user.deposit.log') ? 'bg-warning' : 'bg-info' }}">
                            <a href="/user/deposit/log"><i icon-name="settings-2"></i>{{ __('Depositos') }}</a>
                        </li>
                        <li class="{{ isActive('user.invest-logs') ? 'bg-warning' : 'bg-info' }}">
                            <a href="/user/invest-logs"><i icon-name="book-open"></i>{{ __('Rendimentos') }}</a>
                        </li>
                        <li class="{{ isActive('user.withdraw.log') ? 'bg-warning' : 'bg-info' }}">
                            <a href="/user/withdraw/log"><i icon-name="box"></i>{{ __('Saques') }}</a>
                        </li>
                        <li class="{{ isActive('user.send-money.log') ? 'bg-warning' : 'bg-info' }}">
                            <a href="/user/send-money/log"><i icon-name="calendar"></i>{{ __('Transferências') }}</a>
                        </li>
                    </ul>                    
                </div>
            </div>

            <div class="site-card pt-5">
                <div class="site-card-body table-responsive">
                    <div class="site-datatable">
                        <table class="display data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Transactions ID') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Fee') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Gateway') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <div class="table-description">
                                                <div class="icon">
                                                    <i
                                                        icon-name="@switch($transaction->type->value)
                                                @case('send_money')arrow-right
                                                @break
                                                @case('receive_money')arrow-left
                                                @break
                                                @case('deposit')arrow-down-left
                                                @break
                                                @case('investment')arrow-left-right
                                                @break
                                                @case('withdraw')arrow-up-left
                                                @break
                                                @default()backpack
                                             @endswitch">
                                                    </i>
                                                </div>


                                                <div class="description">
                                                    <strong>{{ $transaction->description }} @if (!in_array($transaction->approval_cause, ['none', '']))
                                                            <span class="optional-msg" data-bs-toggle="tooltip"
                                                                title=""
                                                                data-bs-original-title="{{ $transaction->approval_cause }}"><i
                                                                    icon-name="mail"></i></span>
                                                        @endif
                                                    </strong>
                                                    <div class="date">{{ $transaction->created_at }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><strong>{{ $transaction->tnx }}</strong></td>
                                        <td>
                                            <div class="site-badge primary-bg">
                                                {{ ucfirst(str_replace('_', ' ', $transaction->type->value)) }}</div>
                                        </td>

                                        <td><strong
                                                class="{{ txn_type($transaction->type->value, ['green-color', 'red-color']) }}">{{ txn_type($transaction->type->value, ['+', '-']) . $transaction->amount . ' ' . $currency }}</strong>
                                        </td>
                                        <td><strong>{{ $transaction->charge . ' ' . $currency }}</strong></td>
                                        <td>


                                            @if ($transaction->status->value == \App\Enums\TxnStatus::Pending->value)
                                                <div class="site-badge warnning">{{ __('Pending') }}</div>
                                            @elseif($transaction->status->value == \App\Enums\TxnStatus::Success->value)
                                                <div class="site-badge success">{{ __('Success') }}</div>
                                            @elseif($transaction->status->value == \App\Enums\TxnStatus::Failed->value)
                                                <div class="site-badge primary-bg">{{ __('canceled') }}</div>
                                            @endif
                                        </td>
                                        <td><strong>{{ $transaction->method }}</strong></td>
                                    </tr>
                                @endforeach


                                @if ($recentTransactions->isEmpty())
                                    <tr class="centered">
                                        <td colspan="7">{{ __('No Data Found') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endsection
