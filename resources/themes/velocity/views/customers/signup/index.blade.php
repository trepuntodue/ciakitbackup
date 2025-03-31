@extends('shop::layouts.master')

@section('page_title')
  {{ __('shop::app.customer.signup-form.page-title') }}
@endsection

@section('content-wrapper')
  <div class="auth-content form-container">
    <div class="container">
      <div class="col-lg-10 col-md-12 offset-lg-1">
        <div class="heading">
          <h2 class="fs24 fw6">
            {{ __('velocity::app.customer.signup-form.user-registration') }}
          </h2>

          <a href="{{ route('customer.session.index') }}" class="btn-new-customer">
            <button type="button" class="theme-btn light">
              {{ __('velocity::app.customer.signup-form.login') }}
            </button>
          </a>
        </div>

        <div class="body col-12">
          <h3 class="fw6">
            {{ __('velocity::app.customer.signup-form.become-user') }}
          </h3>

          <p class="fs16">
            {{ __('velocity::app.customer.signup-form.form-sginup-text') }}
          </p>

          {!! view_render_event('bagisto.shop.customers.signup.before') !!}

          <form method="post" action="{{ route('customer.register.create') }}" @submit.prevent="onSubmit">

            {{ csrf_field() }}

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.before') !!}

            <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
              <label for="first_name" class="required label-style">
                {{ __('shop::app.customer.signup-form.firstname') }}
              </label>

              <input type="text" class="form-style" name="first_name" v-validate="'required'"
                value="{{ old('first_name') }}"
                data-vv-as="&quot;{{ __('shop::app.customer.signup-form.firstname') }}&quot;" />

              <span class="control-error" v-if="errors.has('first_name')" v-text="errors.first('first_name')"></span>
            </div>

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.firstname.after') !!}

            <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
              <label for="last_name" class="required label-style">
                {{ __('shop::app.customer.signup-form.lastname') }}
              </label>

              <input type="text" class="form-style" name="last_name" v-validate="'required'"
                value="{{ old('last_name') }}"
                data-vv-as="&quot;{{ __('shop::app.customer.signup-form.lastname') }}&quot;" />

              <span class="control-error" v-if="errors.has('last_name')" v-text="errors.first('last_name')"></span>
            </div>

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.lastname.after') !!}

            <div class="control-group" :class="[errors.has('date_of_birth') ? 'has-error' : '']">
              <label for="date_of_birth" class="required label-style">
                {{ __('shop::app.customer.account.profile.dob') }}
              </label>

              <input type="date" class="form-style datepicker" name="date_of_birth" id="date_of_birth"
                v-validate="'required'" value="{{ old('date_of_birth') }}"
                data-vv-as="&quot;{{ __('shop::app.customer.signup-form.date_of_birth') }}&quot;"
                onkeydown="return false" />

              <span class="control-error" v-if="errors.has('date_of_birth')"
                v-text="errors.first('date_of_birth')"></span>
            </div>

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.date_of_birth.after') !!} <!-- PWS#finale -->

            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
              <label for="email" class="required label-style">
                {{ __('shop::app.customer.signup-form.email') }}
              </label>

              <input type="email" class="form-style" name="email" v-validate="'required|email'"
                value="{{ old('email') }}" data-vv-as="&quot;{{ __('shop::app.customer.signup-form.email') }}&quot;" />

              <span class="control-error" v-if="errors.has('email')" v-text="errors.first('email')"></span>
            </div>

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.email.after') !!}

            <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
              <label for="password" class="required label-style">
                {{ __('shop::app.customer.signup-form.password') }}
              </label>

              <input type="password" class="form-style" name="password" v-validate="'required|min:6'" ref="password"
                value="{{ old('password') }}"
                data-vv-as="&quot;{{ __('shop::app.customer.signup-form.password') }}&quot;" />

              <span class="control-error" v-if="errors.has('password')" v-text="errors.first('password')"></span>
            </div>

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.password.after') !!}

            <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
              <label for="password_confirmation" class="required label-style">
                {{ __('shop::app.customer.signup-form.confirm_pass') }}
              </label>

              <input type="password" class="form-style" name="password_confirmation"
                v-validate="'required|min:6|confirmed:password'"
                data-vv-as="&quot;{{ __('shop::app.customer.signup-form.confirm_pass') }}&quot;" />

              <span class="control-error" v-if="errors.has('password_confirmation')"
                v-text="errors.first('password_confirmation')"></span>
            </div>

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.password_confirmation.after') !!}

            <div class="control-group">

              {!! Captcha::render() !!}

            </div>

            @if (core()->getConfigData('customer.settings.newsletter.subscription'))
              <div class="control-group">
                <input type="checkbox" id="checkbox2" name="is_subscribed">
                <span>{{ __('shop::app.customer.signup-form.subscribe-to-newsletter') }}</span>
              </div>
            @endif

            <div class="control-group">
              <label for="privacy" class="font-medium text-gray-700">Privacy Policy</label>
              <input type="checkbox" id="privacy_checkbox" name="privacy_checkbox" required>
              {{-- <p class="text-gray-500">You need to read and accept our Privacy Policy before register. <a
                  class="font-bold underline" href="/page/privacy-policy/">privacy policy</a></p> --}}
              <p class="text-gray-500">Devi leggere ed accettare la nostra privacy policy prima di registrarti. <a
                  class="font-bold underline" href="/page/privacy-policy/">privacy policy</a></p>
            </div>

            {!! view_render_event('bagisto.shop.customers.signup_form_controls.after') !!}

            <button class="theme-btn" type="submit">
              {{ __('shop::app.customer.signup-form.title') }}
            </button>
          </form>

          {!! view_render_event('bagisto.shop.customers.signup.after') !!}
        </div>
      </div>
    </div>
  </div>
@endsection

{{-- @push('css')
  <style>
    .input-container input[type="date"] {
              border: none;
              box-sizing: border-box;
              outline: 0;
              padding: .75rem;
              position: relative;
              width: 100%;
          }

          input[type="date"]::-webkit-calendar-picker-indicator {
              background: transparent;
              bottom: 0;
              color: transparent;
              cursor: pointer;
              height: auto;
              left: 0;
              position: absolute;
              right: 0;
              top: 0;
              width: auto;
          }
  </style>
@endpush --}}

@push('scripts')
  <script>
    $(function() {
      $(":input[name=first_name]").focus();
    });

    $(document).ready(function() {
      date_of_birth.max = new Date().toLocaleDateString('fr-ca'); // PWS#finale
    });

    document.querySelector('form').addEventListener('submit', function(e) {
      var privacyCheckbox = document.querySelector('#privacy');

      if (!privacyCheckbox.checked) {
        e.preventDefault();
        alert('You must accept the Privacy Policy before submitting the form.');
      }
    });
  </script>

  {!! Captcha::renderJS() !!}
@endpush
