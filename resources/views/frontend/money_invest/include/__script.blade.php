<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('global/js/waypoints.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('global/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('global/js/lucide.min.js') }}"></script>
<script src="{{ asset('frontend/js/magnific-popup.min.js') }}"></script>
<script src="{{ asset('frontend/js/aos.js') }}"></script>
<script src="{{ asset('global/js/datatables.min.js') }}" type="text/javascript" charset="utf8"></script>
<script src="{{ asset('global/js/simple-notify.min.js') }}"></script>
<script src="{{ asset('frontend/theme_base/money_invest/js/main.js?var=5') }}"></script>
<script src="{{ asset('frontend/js/cookie.js') }}"></script>
<script src="{{ asset('global/js/custom.js?var=5') }}"></script>
@include('global.__t_notify')
@if (auth()->check())
    <script src="{{ asset('global/js/pusher.min.js') }}"></script>
    @include('global.__notification_script', ['for' => 'user', 'userId' => auth()->user()->id])
@endif
@if (setting('site_animation', 'permission'))
    <script>
        (function($) {
            'use strict';

            AOS.init();
        })(jQuery);
    </script>
@endif
@if (setting('back_to_top', 'permission'))
    <a class="btn-scroll-top" href="#top" data-scroll aria-label="Scroll back to top">
        <svg viewBox="0 0 40 40" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <circle cx="20" cy="20" r="19" fill="none" stroke="currentColor" stroke-width="1.5"
                stroke-miterlimit="10"></circle>
        </svg>
        <i class="ai-arrow-up"></i>
    </a>
@endif

@notifyJs

@yield('script')
@stack('script')

@php
    $googleAnalytics = plugin_active('Google Analytics');
    $tawkChat = plugin_active('Tawk Chat');
    $fb = plugin_active('Facebook Messenger');
@endphp

@if ($googleAnalytics)
    @include('frontend.plugin.google_analytics', [
        'GoogleAnalyticsId' => json_decode($googleAnalytics?->data, true)['app_id'],
    ])
@endif
@if ($tawkChat)
    @include('frontend.plugin.tawk', ['data' => json_decode($tawkChat->data, true)])
@endif
@if ($fb)
    @include('frontend.plugin.fb', ['data' => json_decode($fb->data, true)])
@endif