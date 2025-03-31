@php
    $currentCustomer = auth()->guard('customer')->user();
@endphp

@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.release.create.page-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head mb-15">
        <span class="account-heading">{{ __('shop::app.customer.account.release.create.title') }}</span>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.release.create.before') !!}

        <form method="post" action="{{ route('customer.release.store') }}" @submit.prevent="onSubmit">
            <div class="account-table-content mb-2">
                @csrf

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.before') !!}

                <div class="control-group" :class="[errors.has('company_name') ? 'has-error' : '']">
                    <label for="company_name">{{ __('shop::app.customer.account.release.create.company_name') }}</label>

                    <input
                        class="control"
                        type="text"
                        name="company_name"
                        value="{{ old('company_name') }}"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.company_name') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('company_name')"
                        v-if="errors.has('company_name')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.company_name.after') !!}

                <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                    <label for="first_name" class="mandatory">{{ __('shop::app.customer.account.release.create.first_name') }}</label>

                    <input
                        class="control"
                        type="text"
                        name="first_name"
                        value="{{ old('first_name') ?? $currentCustomer->first_name }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.first_name') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('first_name')"
                        v-if="errors.has('first_name')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.first_name.after') !!}

                <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                    <label for="last_name" class="mandatory">{{ __('shop::app.customer.account.release.create.last_name') }}</label>

                    <input
                        class="control"
                        type="text"
                        name="last_name"
                        value="{{ old('last_name') ?? $currentCustomer->last_name }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.last_name') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('last_name')"
                        v-if="errors.has('last_name')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.last_name.after') !!}

                <div class="control-group" :class="[errors.has('vat_id') ? 'has-error' : '']">
                    <label for="vat_id">{{ __('shop::app.customer.account.release.create.vat_id') }}
                        <span class="help-note">{{ __('shop::app.customer.account.release.create.vat_help_note') }}</span>
                    </label>

                    <input
                        class="control"
                        type="text"
                        name="vat_id"
                        value="{{ old('vat_id') }}"
                        v-validate=""
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.vat_id') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('vat_id')"
                        v-if="errors.has('vat_id')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.vat_id.after') !!}

                @php
                    $releasees = old('release1') ?? explode(PHP_EOL, '');
                @endphp

                <div class="control-group" :class="[errors.has('release1[]') ? 'has-error' : '']">
                    <label for="release_0" class="mandatory">{{ __('shop::app.customer.account.release.create.street-release') }}</label>

                    <input
                        class="control"
                        id="release_0"
                        type="text"
                        name="release1[]"
                        value="{{ $releasees[0] ?: '' }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.street-release') }}&quot;">

                    <span
                        class="control-error"
                        v-text="'{{ $errors->first('release1.*') }}'">
                    </span>
                </div>

                @if (
                    core()->getConfigData('customer.settings.release.street_lines')
                    && core()->getConfigData('customer.settings.release.street_lines') > 1
                )
                    @for ($i = 1; $i < core()->getConfigData('customer.settings.release.street_lines'); $i++)
                        <div class="control-group" style="margin-top: -25px;">
                            <input
                                class="control"
                                id="release_{{ $i }}"
                                type="text"
                                name="release1[{{ $i }}]"
                                value="{{ $releasees[$i] ?? '' }}">
                        </div>
                    @endfor
                @endif

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.street-release.after') !!}

                @include ('shop::customers.account.release.country-state', ['countryCode' => old('country'), 'stateCode' => old('state')])

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.country-state.after') !!}

                <div class="control-group" :class="[errors.has('city') ? 'has-error' : '']">
                    <label for="city" class="mandatory">{{ __('shop::app.customer.account.release.create.city') }}</label>

                    <input
                        type="text"
                        class="control"
                        name="city"
                        value="{{ old('city') }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.city') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('city')"
                        v-if="errors.has('city')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.city.after') !!}

                <div class="control-group" :class="[errors.has('postcode') ? 'has-error' : '']">
                    <label for="postcode" class="{{ core()->isPostCodeRequired() ? 'mandatory' : '' }}">{{ __('shop::app.customer.account.release.create.postcode') }}</label>

                    <input
                        class="control"
                        type="text"
                        name="postcode"
                        value="{{ old('postcode') }}"
                        v-validate="'{{ core()->isPostCodeRequired() ? 'required' : '' }}'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.postcode') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('postcode')"
                        v-if="errors.has('postcode')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.postcode.after') !!}

                <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                    <label for="phone" class="mandatory">{{ __('shop::app.customer.account.release.create.phone') }}</label>

                    <input
                        class="control"
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.phone') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('phone')"
                        v-if="errors.has('phone')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.after') !!}

                <div class="control-group d-flex">
                    <input
                        class="w-auto"
                        id="default_release"
                        type="checkbox"
                        name="default_release"
                        {{ old('default_release') ? 'checked' : '' }}>

                    <label class="checkbox-view" for="default_release"></label>

                    {{ __('shop::app.customer.account.release.default-release') }}
                </div>

                <div class="button-group">
                    <button class="theme-btn" type="submit">
                        {{ __('shop::app.customer.account.release.create.submit') }}
                    </button>
                </div>
            </div>
        </form>

    {!! view_render_event('bagisto.shop.customers.account.release.create.after') !!}
@endsection