<head>
    <html lang="pt-BR" data-bs-theme="light">
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="@yield('meta_keywords', setting('site_title', 'global'))">
    <meta name="description" content="@yield('meta_description', setting('site_title', 'global'))">
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="shortcut icon" href="{{ asset(setting('site_favicon', 'global')) }}" type="image/x-icon" />
    <link rel="icon" href="{{ asset(setting('site_favicon', 'global')) }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('global/css/fontawesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/vendor/bootstrap.min.css') }}" />
    <script src="{{ asset('frontend/theme_base/padraods/js/theme-switcher.js') }}"></script>

    @stack('style')
    @notifyCss
    @yield('style')
    <link rel="stylesheet" href="{{ asset('frontend/theme_base/padraods/css/theme.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/theme_base/padraods/icons/around-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/theme_base/padraods/css/custom.css') }}" />
    <style>
        {{ \App\Models\CustomCss::first()->css }}
    </style>
    <title>{{ setting('site_title', 'global') }} - @yield('title')</title>
    <style>
        .page-loading {
          position: fixed;
          top: 0;
          right: 0;
          bottom: 0;
          left: 0;
          width: 100%;
          height: 100%;
          -webkit-transition: all .4s .2s ease-in-out;
          transition: all .4s .2s ease-in-out;
          background-color: #fff;
          opacity: 0;
          visibility: hidden;
          z-index: 9999;
        }
        [data-bs-theme="dark"] .page-loading {
          background-color: #121519;
        }
        .page-loading.active {
          opacity: 1;
          visibility: visible;
        }
        .page-loading-inner {
          position: absolute;
          top: 50%;
          left: 0;
          width: 100%;
          text-align: center;
          -webkit-transform: translateY(-50%);
          transform: translateY(-50%);
          -webkit-transition: opacity .2s ease-in-out;
          transition: opacity .2s ease-in-out;
          opacity: 0;
        }
        .page-loading.active > .page-loading-inner {
          opacity: 1;
        }
        .page-loading-inner > span {
          display: block;
          font-family: "Inter", sans-serif;
          font-size: 1rem;
          font-weight: normal;
          color: #6f788b;
        }
        [data-bs-theme="dark"] .page-loading-inner > span {
          color: #fff;
          opacity: .6;
        }
        .page-spinner {
          display: inline-block;
          width: 2.75rem;
          height: 2.75rem;
          margin-bottom: .75rem;
          vertical-align: text-bottom;
          background-color: #d7dde2; 
          border-radius: 50%;
          opacity: 0;
          -webkit-animation: spinner .75s linear infinite;
          animation: spinner .75s linear infinite;
        }
        [data-bs-theme="dark"] .page-spinner {
          background-color: rgba(255,255,255,.25);
        }
        @-webkit-keyframes spinner {
          0% {
            -webkit-transform: scale(0);
            transform: scale(0);
          }
          50% {
            opacity: 1;
            -webkit-transform: none;
            transform: none;
          }
        }
        @keyframes spinner {
          0% {
            -webkit-transform: scale(0);
            transform: scale(0);
          }
          50% {
            opacity: 1;
            -webkit-transform: none;
            transform: none;
          }
        }
      </style>
</head>
