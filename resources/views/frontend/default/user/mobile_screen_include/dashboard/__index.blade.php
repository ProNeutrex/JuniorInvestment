<div class="row">
    <div class="col-12">
        <div class="side-wallet-box default-wallet mb-0 pb-5">
            <div class="icon"><img src="{{ asset($user->avatar ?? 'global/materials/user.png') }}" alt=""/></div>
            <div class="name">
                <h4>{{ __('Hi') }}, {{ $user->full_name }}</h4>
                <p>{{ $user->rank->ranking_name }} - <span>{{ $user->rank->ranking }}</span></p>
            </div>
            <div class="rank-badge"><img src="{{ asset( $user->rank->icon) }}" alt=""/></div>
        </div>

    </div>

    <div class="col-12">
        <div class="mob-shortcut-btn">
            <a href="{{ route('user.deposit.amount') }}"><i icon-name="download"></i> {{ __('Deposit') }}</a>
            <a href="{{ route('user.schema') }}"><i icon-name="box"></i> {{ __('Investment') }}</a>
            <a href="{{ route('user.withdraw.view') }}"><i icon-name="send"></i> {{ __('Withdraw') }}</a>
        </div>
    </div>


    <div class="col-12">
        <!-- all navigation -->
        @include('frontend::user.mobile_screen_include.dashboard.__navigations')

        <!-- all Statistic -->
        @include('frontend::user.mobile_screen_include.dashboard.__statistic')

        <!-- Recent Transactions -->
        @include('frontend::user.mobile_screen_include.dashboard.__transactions')
    </div>

    <div class="col-12">
        <div class="mobile-ref-url mb-4">
            <div class="all-feature-mobile">
                <div class="title">{{ __('Referral URL') }}</div>
                <div class="mobile-referral-link-form">
                    <input type="text" value="{{ $referral->link }}" id="refLink"/>
                    <button type="submit" onclick="copyRef()">
                        <span id="copy">{{ __('Copy') }}</span>
                    </button>
                </div>
                <p class="referral-joined">{{ $referral->relationships()->count() }} {{ __('peoples are joined by using this URL') }}</p>
            </div>
        </div>
    </div>
</div>
