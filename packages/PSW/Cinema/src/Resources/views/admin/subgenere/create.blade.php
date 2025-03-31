@php
$currentCustomer = auth()->guard('customer')->user();
@endphp
@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cinema.subgenere.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.cinema.subgenere.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.cinema.subgenere.index') }}'"></i>
                        {{ __('admin::app.cinema.subgenere.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.cinema.subgenere.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <input type="hidden" name="locale" value="all"/>
          
                    {!! view_render_event('bagisto.admin.cinema.subgenere.create_form_accordian.general.before') !!}

                        <div slot="body">
                            {!! view_render_event('bagisto.admin.cinema.subgenere.create_form_accordian.general.controls.before') !!}

                            <div class="control-group" :class="[errors.has('subge_name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.cinema.subgenere.subgenere_maintitle') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="subge_name" name="subge_name" value="{{ old('subge_name') }}" data-vv-as="&quot;{{ __('admin::app.cinema.subgenere.subgenere_maintitle') }}&quot;" v-slugify-target="'slug'"/>
                                <span class="control-error" v-if="errors.has('subge_name')">@{{ errors.first('subge_name') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('subge_name_en') ? 'has-error' : '']">
                                <label for="name" >{{ __('admin::app.cinema.subgenere.subgenere_othertitle') }}</label>
                                <input type="text" class="control" id="subge_name_en" name="subge_name_en" value="{{ old('generi_name_en') }}" data-vv-as="&quot;{{ __('admin::app.cinema.subgenere.subgenere_othertitle') }}&quot;" v-slugify-target="'slug'"/>
                                <span class="control-error" v-if="errors.has('subge_name_en')">@{{ errors.first('subge_name_en') }}</span>
                            </div>
                          
                            <div class="control-group">
                                <label for="status">{{ __('admin::app.cinema.master.master_is_visible') }}</label>
                                <label class="switch">
                                    <input type="hidden" name="subge_status" value="0" @if (old('subge_status')==0) {{ 'checked' }} @endif>
                                    <input type="checkbox" id="subge_status" name="subge_status" value="1" @if (old('subge_status')==1)  {{ 'checked' }} @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                           

                    {!! view_render_event('bagisto.admin.cinema.subgenere.create_form_accordian.general.after') !!}
                </div>
            </div>
        </form>
    </div>
@stop