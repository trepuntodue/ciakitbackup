@php
$currentCustomer = auth()->guard('customer')->user();
@endphp
@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cinema.compositore.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.cinema.compositore.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.cinema.compositore.index') }}'"></i>
                        {{ __('admin::app.cinema.compositore.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.cinema.compositore.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <input type="hidden" name="locale" value="all"/>

                    {!! view_render_event('bagisto.admin.cinema.compositore.create_form_accordian.general.before') !!}

                        <div slot="body">
                            {!! view_render_event('bagisto.admin.cinema.compositore.create_form_accordian.general.controls.before') !!}

                            <div class="control-group" :class="[errors.has('compo_nome') ? 'has-error' : '']">
                                <label for="compo_nome">{{ __('admin::app.cinema.compositore.compo_nome') }}</label> <!-- PWS#2-2 -->
                                <input type="text" class="control" id="compo_nome" name="compo_nome" value="{{ old('compo_nome') }}" data-vv-as="&quot;{{ __('admin::app.cinema.compositore.compo_nome') }}&quot;" /> <!-- PWS#2-2 -->
                                <span class="control-error" v-if="errors.has('compo_nome')">@{{ errors.first('compo_nome') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('compo_cognome') ? 'has-error' : '']">
                                <label for="compo_cognome">{{ __('admin::app.cinema.compositore.compo_cognome') }}</label> <!-- PWS#2-2 -->
                                <input type="text" class="control" id="compo_cognome" name="compo_cognome" value="{{ old('compo_cognome') }}" data-vv-as="&quot;{{ __('admin::app.cinema.compositore.compo_cognome') }}&quot;" /> <!-- PWS#2-2 -->
                                <span class="control-error" v-if="errors.has('compo_cognome')">@{{ errors.first('compo_cognome') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('compo_alias') ? 'has-error' : '']">
                                <label for="compo_alias" class="required" >{{ __('admin::app.cinema.compositore.compo_alias') }}</label> <!-- PWS#2-2 -->
                                <input type="text" v-validate="'required'" class="control" id="compo_alias" name="compo_alias" value="{{ old('compo_alias') }}" data-vv-as="&quot;{{ __('admin::app.cinema.compositore.compo_alias') }}&quot;" /> <!-- PWS#2-2 -->
                                <span class="control-error" v-if="errors.has('compo_alias')">@{{ errors.first('compo_alias') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="status">{{ __('admin::app.cinema.master.master_is_visible') }}</label>
                                <label class="switch">
                                    <input type="hidden" name="status" value="0" @if (old('status')==0) {{ 'checked' }} @endif>
                                    <input type="checkbox" id="status" name="status" value="1" @if (old('status')==1)  {{ 'checked' }} @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>


                    {!! view_render_event('bagisto.admin.cinema.compositore.create_form_accordian.general.after') !!}
                </div>
            </div>
        </form>
    </div>
@stop
