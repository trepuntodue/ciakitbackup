@php
$currentCustomer = auth()->guard('customer')->user();
@endphp
@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cinema.regista.edittitle') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.cinema.regista.update', $registi->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.cinema.regista.index') }}'"></i>
                        {{ __('admin::app.cinema.regista.edit-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.cinema.regista.update-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @method('PUT')

                 @csrf

                    <input type="hidden" name="locale" value="all"/>
               
                    <div class="control-group" :class="[errors.has('registi_nome') ? 'has-error' : '']">
                        <label for="name" class="required">{{ __('admin::app.cinema.regista.registi_nome') }}</label>
                        <input type="text" v-validate="'required'" class="control" id="registi_nome" name="registi_nome" value="{{ $registi->registi_nome }}" data-vv-as="&quot;{{ __('admin::app.cinema.regista.registi_nome') }}&quot;" v-slugify-target="'slug'"/>
                        <span class="control-error" v-if="errors.has('registi_nome')">@{{ errors.first('registi_nome') }}</span>
                    </div>
                    <div class="control-group" :class="[errors.has('registi_cognome') ? 'has-error' : '']">
                        <label for="name"  class="required">{{ __('admin::app.cinema.regista.registi_cognome') }}</label>
                        <input type="text"  v-validate="'required'" class="control" id="registi_cognome" name="registi_cognome" value="{{ $registi->registi_cognome }}" data-vv-as="&quot;{{ __('admin::app.cinema.regista.registi_cognome') }}&quot;" v-slugify-target="'slug'"/>
                        <span class="control-error" v-if="errors.has('registi_cognome')">@{{ errors.first('registi_cognome') }}</span>
                    </div>
                    <div class="control-group" :class="[errors.has('registi_alias') ? 'has-error' : '']">
                        <label for="name" >{{ __('admin::app.cinema.regista.registi_alias') }}</label>
                        <input type="text" class="control" id="registi_alias" name="registi_alias" value="{{ $registi->registi_alias }}" data-vv-as="&quot;{{ __('admin::app.cinema.regista.registi_alias') }}&quot;" v-slugify-target="'slug'"/>
                        <span class="control-error" v-if="errors.has('registi_alias')">@{{ errors.first('registi_alias') }}</span>
                    </div>
                  
                    <div class="control-group">
                        <label for="status">{{ __('admin::app.cinema.master.master_is_visible') }}</label>
                        <label class="switch">
                            <input type="hidden" name="status" value="0" @if ($registi->status==0) {{ 'checked' }} @endif>
                            <input type="checkbox" id="status" name="status" value="1" @if ($registi->status==1)  {{ 'checked' }} @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    {!! view_render_event('bagisto.admin.cinema.regista.create_form_accordian.general.after') !!}

                   

                </div>
            </div>
        </form>
    </div>
@stop