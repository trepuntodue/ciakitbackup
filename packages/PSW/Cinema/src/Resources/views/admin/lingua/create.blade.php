@php
$currentCustomer = auth()->guard('customer')->user();
@endphp
@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cinema.lingua.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.cinema.lingua.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.cinema.lingua.index') }}'"></i>
                        {{ __('admin::app.cinema.lingua.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.cinema.lingua.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <input type="hidden" name="locale" value="all"/>
          
                    {!! view_render_event('bagisto.admin.cinema.lingua.create_form_accordian.general.before') !!}

                        <div slot="body">
                            {!! view_render_event('bagisto.admin.cinema.lingua.create_form_accordian.general.controls.before') !!}

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.cinema.lingua.name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="name" name="name" value="{{ old('name') }}" data-vv-as="&quot;{{ __('admin::app.cinema.lingua.name') }}&quot;" v-slugify-target="'slug'"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('name_en') ? 'has-error' : '']">
                                <label for="name" >{{ __('admin::app.cinema.lingua.name_en') }}</label>
                                <input type="text" class="control" id="name_en" name="name_en" value="{{ old('name_en') }}" data-vv-as="&quot;{{ __('admin::app.cinema.lingua.name_en') }}&quot;" v-slugify-target="'slug'"/>
                                <span class="control-error" v-if="errors.has('name_en')">@{{ errors.first('name_en') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="name" >{{ __('admin::app.cinema.lingua.code') }}</label>
                                <input type="text" class="control" id="code" name="code" value="{{ old('code') }}" data-vv-as="&quot;{{ __('admin::app.cinema.lingua.code') }}&quot;" v-slugify-target="'slug'"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>
                            <div class="control-group">
                                <label for="status">{{ __('admin::app.cinema.master.master_is_visible') }}</label>
                                <label class="switch">
                                    <input type="hidden" name="status" value="0" @if (old('status')==0) {{ 'checked' }} @endif>
                                    <input type="checkbox" id="status" name="status" value="1" @if (old('status')==1)  {{ 'checked' }} @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                           

                    {!! view_render_event('bagisto.admin.cinema.lingua.create_form_accordian.general.after') !!}
                </div>
            </div>
        </form>
    </div>
@stop