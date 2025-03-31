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

                <div class="control-group" :class="[errors.has('original_title') ? 'has-error' : '']">
                    <label for="original_title" class="mandatory">{{ __('shop::app.customer.account.release.create.original_title') }}</label>

                    <input
                        class="control"
                        type="text"
                        name="original_title"
                        v-validate="'required'"
                        value="{{ old('original_title') }}"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.original_title') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('original_title')"
                        v-if="errors.has('original_title')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.original_title.after') !!}

                <div class="control-group" :class="[errors.has('other_title') ? 'has-error' : '']">
                    <label for="other_title" >{{ __('shop::app.customer.account.release.create.other_title') }}</label>

                    <input
                        class="control"
                        type="text"
                        name="other_title"
                        value="{{ old('other_title') }}"
                        {{-- v-validate="'required'" --}}
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.other_title') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('other_title')"
                        v-if="errors.has('other_title')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.other_title.after') !!}

                <div class="control-group" :class="[errors.has('release_year') ? 'has-error' : '']">
                    <label for="release_year" class="mandatory">{{ __('shop::app.customer.account.release.create.release_year') }}</label>
                    <input
                        class="control"
                        type="text"
                        name="release_year"
                        value="{{ old('release_year') }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_year') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('release_year')"
                        v-if="errors.has('release_year')">
                    </span>
                </div>



                <div class="control-group" :class="[errors.has('country') ? 'has-error' : '']">
                    <label for="country" class="{{ core()->isCountryRequired() ? 'required' : '' }}">
                        {{ __('shop::app.customer.account.release.create.country') }}
                    </label>

                    <select
                        class="control"
                        id="country"
                        type="text"
                        name="country"
                        v-model="country"
                        multiple="multiple"
                        v-validate="'{{ core()->isCountryRequired() ? 'required' : '' }}'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.country') }}&quot;">
                        <option value=""></option>

                        @foreach (core()->countries() as $country)
                            <option {{ $country->code === $defaultCountry ? 'selected' : '' }}  value="{{ $country->code }}">{{ $country->name }}</option>
                        @endforeach
                    </select>

                    <span
                        class="control-error"
                        v-text="errors.first('country')"
                        v-if="errors.has('country')">
                    </span>
                </div>
                {{-- @include ('shop::customers.account.release.country-state', ['countryCode' => old('country'), 'stateCode' => old('state')]) --}}

                {{-- {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.country-state.after') !!} --}}

                <div class="control-group" :class="[errors.has('release_distribution') ? 'has-error' : '']">
                    <label for="release_distribution" class="mandatory">{{ __('shop::app.customer.account.release.create.release_distribution') }}</label>

                    <input
                        type="text"
                        class="control"
                        name="release_distribution"
                        value="{{ old('release_distribution') }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_distribution') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('release_distribution')"
                        v-if="errors.has('release_distribution')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.release_distribution.after') !!}

                <div class="control-group" :class="[errors.has('releasetype') ? 'has-error' : '']">
                    <label for="release_type" class="{{ core()->isPostCodeRequired() ? 'mandatory' : '' }}">{{ __('shop::app.customer.account.release.create.release_type') }}</label>
                    <select
                    class="control styled-select"
                    id="release_type"
                    type="text"
                    name="release_type"
                    v-model="release_type"

                    data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.release_type') }}&quot;">
                    <option value="">{{ __('Select release type') }}</option>
                    <option  value="video">Video</option>
                    <option   value="poster">Poster</option>
                    {{-- @foreach (core()->countries() as $country)
                        <option {{ $country->code === $defaultCountry ? 'selected' : '' }}  value="{{ $country->code }}">{{ $country->name }}</option>
                    @endforeach --}}
                </select>

                    {{-- <input
                        class="control styled-select"
                        type="text"
                        name="release_type"
                        value="{{ old('release_type') }}"
                        v-validate="'{{ core()->isPostCodeRequired() ? 'required' : '' }}'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_type') }}&quot;"> --}}

                    <span
                        class="control-error"
                        v-text="errors.first('release_type')"
                        v-if="errors.has('release_type')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.release_type.after') !!}

                <div class="control-group" :class="[errors.has('release_language') ? 'has-error' : '']">
                    <label for="release_language" class="mandatory">{{ __('shop::app.customer.account.release.create.release_language') }}</label>

                    <input
                        class="control"
                        type="text"
                        name="release_language"
                        value="{{ old('release_language') }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_language') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('release_language')"
                        v-if="errors.has('release_language')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.after') !!}



                <div class="button-group">
                    <button class="theme-btn" type="submit">
                        {{ __('shop::app.customer.account.release.create.submit') }}
                    </button>
                </div>
            </div>
        </form>

    {!! view_render_event('bagisto.shop.customers.account.release.create.after') !!}
@endsection
