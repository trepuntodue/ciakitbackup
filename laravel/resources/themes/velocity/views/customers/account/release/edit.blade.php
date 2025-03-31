@extends('shop::customers.account.index')
@section('page_title')
    {{ __('shop::app.customer.account.release.
    page-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head mb-15">
        <span class="account-heading">{{ __('shop::app.customer.account.release.edit.title') }}</span>

        <span></span>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.release.edit.before', ['release' => $release]) !!}

    <form method="post" action="{{ route('customer.release.update', $release->id) }}" @submit.prevent="onSubmit">
        <div class="account-table-content mb-2">
            @method('PUT')

            @csrf

            {!! view_render_event('bagisto.shop.customers.account.release.edit_form_controls.before', ['release' => $release]) !!}

            <div class="control-group" :class="[errors.has('master_id') ? 'has-error' : '']">
               <label for="master_id" class="mandatory">{{ __('admin::app.cinema.release.master_id') }}</label>

               <select  data-mdb-filter="true" class="control js-example-basic-multiple" v-validate="'required'" id="master_id" name="master_id" data-vv-as="&quot;{{ __('admin::app.cinema.release.master_id') }}&quot;">
                   <option value="">Seleziona Master</option>
               @php
                $dt = $master_selezionato;
               @endphp
                   @foreach ($masters as $mas)
                       <option {{ $mas->master_id == $dt ? 'selected' : '' }}  value="{{ $mas->master_id }}">{{ $mas->master_maintitle }}</option> <!-- PWS#8-link-master -->
                   @endforeach
               </select>

               <span
                   class="control-error"
                   v-text="errors.first('master_id')"
                   v-if="errors.has('master_id')">
               </span>
           </div>

            <div class="control-group" :class="[errors.has('original_title') ? 'has-error' : '']">
                <label for="original_title" class="mandatory">{{ __('shop::app.customer.account.release.edit.original_title') }}</label>

                <input
                    class="control"
                    type="text"
                    name="original_title"
                    v-validate="'required'"
                    value="{{ old('original_title') ?? $release->original_title }}"
                    data-vv-as="&quot;{{ __('shop::app.customer.account.release.edit.original_title') }}&quot;">

                <span
                    class="control-error"
                    v-text="errors.first('original_title')"
                    v-if="errors.has('original_title')">
                </span>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.release.edit_form_controls.original_title.after') !!}

            <div class="control-group" :class="[errors.has('other_title') ? 'has-error' : '']">
                <label for="other_title" >{{ __('shop::app.customer.account.release.create.other_title') }}</label>

                <input
                    type="text"
                    class="control"
                    name="other_title"
                    value="{{ old('other_title') ?? $release->other_title }}"

                    data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.other_title') }}&quot;">

                <span
                    class="control-error"
                    v-text="errors.first('other_title')"
                    v-if="errors.has('other_title')">
                </span>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.release.edit_form_controls.other_title.after') !!}

            <div class="control-group" :class="[errors.has('release_year') ? 'has-error' : '']">
                <label for="release_year" class="mandatory">{{ __('shop::app.customer.account.release.create.release_year') }}</label>

                <input
                    class="control"
                    type="text"
                    name="release_year"
                    value="{{ old('release_year') ?? $release->release_year }}"
                    v-validate="'required'"
                    data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_year') }}&quot;">

                <span
                    class="control-error"
                    v-text="errors.first('release_year')"
                    v-if="errors.has('release_year')">
                </span>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.release.edit_form_controls.release_year.after') !!}

            <div class="control-group" :class="[errors.has('release_distribution') ? 'has-error' : '']">
                <label for="release_distribution">{{ __('shop::app.customer.account.release.create.release_distribution') }}
                </label>

                <input
                    class="control"
                    type="text"
                    name="release_distribution"
                    value="{{ old('release_distribution') ?? $release->release_distribution }}"
                    v-validate=""
                    data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_distribution') }}&quot;">

                <span
                    class="control-error"
                    v-text="errors.first('release_distribution')"
                    v-if="errors.has('release_distribution')">
                </span>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.release.edit_form_controls.release_distribution.after') !!}


            @include ('shop::customers.account.release.country-state', ['countryCode' => old('country') ?? $release->country])

            {!! view_render_event('bagisto.shop.customers.account.release.edit_form_controls.country-state.after') !!}

            @include ('shop::customers.account.release.releasetype', ['releasetypeCode' => old('releasetype') ?? $release->releasetype])

            {!! view_render_event('bagisto.shop.customers.account.release.edit_form_controls.releasetype.after') !!}

            {{-- <div id="release-type" class="control-group" :class="[errors.has('release_type') ? 'has-error' : '']">
                <label for="release_type" class="mandatory">{{ __('shop::app.customer.account.release.create.release_type') }}</label>

                <select
                class="control styled-select"
                id="release_type"
                type="text"
                name="release_type"
                v-model="release_type"
                data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_type') }}&quot;">
                <option value="" >{{ __('Seleziona tipo') }}</option>
                @if ($release->release_type == 'video')
                    <option selected value="video">Video</option>
                    <option  value="poster">Poster</option>
                @else
                    <option  value="video">Video</option>
                    <option  selected value="poster">Poster</option>
                @endif

            </select>



                <span
                    class="control-error"
                    v-text="errors.first('release_type')"
                    v-if="errors.has('release_type')">
                </span>
            </div> --}}

            {{-- {!! view_render_event('bagisto.shop.customers.account.release.edit_form_controls.create.after') !!} --}}


            {{-- <script type="text/x-template" id="layered-navigation-template">
            <div id="lingua" class="control-group" :class="[errors.has('release_language') ? 'has-error' : '']">
                <label for="release_language" class="mandatory">{{ __('shop::app.customer.account.release.create.release_language') }}</label>

                <select
                class="control styled-select"
                id="release_language"
                type="text"
                name="release_language"
                v-model="release_language"
                value="{{ old('release_language') ?? $release->release_language }}"
                data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_language') }}&quot;">
                <option value="" >{{ __('Seleziona lingua') }}</option>
                @foreach ($language as $lang)
                <option value="{{ $lang->name }}" @if($lang->name == $defaultLanguage) selected @endif >{{ $lang->name }}</option>
            @endforeach
            </select>
                <span
                    class="control-error"
                    v-text="errors.first('release_language')"
                    v-if="errors.has('release_language')">
                </span>
            </div>
        </script> --}}
            @include ('shop::customers.account.release.language', ['languageCode' => old('language') ?? $release->language])

            {!! view_render_event('bagisto.shop.customers.account.release.edit_form_controls.language.after') !!}

            {!! view_render_event('bagisto.shop.customers.account.release.edit_form_controls.after', ['release' => $release]) !!}



            <div class="control-group"> <!-- START PWS#8-link-master -->
                <label for="release_is_visible">{{ __('admin::app.cinema.release.release_is_visible') }}</label>
                <label class="switch">
                    <input type="hidden" name="release_is_visible" value="0" @if ((old('release_is_visible') ?: $release->release_is_visible) ==0) {{ 'checked' }} @endif>
                    <input type="checkbox" id="release_is_visible" name="release_is_visible" value="1" @if ((old('release_is_visible') ?: $release->release_is_visible) ==1)  {{ 'checked' }} @endif>
                    <span class="slider round"></span>
                </label>
            </div> <!-- END PWS#8-link-master -->

            <div class="button-group">
                <button class="theme-btn" type="submit">
                    {{ __('shop::app.customer.account.release.create.submit') }}
                </button>
            </div>
        </div>
    </form>

    {!! view_render_event('bagisto.shop.customers.account.release.edit.after', ['release' => $release]) !!}

@endsection
