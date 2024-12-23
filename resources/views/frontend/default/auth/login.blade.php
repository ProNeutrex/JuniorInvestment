@extends('frontend::layouts.auth')
@section('title')
    {{ __('Login') }}
@endsection
@section('content')
    <main class="page-wrapper">
        <div class="d-lg-flex position-relative h-100">
            <a class="text-nav btn btn-icon bg-light border rounded-circle position-absolute top-0 end-0 p-0 mt-3 me-3 mt-sm-4 me-sm-4"
                href="/" data-bs-toggle="tooltip" data-bs-placement="left" title="Back to home" aria-label="Back to home">
                <i class="ai-home"></i>
            </a>
            <div class="d-flex flex-column align-items-center w-lg-50 h-100 pt-5">
                <div class="h-75 mt-auto">
                    <h1>{{ $data['title'] }}</h1>
                    <p class="pb-3 mb-3 mb-lg-4"> {{ __("Don't have an account?") }}
                        <a href="{{ route('register') }}">{{ __('Signup for free') }}</a>
                    </p>
                    <form class="needs-validation" novalidate method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="pb-3 mb-3">
                            <div class="position-relative">
                                <i class="ai-mail fs-lg position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                                <input class="form-control form-control-lg ps-5" type="email" name="email"
                                    placeholder="{{ __('Email Or Username') }}" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="position-relative">
                                <i
                                    class="ai-lock-closed fs-lg position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                                <div class="password-toggle">
                                    <input class="form-control form-control-lg ps-5" name="password" type="password"
                                        placeholder="{{ __('Password') }}" required>
                                    <label class="password-toggle-btn" aria-label="Show/hide password">
                                        <input class="password-toggle-check" type="checkbox"><span
                                            class="password-toggle-indicator"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap align-items-center justify-content-between pb-4">
                            <div class="form-check my-1">
                                <input class="form-check-input" type="checkbox" id="keep-signedin">
                                <label class="form-check-label ms-1" for="keep-signedin"> {{ __('Remember me') }}</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="fs-sm fw-semibold text-decoration-none my-1"
                                    href="{{ route('password.request') }}">{{ __('Forget Password') }}</a>
                            @endif
                        </div>
                        @if ($googleReCaptcha)
                            <div class="g-recaptcha mb-3" id="feedback-recaptcha"
                                data-sitekey="{{ json_decode($googleReCaptcha->data, true)['google_recaptcha_key'] }}">
                            </div>
                        @endif
                        <button type="submit" class="btn btn-lg btn-primary w-100 mb-4"
                            type="submit">{{ __('Account Login') }}</button>
                    </form>
                </div>
            </div>
            <div class="w-50 bg-size-cover bg-repeat-0 bg-position-center"
                style="background-image: url({{ asset('frontend/theme_base/money_invest/materials/banners/auth-banner.jpg') }});">
            </div>
        </div>
    </main>
@endsection
@section('script')
    @if ($googleReCaptcha)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection