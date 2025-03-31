@php
$currentCustomer = auth()->guard('customer')->user();
@endphp
@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cinema.attore.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.cinema.attore.update', $attori->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.cinema.attore.index') }}'"></i>
                        {{ __('admin::app.cinema.attore.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.cinema.attore.update-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @method('PUT')

                 @csrf

                    <input type="hidden" name="locale" value="all"/>

                    <div class="control-group" :class="[errors.has('attori_alias') ? 'has-error' : '']"> <!-- PWS#2-attori -->
                        <label for="name" >{{ __('admin::app.cinema.attore.attori_alias') }}</label>
                        <input type="text" class="control" id="attori_alias" name="attori_alias" value="{{ $attori->attori_alias }}" data-vv-as="&quot;{{ __('admin::app.cinema.attore.attori_alias') }}&quot;" v-slugify-target="'slug'"/>
                        <span class="control-error" v-if="errors.has('attori_alias')">@{{ errors.first('attori_alias') }}</span>
                    </div>
                    <div class="control-group" :class="[errors.has('attori_nome') ? 'has-error' : '']">
                        <label for="name" class="required">{{ __('admin::app.cinema.attore.attori_nome') }}</label>
                        <input type="text" v-validate="'required'" class="control" id="attori_nome" name="attori_nome" value="{{ $attori->attori_nome }}" data-vv-as="&quot;{{ __('admin::app.cinema.attore.attori_nome') }}&quot;" v-slugify-target="'slug'"/>
                        <span class="control-error" v-if="errors.has('attori_nome')">@{{ errors.first('attori_nome') }}</span>
                    </div>
                    <div class="control-group" :class="[errors.has('attori_cognome') ? 'has-error' : '']">
                        <label for="name"  class="required">{{ __('admin::app.cinema.attore.attori_cognome') }}</label>
                        <input type="text"  v-validate="'required'" class="control" id="attori_cognome" name="attori_cognome" value="{{ $attori->attori_cognome }}" data-vv-as="&quot;{{ __('admin::app.cinema.attore.attori_cognome') }}&quot;" v-slugify-target="'slug'"/>
                        <span class="control-error" v-if="errors.has('attori_cognome')">@{{ errors.first('attori_cognome') }}</span>
                    </div>

                    <div class="control-group">
                        <label for="status">{{ __('admin::app.cinema.master.master_is_visible') }}</label>
                        <label class="switch">
                            <input type="hidden" name="status" value="0" @if ($attori->status==0) {{ 'checked' }} @endif>
                            <input type="checkbox" id="status" name="status" value="1" @if ($attori->status==1)  {{ 'checked' }} @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    {!! view_render_event('bagisto.admin.cinema.attore.create_form_accordian.general.after') !!}



                </div>
            </div>
        </form>
    </div>
@stop
