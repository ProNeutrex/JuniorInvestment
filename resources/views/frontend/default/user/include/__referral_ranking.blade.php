<div class="side-wallet-box default-wallet mb-0">
    <div class="user-balance-card bg-secondary">
        <div class="wallet-name">
            <div class="name">{{ __('Account Balance') }}</div>
            <div class="default">{{ __('Wallet') }}</div>
        </div>
        <div class="wallet-info">
            <div class="wallet-id"><i icon-name="wallet"></i>{{ __('Main Wallet') }}</div>
            <div class="balance">{{ setting('currency_symbol','global').$user->balance }}</div>
        </div>
        <div class="wallet-info">
            <div class="wallet-id"><i icon-name="landmark"></i>{{ __('Profit Wallet') }}</div>
            <div class="balance">{{ setting('currency_symbol','global').number_format($user->profit_balance,2) }}</div>
        </div>
    </div>
    <div class="actions">
        <a href="{{ route('user.deposit.amount') }}" class="user-sidebar-btn"><i
                class="anticon anticon-file-add"></i>{{ __('Deposit') }}</a>
        <a href="{{ route('user.schema') }}" class="user-sidebar-btn red-btn"><i
                class="anticon anticon-export"></i>{{ __('Invest Now') }}</a>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
        <div class="user-ranking" @if($user->avatar) style="background: url({{ asset($user->avatar) }});" @endif>
            <h4>{{ $user->rank->ranking }}</h4>
            <p>{{ $user->rank->ranking_name }}</p>
            <div class="rank" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $user->rank->description }}">
                <img src="{{ asset( $user->rank->icon) }}" alt="">
            </div>
        </div>
    </div>
    @if(setting('sign_up_referral','permission'))
        <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('Referral URL') }}</h3>
                </div>
                <div class="site-card-body">
                    <div class="referral-link">
                        <div class="referral-link-form">
                            <input type="text" value="{{ $referral->link }}" id="refLink"/>
                            <button type="submit" onclick="copyRef()">
                                <i class="anticon anticon-copy"></i>
                                <span id="copy">{{ __('Copy') }}</span>
                            </button>
                        </div>
                        <p class="referral-joined">
                            {{ $referral->relationships()->count() }} {{ __('peoples are joined by using this URL') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
