<div class="side-nav">
    <div class="side-nav-inside">
        <ul class="side-nav-menu">
            <li class="side-nav-item {{ isActive('user.dashboard') }}">
                <a href="{{route('user.dashboard')}}"><i
                        class="anticon anticon-appstore"></i><span>{{ __('Dashboard') }}</span></a>
            </li>

            <li class="side-nav-item {{ isActive('user.schema*') }}">
                <a href="{{route('user.schema')}}"><i
                        class="anticon anticon-check-square"></i><span>{{ __('All Schema') }}</span></a>
            </li>
            <li class="side-nav-item {{ isActive('user.transacoes*') }}">
                <a href="/user/transacoes"><i
                        class="anticon anticon-copy"></i><span>{{ __('Histórico transações') }}</span></a>
            </li>
            <li class="side-nav-item {{ isActive('user.wallet-exchange') }}">
                <a href="{{ route('user.wallet-exchange') }}"><i
                        class="anticon anticon-transaction"></i><span>{{ __('Wallet Exchange') }}</span></a>
            </li>
            <li class="side-nav-item @if( Route::currentRouteName() != 'user.withdraw.log') {{ isActive('user.withdraw*') }} @endif">
                <a href="{{ route('user.withdraw.view') }}"><i
                        class="anticon anticon-bank"></i><span>{{ __('Withdraw') }}</span></a>
            </li>
            <li class="side-nav-item @if (Route::currentRouteName() != 'user.deposit.log') {{ isActive('user.deposit*') }} @endif">
                <a href="{{ route('user.deposit.amount') }}"><i
                        class="anticon anticon-file-add"></i><span>{{ __('Add Money') }}</span></a>
            </li>
            @if(setting('sign_up_referral','permission'))
                <li class="side-nav-item {{ isActive('user.referral') }}">
                    <a href="{{ route('user.referral') }}"><i
                            class="anticon anticon-usergroup-add"></i><span>{{ __('Referral') }}</span></a>
                </li>
            @endif
            <li class="side-nav-item {{ isActive('user.setting*') }}">
                <a href="{{ route('user.setting.show') }}"><i
                        class="anticon anticon-setting"></i><span>{{ __('Settings') }}</span></a>
            </li>
            <li class="side-nav-item {{ isActive('user.ticket*') }}">
                <a href="{{ route('user.ticket.index') }}"
                ><i class="anticon anticon-tool"></i><span>{{ __('Support Tickets') }}</span></a
                >
            </li>
            <li class="side-nav-item {{ isActive('user.notification*') }}">
                <a href="{{ route('user.notification.all') }}"
                ><i class="anticon anticon-notification"></i><span>{{ __('Notifications') }}</span></a
                >
            </li>
            <li class="side-nav-item">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="site-btn grad-btn w-100">
                        <i class="anticon anticon-logout"></i><span>{{ __('Logout') }}</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
