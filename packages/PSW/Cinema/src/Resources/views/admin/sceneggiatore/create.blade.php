@php
$currentCustomer = auth()->guard('customer')->user();
@endphp
@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cinema.sceneggiatore.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.cinema.sceneggiatore.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.cinema.sceneggiatore.index') }}'"></i>
                        {{ __('admin::app.cinema.sceneggiatore.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.cinema.sceneggiatore.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <input type="hidden" name="locale" value="all"/>
          
                    {!! view_render_event('bagisto.admin.cinema.sceneggiatore.create_form_accordian.general.before') !!}

                        <div slot="body">
                            {!! view_render_event('bagisto.admin.cinema.sceneggiatore.create_form_accordian.general.controls.before') !!}

                            <div class="control-group" :class="[errors.has('scene_nome') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.cinema.sceneggiatore.scene_nome') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="scene_nome" name="scene_nome" value="{{ old('scene_nome') }}" data-vv-as="&quot;{{ __('admin::app.cinema.sceneggiatore.scene_nome') }}&quot;" v-slugify-target="'slug'"/>
                                <span class="control-error" v-if="errors.has('scene_nome')">@{{ errors.first('scene_nome') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('scene_cognome') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.cinema.sceneggiatore.scene_cognome') }}</label>
                                <input type="text" v-validate="'required'"  class="control" id="scene_cognome" name="scene_cognome" value="{{ old('scene_cognome') }}" data-vv-as="&quot;{{ __('admin::app.cinema.sceneggiatore.scene_cognome') }}&quot;" v-slugify-target="'slug'"/>
                                <span class="control-error" v-if="errors.has('scene_cognome')">@{{ errors.first('scene_cognome') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('scene_alias') ? 'has-error' : '']">
                                <label for="name" >{{ __('admin::app.cinema.sceneggiatore.scene_alias') }}</label>
                                <input type="text" class="control" id="scene_alias" name="scene_alias" value="{{ old('scene_alias') }}" data-vv-as="&quot;{{ __('admin::app.cinema.sceneggiatore.scene_alias') }}&quot;" v-slugify-target="'slug'"/>
                                <span class="control-error" v-if="errors.has('scene_alias')">@{{ errors.first('scene_alias') }}</span>
                            </div>
                          
                            <div class="control-group">
                                <label for="status">{{ __('admin::app.cinema.master.master_is_visible') }}</label>
                                <label class="switch">
                                    <input type="hidden" name="status" value="0" @if (old('status')==0) {{ 'checked' }} @endif>
                                    <input type="checkbox" id="status" name="status" value="1" @if (old('status')==1)  {{ 'checked' }} @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                           

                    {!! view_render_event('bagisto.admin.cinema.sceneggiatore.create_form_accordian.general.after') !!}
                </div>
            </div>
        </form>
    </div>
@stop