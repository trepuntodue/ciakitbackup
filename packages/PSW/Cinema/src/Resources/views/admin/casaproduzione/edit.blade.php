@php
$currentCustomer = auth()->guard('customer')->user();
@endphp
@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cinema.casaproduzione.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.cinema.casaproduzione.update', $casaproduzioni->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.cinema.casaproduzione.index') }}'"></i>
                        {{ __('admin::app.cinema.casaproduzione.edit-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.cinema.casaproduzione.update-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @method('PUT')

                 @csrf

                    <input type="hidden" name="locale" value="all"/>
               
                    <div class="control-group" :class="[errors.has('casa_nome') ? 'has-error' : '']">
                        <label for="name" class="required">{{ __('admin::app.cinema.casaproduzione.casa_nome') }}</label>
                        <input type="text" v-validate="'required'" class="control" id="casa_nome" name="casa_nome" value="{{ $casaproduzioni->casa_nome }}" data-vv-as="&quot;{{ __('admin::app.cinema.casaproduzione.casa_nome') }}&quot;" v-slugify-target="'slug'"/>
                        <span class="control-error" v-if="errors.has('casa_nome')">@{{ errors.first('casa_nome') }}</span>
                    </div>
                    {{-- <div class="control-group" :class="[errors.has('casaproduzioni_cognome') ? 'has-error' : '']">
                        <label for="name"  class="required">{{ __('admin::app.cinema.casaproduzione.casaproduzioni_cognome') }}</label>
                        <input type="text"  v-validate="'required'" class="control" id="casaproduzioni_cognome" name="casaproduzioni_cognome" value="{{ $casaproduzioni->casaproduzioni_cognome }}" data-vv-as="&quot;{{ __('admin::app.cinema.casaproduzione.casaproduzioni_cognome') }}&quot;" v-slugify-target="'slug'"/>
                        <span class="control-error" v-if="errors.has('casaproduzioni_cognome')">@{{ errors.first('casaproduzioni_cognome') }}</span>
                    </div>
                    <div class="control-group" :class="[errors.has('casaproduzioni_alias') ? 'has-error' : '']">
                        <label for="name" >{{ __('admin::app.cinema.casaproduzione.casaproduzioni_alias') }}</label>
                        <input type="text" class="control" id="casaproduzioni_alias" name="casaproduzioni_alias" value="{{ $casaproduzioni->casaproduzioni_alias }}" data-vv-as="&quot;{{ __('admin::app.cinema.casaproduzione.casaproduzioni_alias') }}&quot;" v-slugify-target="'slug'"/>
                        <span class="control-error" v-if="errors.has('casaproduzioni_alias')">@{{ errors.first('casaproduzioni_alias') }}</span>
                    </div> --}}
                  
                    <div class="control-group">
                        <label for="status">{{ __('admin::app.cinema.master.master_is_visible') }}</label>
                        <label class="switch">
                            <input type="hidden" name="status" value="0" @if ($casaproduzioni->status==0) {{ 'checked' }} @endif>
                            <input type="checkbox" id="status" name="status" value="1" @if ($casaproduzioni->status==1)  {{ 'checked' }} @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    {!! view_render_event('bagisto.admin.cinema.casaproduzione.create_form_accordian.general.after') !!}

                   

                </div>
            </div>
        </form>
    </div>
@stop