@extends('frontend::layouts.auth')

@section('title')
    {{ __('Register') }}
@endsection
@section('content')
    <main class="page-wrapper">
        <div class="d-lg-flex position-relative">
            <a class="text-nav btn btn-icon bg-light border rounded-circle position-absolute top-0 end-0 p-0 mt-3 me-3 mt-sm-4 me-sm-4"
                href="/" data-bs-toggle="tooltip" data-bs-placement="left" title="Back to home" aria-label="Back to home">
                <i class="ai-home"></i>
            </a>
            <div class="d-flex flex-column align-items-center w-lg-50 h-100 pt-5">
                <div class="h-75 mt-auto">
                    <h1>{{ $data['title'] }}</h1>
                    <p class="pb-3 mb-3 mb-lg-4"> {{ __("Já tem uma conta?") }}
                        <a href="{{ route('login') }}">{{ __('Login') }}</a>
                    </p>
                    @if ($errors->any())
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            @foreach ($errors->all() as $error)
                                <strong>{{ __('You Entered') }} {{ $error }}</strong>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="needs-validation">
                        @csrf
                        <div class="pb-3 mb-3">
                            <div class="position-relative">
                                <label class="box-label" for="name">{{ __('First Name') }}<span
                                        class="required-field">*</span></label>
                                <input class="form-control form-control-lg ps-5" type="text" name="first_name" value="{{ old('first_name') }}"
                                    required />
                            </div>
                        </div>

                        <div class="pb-3 mb-3">
                            <div class="position-relative">
                                <label class="box-label" for="name">{{ __('Last Name') }}<span
                                        class="required-field">*</span></label>
                                <input class="form-control form-control-lg ps-5" type="text" name="last_name" value="{{ old('last_name') }}"
                                    required />
                            </div>
                        </div>
                        <div class="pb-3 mb-3">
                            <div class="position-relative">
                                <label class="box-label" for="email">{{ __('Email Address') }}<span
                                        class="required-field">*</span></label>
                                <input class="form-control form-control-lg ps-5" type="email" name="email" value="{{ old('email') }}"
                                    required />
                            </div>
                        </div>

                        @if (getPageSetting('username_show'))
                            <div class="pb-3 mb-3">
                                <div class="position-relative">
                                    <label class="box-label" for="username">{{ __('User Name') }}<span
                                            class="required-field">*</span></label>
                                    <input class="form-control form-control-lg ps-5" type="text" name="username" value="{{ old('username') }}"
                                        required />
                                </div>
                            </div>
                        @endif
                        @if (getPageSetting('country_show'))
                            <div class="pb-3 mb-3">
                                <div class="position-relative">
                                    <label class="box-label" for="username">{{ __('Select Country') }}<span
                                            class="required-field">*</span></label>

                                    <select name="country" id="countrySelect" class="form-select">

                                        @foreach (getCountries() as $country)
                                            <option @if ($location->country_code == $country['code']) selected @endif
                                                value="{{ $country['name'] . ':' . $country['dial_code'] }}">
                                                {{ $country['name'] }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                        @endif
                        @if (getPageSetting('phone_show'))
                            <div class="pb-3 mb-3">
                                <div class="position-relative">
                                    <label class="box-label" for="username">{{ __('Phone Number') }}<span
                                            class="required-field">*</span></label>
                                    <div class="input-group joint-input"><span class="input-group-text"
                                            id="dial-code">{{ getLocation()->dial_code }}</span>
                                        <input type="text" class="form-control" name="phone"
                                            value="{{ old('phone') }}" aria-label="Username"
                                            aria-describedby="basic-addon1" />
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (getPageSetting('referral_code_show'))
                            <div class="pb-3 mb-3">
                                <div class="position-relative">
                                    <label class="box-label" for="invite">{{ __('Referral Code') }}</label>
                                    <input class="form-control form-control-lg ps-5" type="text" name="invite"
                                        value="{{ request('invite') ?? old('invite') }}" />
                                </div>
                            </div>
                        @endif

                        <div class="pb-3 mb-3">
                            <div class="position-relative">
                                <label class="box-label" for="password">{{ __('Password') }}<span
                                        class="required-field">*</span></label>
                                <div class="password">
                                    <input class="form-control form-control-lg ps-5" type="password" name="password" required />
                                </div>
                            </div>
                        </div>
                        <div class="pb-3 mb-3">
                            <div class="position-relative">
                                <label class="box-label" for="password">{{ __('Confirm Password') }}<span
                                        class="required-field">*</span></label>
                                <div class="password">
                                    <input class="form-control form-control-lg ps-5" type="password" name="password_confirmation" required />
                                </div>
                            </div>
                        </div>
                        <div class="pb-3 mb-3">
                            <div class="position-relative">
                                @if ($googleReCaptcha)
                                    <div class="g-recaptcha" id="feedback-recaptcha"
                                        data-sitekey="{{ json_decode($googleReCaptcha->data, true)['google_recaptcha_key'] }}">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="pb-3 mb-3">
                            <div class="position-relative">
                                <input class="form-check-input check-input" type="checkbox" name="i_agree"
                                    value="yes" id="flexCheckDefault" required />
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{ __('I agree with') }}
                                    <a href="{{ url('/privacy-policy') }}">{{ __('Privacy & Policy') }}</a>
                                    {{ __('and') }}
                                    <a href="{{ url('/terms-and-conditions') }}">{{ __('Terms & Condition') }}</a>
                                </label>
                            </div>
                        </div>


                        <div class="col-xl-12">
                            <button type="submit" class="btn btn-lg btn-primary w-100 mb-4">
                                {{ __('Create Account') }}
                            </button>
                        </div>
                    </form>
                    <div class="singnup-text">
                        <p>{{ __('Already have an account?') }} <a href="{{ route('login') }}">{{ __('Login') }}</a>
                        </p>
                    </div>
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
    <script>
        $('#countrySelect').on('change', function(e) {
            "use strict";
            e.preventDefault();
            var country = $(this).val();
            $('#dial-code').html(country.split(":")[1])
        })
    </script>
@endsection
