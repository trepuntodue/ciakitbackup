@php
$currentCustomer = auth()->guard('customer')->user();
@endphp
@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cinema.genere.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.cinema.genere.update', $generi->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.cinema.genere.index') }}'"></i>
                        {{ __('admin::app.cinema.genere.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.cinema.genere.update-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @method('PUT')

                 @csrf

                    <input type="hidden" name="locale" value="all"/>
               
                    <div class="control-group" :class="[errors.has('generi_name') ? 'has-error' : '']">
                        <label for="name" class="required">{{ __('admin::app.cinema.genere.genere_maintitle') }}</label>
                        <input type="text" v-validate="'required'" class="control" id="generi_name" name="generi_name" value="{{ $generi->generi_name}}" data-vv-as="&quot;{{ __('admin::app.cinema.genere.genere_maintitle') }}&quot;" v-slugify-target="'slug'"/>
                        <span class="control-error" v-if="errors.has('generi_name')">@{{ errors.first('generi_name') }}</span>
                    </div>
                    <div class="control-group" :class="[errors.has('generi_name_en') ? 'has-error' : '']">
                        <label for="name" >{{ __('admin::app.cinema.genere.genere_othertitle') }}</label>
                        <input type="text" class="control" id="generi_name_en" name="generi_name_en" value="{{ $generi->generi_name_en }}" data-vv-as="&quot;{{ __('admin::app.cinema.genere.genere_othertitle') }}&quot;" v-slugify-target="'slug'"/>
                        <span class="control-error" v-if="errors.has('generi_name_en')">@{{ errors.first('generi_name_en') }}</span>
                    </div>
                    <div class="control-group">
                        <label for="status">{{ __('admin::app.cinema.genere.status') }}</label>
                        <label class="switch">
                            <input type="hidden" name="status" value="0" @if ($generi->status==0) {{ 'checked' }} @endif>
                            <input type="checkbox" id="status" name="status" value="1" @if ($generi->status==1)  {{ 'checked' }} @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    {!! view_render_event('bagisto.admin.cinema.genere.create_form_accordian.general.after') !!}

                   

                </div>
            </div>
        </form>
    </div>
@stop