<div class="panel-header">
    <div class="logo">
        <a href="/user/dashboard">
            <img class="logo-unfold" src="{{ asset(setting('site_logo','global')) }}" alt="Logo"/>
            <img class="logo-fold" src="{{ asset(setting('site_logo','global')) }}" alt="Logo"/>
        </a>
    </div>
    <div class="nav-wrap">
        <div class="nav-left">
            <button class="sidebar-toggle">
                <i class="anticon anticon-arrow-left"></i>
            </button>
            <div class="mob-logo">
                <a href="/user/dashboard">
                    <img src="{{ asset(setting('site_logo','global')) }}" alt="Site Name"/>
                </a>
            </div>
        </div>
        <div class="nav-right">
            <div class="single-nav-right">
                <div class="single-right">
                    <div class="color-switcher">
                        <i icon-name="moon" class="dark-icon" data-mode="dark"></i>
                        <i icon-name="sun" class="light-icon" data-mode="light"></i>
                    </div>
                </div>
                {{--  Push Notificataion--}}
                @auth
                    @php
                        $userId = auth()->id();
                        $notifications = App\Models\Notification::where('for','user')->where('user_id', $userId)->latest()->take(10)->get();
                        $totalUnread = App\Models\Notification::where('for','user')->where('user_id', $userId)->where('read', 0)->count();
                        $totalCount = App\Models\Notification::where('for','user')->where('user_id', $userId)->get()->count();
                    @endphp
                    <div class="single-nav-right user-notifications{{ $userId }}">
                        @include('global.__notification_data',['notifications'=>$notifications,'totalUnread'=>$totalUnread,'totalCount'=>$totalCount])
                    </div>
                @endauth
                {{-- End Push Notificataion--}}

                <div class="single-right">
                    <select name="language" id="" class="site-nice-select"
                            onchange="window.location.href=this.options[this.selectedIndex].value;">
                        @foreach(\App\Models\Language::where('status',true)->get() as $lang)
                            <option
                                value="{{ route('language-update',['name'=> $lang->locale]) }}" @selected(App::currentLocale() == $lang->locale )>{{$lang->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="single-right">
                    <button
                        type="button"
                        class="item"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="anticon anticon-user"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="{{ route('user.setting.show') }}" class="dropdown-item" type="button"><i
                                    class="anticon anticon-setting"></i>{{ __('Settings') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('user.change.password') }}" class="dropdown-item" type="button">
                                <i class="anticon anticon-lock"></i>{{ __('Change Password') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.ticket.index') }}" class="dropdown-item" type="button">
                                <i class="anticon anticon-customer-service"></i>{{ __('Support Tickets') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.transacoes') }}" class="dropdown-item" type="button">
                                <i class="fa-solid fa-clock-rotate-left"></i>{{ __('Histórico transações') }}
                            </a>
                        </li>
                        <li class="logout">
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <a href="{{ url('logout') }}" class="dropdown-item"
                                   onclick="event.preventDefault(); localStorage.clear();  $('#logout-form').submit();"><i
                                        class="anticon anticon-logout"></i>{{ __('Logout') }}</a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        // Color Switcher
        $(".color-switcher").on('click', function () {
            "use strict"
            $("body").toggleClass("dark-theme");
            var url = '{{ route("mode-theme") }}';
            $.get(url)
        });
    </script>
@endpush