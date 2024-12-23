<div class="side-wallet-box default-wallet mb-0 pb-5">
    <div class="user-balance-card bg-secondary">
        <div class="wallet-name">
            <div class="name">{{ __('Account Balance') }}</div>
            <div class="default">{{ __('Wallet') }}</div>
        </div>
        <div class="wallet-info">
            <div class="wallet-id"><i icon-name="landmark"></i>{{ __('Profit Wallet') }}</div>
            <div class="balance">{{ setting('currency_symbol','global').number_format($user->profit_balance,2) }}</div>
        </div>
    </div>
    <div class="actions">
        <a href="{{ route('user.withdraw.view') }}" class="user-sidebar-btn"><i
                class="anticon anticon-file-add"></i>{{ __('Withdraw') }}</a>
        <a href="{{ route('user.schema') }}" class="user-sidebar-btn red-btn"><i
                class="anticon anticon-export"></i>{{ __('Invest Now') }}</a>
    </div>
</div>
<div class="row">
    @if(setting('sign_up_referral','permission'))
        <div class="lg-12">
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
