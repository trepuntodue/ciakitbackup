@php
    $currentCustomer = auth()->guard('customer')->user();
@endphp

@extends('shop::customers.account.index')
@section('page_title')
    {{ __('shop::app.customer.account.release.create.page-title') }}
@endsection

@push('css')
<style type="text/css">
    input[type="radio"]:checked  + .tipo {
        background-color: #d8af00;
    }
</style>
@endpush

@section('page-detail-wrapper')
    <div class="account-head mb-15">
        <span class="account-heading">{{ __('shop::app.customer.account.release.create.title') }}</span>
    </div>
    
    {!! view_render_event('bagisto.shop.customers.account.release.create.before') !!}

    <!-- PWS#video-poster --> <!-- PWS#mmm -->
    @if(!$action && !request()->get('tipo'))
        <meta name="_token" content="{{csrf_token()}}" />
        <div class="step_1">
            <h2 class="text-center uppercase text-xl">selezionare tipo</h2>
            <form id="cerca_release" method="post" action="{{ route('customer.release.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
                <div class="account-table-content mb-2">
                @csrf
                    <div class="tipo-container grid grid-cols-2 content-evenly gap-4 text-center mt-4 mb-4">
                        <input class="hidden" type="radio" id="tipo-video" name="tipo" value="video">
                        <div class="tipo video bg-themeColor-500 rounded-md text-white flex">
                            <label class="w-full h-full p-5 uppercase" for="tipo-video">
                                Video
                            </label>
                        </div>
                        <input class="hidden" type="radio" id="tipo-poster" name="tipo" value="poster">
                        <div class="tipo poster bg-themeColor-500 rounded-md text-white flex">
                            <label class="w-full h-full p-5 uppercase" for="tipo-poster">
                                Poster
                            </label>
                        </div>
                    </div>

                    <div class="campo-cerca-container">
                        <div class="cerca video mb-4 flex justify-center" style="display:none;">
                            <label class="text-center">
                                {{ __('shop::app.customer.account.release.create.numero_catalogo') }}
                                <input
                                    minlength="3" 
                                    required
                                    id="numero_catalogo"
                                    class="control"
                                    type="number"
                                    name="numero_catalogo">
                            </label> <!-- PWS#finale -->
                        </div>
                        <div class="cerca poster mb-4 flex justify-center" style="display:none;">
                            <label class="text-center">
                                {{ __('shop::app.customer.account.release.create.original_title') }}
                                <input
                                    minlength="3" 
                                    required
                                    id="original_title"
                                    class="control"
                                    type="text"
                                    name="original_title"
                                    data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.original_title') }}&quot;">
                            </label>
                        </div>
                    </div>

                    <div class="button-group btn-submit" style="display:none;">
                        <input class="theme-btn" type="button" value="{{ __('shop::app.common.search') }}" onclick="form.reportValidity();">
                    </div>
                </div>
            </form>
        </div>
        <div class="step_2" style="display:none;">
            <h2 class="text-center uppercase text-xl mb-4">{{ __('shop::app.common.seleziona_valore') }}</h2>
            <div class="results releases">
            </div>
            <div class="add-new">
                <form id="crea_nuovo" method="get" action="{{ route('customer.release.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
                    <div class="account-table-content mb-2">
                        <input type="hidden" id="tipo" name="tipo" class="tipo" value="">
                        <div class="button-group btn-submit">
                            <input class="theme-btn" type="button" value="{{ __('shop::app.common.create_new') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @elseif(request()->get('tipo') && (request()->get('tipo') == 'video' || request()->get('tipo') == 'poster'))

        @php
            $tipo = request()->get('tipo');
        @endphp
        
        <form method="post" action="{{ route('customer.release.store') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <!-- START PWS#8-link-master -->
            <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer_id}}">
            <input type="hidden" name="action" id="action" value="create">
            <!-- END PWS#8-link-master -->
            <div class="account-table-content mb-2">
                @csrf

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.before') !!}

                <div class="control-group" :class="[errors.has('master_id') ? 'has-error' : '']">

                 <label for="master_id" class="mandatory">{{ __('shop::app.customer.account.release.create.linked_master') }}</label>

                 <select  data-mdb-filter="true" class="control js-example-basic-multiple" v-validate="'required'" id="master_id" name="master_id" data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.linked_master') }}&quot;">
                     <option value="">{{ __('shop::app.customer.account.release.create.linked_master') }}</option> <!-- PWS#13-film PWS#finale -->
                 @php
                 $dt = false;
                 if($selected_master !== false) $dt = $selected_master->master_id; // PWS#230101
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
                    <label for="original_title"  class="mandatory">{{ __('shop::app.customer.account.release.create.original_title') }}</label>
                    @php
                    $dt = '';
                    if($selected_master !== false) $dt = $selected_master->master_maintitle;
                    @endphp
                    <input
                        class="control"
                        type="text"
                        name="original_title"
                        value="{{ $dt }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.original_title') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('original_title')"
                        v-if="errors.has('original_title')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.original_title.after') !!}

                <div class="control-group" :class="[errors.has('other_title') ? 'has-error' : '']">
                    <label for="other_title">{{ __('shop::app.customer.account.release.create.other_title') }}</label>
                    @php
                    $dt = '';
                    if($selected_master !== false) $dt = $selected_master->master_othertitle;
                    @endphp
                    <input
                        class="control"
                        type="text"
                        name="other_title"
                        value="{{ $dt }}"
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
                    @php
                    $dt = '';
                    if($selected_master !== false) $dt = $selected_master->master_year;
                    @endphp
                    <input
                        class="control"
                        type="text"
                        name="release_year"
                        value="{{ $dt }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_year') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('release_year')"
                        v-if="errors.has('release_year')">
                    </span>
                </div>



              @include ('shop::customers.account.release.country-state', ['countryCode' => old('country'), 'stateCode' => old('state')])

                 {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.country-state.after') !!}

                <div class="control-group" :class="[errors.has('release_distribution') ? 'has-error' : '']">
                    <label for="release_distribution" class="mandatory">{{ __('shop::app.customer.account.release.create.release_distribution') }}</label>
                    @php
                    $dt = '';
                    if($selected_master !== false) $dt = $selected_master->casaproduzione_nome;
                    @endphp
                    <input
                        type="text"
                        class="control"
                        name="release_distribution"
                        value="{{ $dt }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_distribution') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('release_distribution')"
                        v-if="errors.has('release_distribution')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.release_distribution.after') !!}

          @include ('shop::customers.account.release.releasetype', ['releasetypeCode' => old('releasetype') ]) <!-- PWS#02-23 -->

                {!! view_render_event('bagisto.shop.customers.account.release.edit_form_controls.releasetype.after') !!}

                {{-- <div class="control-group" :class="[errors.has('release_type') ? 'has-error' : '']">
                    <label for="release_type" class="{{ core()->isPostCodeRequired() ? 'mandatory' : '' }}">{{ __('shop::app.customer.account.release.create.release_type') }}</label>
                    <select
                    class="control styled-select"
                    id="release_type"
                    type="text"
                    name="release_type"

                    data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_type') }}&quot;">
                    <option value="" selected="selected">{{ __('Seleziona tipo') }}</option>
                    <option  value="video">Video</option>
                    <option   value="poster">Poster</option>

                </select>


                    <span
                        class="control-error"
                        v-text="errors.first('release_type')"
                        v-if="errors.has('release_type')">
                    </span>
                </div> --}}

                {{-- {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.release_type.after') !!} --}}

                {{-- <div class="control-group" :class="[errors.has('release_language') ? 'has-error' : '']">
                    <label for="release_language" class="mandatory">{{ __('shop::app.customer.account.release.create.release_language') }}</label>

                         <select
                        class="control styled-select"
                        id="release_language"
                        type="text"
                        name="release_language"

                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_language') }}&quot;">
                        <option value="" selected="selected">{{ __('Seleziona lingua') }}</option>
                        <option  value="italiano">italiano</option>
                        <option   value="inglese">inglese</option>
                        <option  value="francese">francese</option>
                        <option   value="tedesco">tedesco</option>

                    </select>
                    <span
                        class="control-error"
                        v-text="errors.first('release_language')"
                        v-if="errors.has('release_language')">
                    </span>
                </div> --}}
                @include ('shop::customers.account.release.language', ['languageCode' => old('language') ])

                <!-- START PWS#02-23 -->
                @if($tipo == 'video')
                    <div class="control-group" :class="[errors.has('formato') ? 'has-error' : '']" data-available_for="video">
                        <label for="formato" class="mandatory">
                            {{ __('shop::app.customer.account.release.create.formato') }}
                        </label>

                        
                        <select
                            class="control styled-select"
                            id="formato"
                            type="text"
                            name="formato"
                            v-validate="'required'"
                            data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.formato') }}&quot;">
                            <option value="">{{ __('Seleziona formato') }}</option>

                            @foreach ($release_formato as $formato)
                                <option value="{{ $formato->id }}">{{ $formato->nome }}</option> 
                            @endforeach
                        </select>

                        <div class="select-icon-container">
                            <span class="select-icon rango-arrow-down"></span>
                        </div>

                        <span
                            class="control-error"
                            v-text="errors.first('formato')"
                            v-if="errors.has('formato')">
                        </span>
                    </div>
                @endif

                @if($tipo == 'video')
                <div class="control-group" :class="[errors.has('aspect_ratio') ? 'has-error' : '']" data-available_for="video">
                    <label for="aspect_ratio" class="mandatory">
                        {{ __('shop::app.customer.account.release.create.aspect_ratio') }}
                    </label>

                    
                    <select
                        class="control styled-select"
                        id="aspect_ratio"
                        type="text"
                        name="aspect_ratio"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.aspect_ratio') }}&quot;">
                        <option value="">{{ __('Seleziona aspect ratio') }}</option>

                        @foreach ($release_aspect_ratio as $aspect_ratio)
                            <option value="{{ $aspect_ratio->id }}">{{ $aspect_ratio->nome }}</option> 
                        @endforeach
                    </select>

                    <div class="select-icon-container">
                        <span class="select-icon rango-arrow-down"></span>
                    </div>

                    <span
                        class="control-error"
                        v-text="errors.first('aspect_ratio')"
                        v-if="errors.has('aspect_ratio')">
                    </span>
                </div>
                @endif

                @if($tipo == 'video')
                <div class="control-group" :class="[errors.has('camera_format') ? 'has-error' : '']" data-available_for="video">
                    <label for="camera_format" class="mandatory">
                        {{ __('shop::app.customer.account.release.create.camera_format') }}
                    </label>

                    
                    <select
                        class="control styled-select"
                        id="camera_format"
                        type="text"
                        name="camera_format"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.camera_format') }}&quot;">
                        <option value="">{{ __('Seleziona camera format') }}</option>

                        @foreach ($release_camera_format as $camera_format)
                            <option value="{{ $camera_format->id }}">{{ $camera_format->nome }}</option> 
                        @endforeach
                    </select>

                    <div class="select-icon-container">
                        <span class="select-icon rango-arrow-down"></span>
                    </div>

                    <span
                        class="control-error"
                        v-text="errors.first('camera_format')"
                        v-if="errors.has('camera_format')">
                    </span>
                </div>
                @endif

                @if($tipo == 'video')
                <div class="control-group" :class="[errors.has('region_code') ? 'has-error' : '']" data-available_for="video">
                    <label for="region_code" class="mandatory">
                        {{ __('shop::app.customer.account.release.create.region_code') }}
                    </label>

                    
                    <select
                        class="control styled-select"
                        id="region_code"
                        type="text"
                        name="region_code"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.region_code') }}&quot;">
                        <option value="">{{ __('Seleziona region code') }}</option>

                        @foreach ($release_region_code as $region_code)
                            <option value="{{ $region_code->id }}">{{ $region_code->nome }}</option> 
                        @endforeach
                    </select>

                    <div class="select-icon-container">
                        <span class="select-icon rango-arrow-down"></span>
                    </div>

                    <span
                        class="control-error"
                        v-text="errors.first('region_code')"
                        v-if="errors.has('region_code')">
                    </span>
                </div>
                @endif

                @if($tipo == 'video')
                <div class="control-group" :class="[errors.has('tipologia') ? 'has-error' : '']" data-available_for="video">
                    <label for="tipologia" class="mandatory">
                        {{ __('shop::app.customer.account.release.create.tipologia') }}
                    </label>

                    
                    <select
                        class="control styled-select"
                        id="tipologia"
                        type="text"
                        name="tipologia"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.tipologia') }}&quot;">
                        <option value="">{{ __('Seleziona tipologia di edizione') }}</option>

                        @foreach ($release_tipologia as $tipologia)
                            <option value="{{ $tipologia->id }}">{{ $tipologia->nome }}</option> 
                        @endforeach
                    </select>

                    <div class="select-icon-container">
                        <span class="select-icon rango-arrow-down"></span>
                    </div>

                    <span
                        class="control-error"
                        v-text="errors.first('tipologia')"
                        v-if="errors.has('tipologia')">
                    </span>
                </div>
                @endif

                @if($tipo == 'video')
                <div class="control-group" :class="[errors.has('canali_audio') ? 'has-error' : '']" data-available_for="video">
                    <label for="canali_audio" class="mandatory">
                        {{ __('shop::app.customer.account.release.create.canali_audio') }}
                    </label>

                    
                    <select
                        class="control styled-select"
                        id="canali_audio"
                        type="text"
                        name="canali_audio"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.canali_audio') }}&quot;">
                        <option value="">{{ __('Seleziona canali audio') }}</option>

                        @foreach ($release_canali_audio as $canali_audio)
                            <option value="{{ $canali_audio->id }}">{{ $canali_audio->nome }}</option> 
                        @endforeach
                    </select>

                    <div class="select-icon-container">
                        <span class="select-icon rango-arrow-down"></span>
                    </div>

                    <span
                        class="control-error"
                        v-text="errors.first('canali_audio')"
                        v-if="errors.has('canali_audio')">
                    </span>
                </div>
                @endif

                @if($tipo == 'video')
                <div class="control-group" data-available_for="video">
                    <label for="subtitles">{{ __('shop::app.customer.account.release.create.subtitles') }}</label>
                    <label class="switch">
                        <input type="hidden" name="subtitles" value="0" @if (old('subtitles')==0) {{ 'checked' }} @endif>
                        <input type="checkbox" id="subtitles" name="subtitles" value="1" @if (old('subtitles')==1)  {{ 'checked' }} @endif>
                        <span class="slider round"></span>
                    </label>
                </div>
                @endif

                @if($tipo == 'video')
                <div class="control-group" :class="[errors.has('durata') ? 'has-error' : '']" data-available_for="video">
                    <label for="durata"  class="mandatory">{{ __('shop::app.customer.account.release.create.durata') }}</label>
                    @php
                    $dt = '';
                    if($selected_master !== false) $dt = $selected_master->master_maintitle;
                    @endphp
                    <input
                        class="control"
                        type="number"
                        name="durata"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.durata') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('durata')"
                        v-if="errors.has('durata')">
                    </span>
                </div>
                @endif

                @if($tipo == 'video')
                <div class="control-group" :class="[errors.has('contenuti_speciali') ? 'has-error' : '']" data-available_for="video">
                    <label for="contenuti_speciali"  class="mandatory">{{ __('shop::app.customer.account.release.create.contenuti_speciali') }}</label>
                    <textarea
                        class="control"
                        style="resize:vertical"
                        rows="5"
                        name="contenuti_speciali"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.contenuti_speciali') }}&quot;"></textarea>

                    <span
                        class="control-error"
                        v-text="errors.first('contenuti_speciali')"
                        v-if="errors.has('contenuti_speciali')">
                    </span>
                </div>
                @endif

                @if($tipo == 'video')
                <div class="control-group" :class="[errors.has('numero_catalogo') ? 'has-error' : '']" data-available_for="video">
                    <label for="numero_catalogo"  class="mandatory">{{ __('shop::app.customer.account.release.create.numero_catalogo') }}</label>
                    <input
                        class="control"
                        type="number"
                        name="numero_catalogo"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.numero_catalogo') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('numero_catalogo')"
                        v-if="errors.has('numero_catalogo')">
                    </span>
                </div>
                @endif

                @if($tipo == 'video')
                <div class="control-group" :class="[errors.has('barcode') ? 'has-error' : '']" data-available_for="video">
                    <label for="barcode"  class="mandatory">{{ __('shop::app.customer.account.release.create.barcode') }}</label>
                    <input
                        class="control"
                        type="number"
                        name="barcode"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.barcode') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('barcode')"
                        v-if="errors.has('barcode')">
                    </span>
                </div>
                @endif

                @if($tipo == 'video')
                <div class="control-group" :class="[errors.has('crediti') ? 'has-error' : '']" data-available_for="video">
                    <label for="crediti"  class="mandatory">{{ __('shop::app.customer.account.release.create.crediti') }}</label>
                    <textarea
                        class="control"
                        style="resize:vertical"
                        rows="5"
                        name="crediti"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.crediti') }}&quot;"></textarea>

                    <span
                        class="control-error"
                        v-text="errors.first('crediti')"
                        v-if="errors.has('crediti')">
                    </span>
                </div>
                @endif

                @if($tipo == 'video')
                <div class="control-group" :class="[errors.has('release_description') ? 'has-error' : '']" data-available_for="video">
                    <label for="release_description"  class="mandatory">{{ __('shop::app.customer.account.release.create.release_description') }}</label>
                    <textarea
                        class="control"
                        style="resize:vertical"
                        rows="5"
                        name="release_description"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_description') }}&quot;"></textarea>

                    <span
                        class="control-error"
                        v-text="errors.first('release_description')"
                        v-if="errors.has('release_description')">
                    </span>
                </div>
                @endif
                <!-- END PWS#02-23 -->
                @if($tipo == 'poster')
                <div class="control-group" :class="[errors.has('poster_tipo') ? 'has-error' : '']" data-available_for="poster">
                    <label for="poster_tipo"  class="mandatory">{{ __('shop::app.customer.account.release.create.poster_tipo') }}</label>
                    <select
                        class="control styled-select"
                        id="poster_tipo"
                        type="text"
                        name="poster_tipo"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.poster_tipo') }}&quot;">
                        <option value="">{{ __('shop::app.customer.account.release.create.select_poster_tipo') }}</option>

                        @foreach ($release_poster_tipo as $rpt)
                            <option value="{{ $rpt->id }}">{{ $rpt->nome }}</option> 
                        @endforeach
                    </select>

                    <div class="select-icon-container">
                        <span class="select-icon rango-arrow-down"></span>
                    </div>
                </div>
                @endif

                @if($tipo == 'poster')
                <div class="control-group" :class="[errors.has('poster_formato') ? 'has-error' : '']" data-available_for="poster">
                    <label for="poster_formato"  class="mandatory">{{ __('shop::app.customer.account.release.create.poster_formato') }}</label>
                    <select
                        class="control styled-select"
                        id="poster_formato"
                        type="text"
                        name="poster_formato"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.poster_formato') }}&quot;">
                        <option value="">{{ __('shop::app.customer.account.release.create.select_poster_formato') }}</option>

                        @foreach ($release_poster_formato as $rpf)
                            <option value="{{ $rpf->id }}">{{ $rpf->nome }}</option> 
                        @endforeach
                    </select>

                    <div class="select-icon-container">
                        <span class="select-icon rango-arrow-down"></span>
                    </div>
                </div>
                @endif

                @if($tipo == 'poster')
                <div class="control-group" :class="[errors.has('poster_misure') ? 'has-error' : '']" data-available_for="poster">
                    <label for="poster_misure"  class="mandatory">{{ __('shop::app.customer.account.release.create.poster_misure') }}</label>
                    <select
                        class="control styled-select"
                        id="poster_misure"
                        type="text"
                        name="poster_misure"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.poster_misure') }}&quot;">
                        <option value="">{{ __('shop::app.customer.account.release.create.select_poster_misure') }}</option>

                        @foreach ($release_poster_misure as $rpm)
                            <option value="{{ $rpm->id }}">{{ $rpm->nome }} [{{$rpm->descrizione}}]</option> 
                        @endforeach
                    </select>

                    <div class="select-icon-container">
                        <span class="select-icon rango-arrow-down"></span>
                    </div>
                </div>
                @endif

                @if($tipo == 'poster')
                <div class="control-group" :class="[errors.has('illustratore') ? 'has-error' : '']">
                    <label for="illustratore"  class="mandatory">{{ __('shop::app.customer.account.release.create.illustratore') }}</label>
                    <input
                        class="control"
                        type="text"
                        name="illustratore"
                        value="{{ $dt }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.illustratore') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('illustratore')"
                        v-if="errors.has('illustratore')">
                    </span>
                </div>
                @endif

                @if($tipo == 'poster')
                <div class="control-group" :class="[errors.has('stampatore') ? 'has-error' : '']">
                    <label for="stampatore"  class="mandatory">{{ __('shop::app.customer.account.release.create.stampatore') }}</label>
                    <input
                        class="control"
                        type="text"
                        name="stampatore"
                        value="{{ $dt }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.stampatore') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('stampatore')"
                        v-if="errors.has('stampatore')">
                    </span>
                </div>
                @endif
                <!-- START PWS#video-poster -->

                <!-- END PWS#video-poster -->

                {!! view_render_event('bagisto.shop.customers.account.release.edit_form_controls.language.after') !!}

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.after') !!}


                <div class="button-group">
                    <button class="theme-btn" type="submit">
                        {{ __('shop::app.customer.account.release.create.submit') }}
                    </button>
                </div>
            </div>
        </form>
    @else
        <div>
            <h2>Si è verificato un errore.</h2>
        </div>
    @endif
    {!! view_render_event('bagisto.shop.customers.account.release.create.after') !!}
@endsection

@push('scripts')
<script>
    history.pushState(null, "", location.href.split("?")[0]); // toglie eventuali query parameters

    $(document).on("change", "#cerca_release input[name='tipo']", function(){
        tipoVideo = 'tipo-video';
        tipoPoster = 'tipo-poster';
        isChecked = $(this).is(':checked');

        if($(this).attr('id') == tipoVideo && isChecked){
            $('#cerca_release .cerca.video').fadeIn();
            $('#cerca_release .cerca.video input').prop('disabled',false);
            $('#cerca_release .cerca.poster').hide();
            $('#cerca_release .cerca.poster input').prop('disabled',true);
            $('#cerca_release .btn-submit').fadeIn();
        } else{
            $('#cerca_release .cerca.video').hide();
            $('#cerca_release .cerca.video input').prop('disabled',true);
            $('#cerca_release .cerca.poster').fadeIn();
            $('#cerca_release .cerca.poster input').prop('disabled',false);
            $('#cerca_release .btn-submit').fadeIn();
        }
    });

    $(document).on("click", "#cerca_release .btn-submit .theme-btn", function(e){
        tipo = $('input[name="tipo"]:checked').val();
        $('#tipo').val(tipo);
        numero_catalogo = $('input[name="numero_catalogo"]').val();
        titolo = $('input[name="original_title"]').val();

        if(tipo == 'video' && (!numero_catalogo || numero_catalogo.length < 3)){
            $('#cerca_release .cerca.video').addClass("error");
        } else if(tipo == 'poster' && (!titolo || titolo.length < 3)){
            $('#cerca_release .cerca.poster').addClass("error");
        } else if(tipo != '' && (numero_catalogo || titolo)){
            // $('#cerca_release').submit();
            numero_catalogo = $('#numero_catalogo').val();
            titolo = $('#original_title').val();
            getReleases();
        }
    });

    $(document).on("click", "#crea_nuovo .btn-submit .theme-btn", function(e){
        $('#crea_nuovo').submit();
    });

    $(document).on("click", ".pagination a", function(e){
        e.preventDefault();
        getReleases($(this).attr('href').split('page=')[1]);
    });

    function getReleases(page = 1){
        $.ajax({
            type: 'POST',
            url: '{{ route('customer.release.create') }}?page=' + page,
            dataType: 'json',
            data: {
                _token: '{{ csrf_token() }}',
                action: 'search',
                tipo: tipo,
                numero_catalogo: numero_catalogo,
                titolo: titolo
            },
            success: function(data) {
                if(data.success){
                    releases = data.releases;
                    $('.results.releases').html(releases);

                    $('.step_1').fadeOut('slow');
                    $('.step_1').promise().done(function(){
                        $('.step_2').fadeIn();
                    });

                } else{
                    console.log(data);
                    window.location.reload();
                }
            }
        });
    }

    function addToCollection(element_clicked, element_id){ // PWS#related-release-2
        if(config != undefined && config.routes != undefined && config.params != undefined && config.params.user_id != -1 && $('meta[name="_token"]').length > 0){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                url: config.routes.addToCollection,
                method: 'post',
                data: {
                    element_id: element_id,
                    tipo: 'collection',
                    table: 'release',
                },
                success: function(result){
                    if(result.response){
                        window.location.href = "{{ route('customer.release.index'); }}";
                    }
                }
            });
        }
    }
</script>
<script>
    var config = {
        routes: {
            addToCollection: "{{ route('customer.addToCollection') }}"
        },
        params: {
          user_id: @php if(Auth::check()){ echo Auth::id(); } else{ echo -1; } @endphp,
          table: 'release',
        }
    };
</script>
@endpush