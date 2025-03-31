@php
$currentCustomer = auth()->guard('customer')->user();

$is_poster = (int) $release->releasetype == config('constants.release.tipo.poster');

@endphp
@php
// $currentCustomer = auth()->guard('customer')->user();
// echo '<pre>';print_r($release);
//  die();
@endphp
@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cinema.release.update-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.cinema.release.update', $release->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.cinema.release.index') }}'"></i>
                        {{ __('admin::app.cinema.release.update-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.cinema.release.update-btn-title') }}
                    </button>
                </div>
            </div>
            <div class="page-content">
                @csrf()

                <input name="_method" type="hidden" value="PUT">
               {!! view_render_event('bagisto.admin.cinema.release.images.before', ['release' => $release]) !!}

                <accordian title="{{ __('admin::app.cinema.release.images') }}" :active="false">
                    <div slot="body">
                        {!! view_render_event('bagisto.admin.cinema.release.images.controls.before', ['release' => $release]) !!}

                        <div class="control-group {{ $errors->has('images.files.*') ? 'has-error' : '' }}">
                            <label>{{ __('admin::app.cinema.release.image') }}</label>

                            <release-image></release-image>

                            <span
                                class="control-error"
                                v-text="'{{ $errors->first('images.files.*') }}'">
                            </span>

                            <span class="control-info mt-10">{{ __('admin::app.cinema.release.image-size') }}</span>
                        </div>

                        {!! view_render_event('bagisto.admin.cinema.release.images.controls.after', ['release' => $release]) !!}
                    </div>
                </accordian>

                {!! view_render_event('bagisto.admin.cinema.release.images.after', ['release' => $release]) !!}

                @push('scripts')
                    <script type="text/x-template" id="release-image-template">
                        <div>
                            <div class="image-wrapper">
                                <draggable v-model="items" group="people" @end="onDragEnd">
                                    <release-image-item
                                        v-for='(image, index) in items'
                                        :key='image.id'
                                        :image="image"
                                        class="draggable"
                                        @onRemoveImage="removeImage($event)"
                                        @onImageSelected="imageSelected($event)">
                                    </release-image-item>
                                </draggable>
                            </div>

                            <label class="btn btn-lg btn-primary" style="display: table; width: auto" @click="createFileType">
                                {{ __('admin::app.cinema.release.add-image-btn-title') }}
                            </label>
                        </div>
                    </script>

                    <script type="text/x-template" id="release-image-item-template">
                        <label class="image-item" v-bind:class="{ 'has-image': imageData.length > 0 }">
                            <input
                                type="hidden"
                                :name="'images[files][' + image.id + ']'"
                                v-if="! new_image"/>

                            <input
                                type="hidden"
                                :name="'images[positions][' + image.id + ']'"/>

                            <input
                                :id="_uid"
                                ref="imageInput"
                                type="file"
                                name="images[files][]"
                                accept="image/*"
                                multiple="multiple"
                                v-validate="'mimes:image/*'"
                                @change="addImageView($event)"/>

                            <img
                                class="preview"
                                :src="imageData"
                                v-if="imageData.length > 0">

                            <label class="remove-image" @click="removeImage()">
                                {{ __('admin::app.cinema.release.remove-image-btn-title') }}
                            </label>
                        </label>
                    </script>

                    <script>
                        Vue.component('release-image', {
                            template: '#release-image-template',

                            data: function() {
                                return {
                                    images: @json($release->images),

                                    imageCount: 0,

                                    items: [],
                                }
                            },

                            computed: {
                                finalInputName: function() {
                                    return 'images[' + this.image.id + ']';
                                }
                            },

                            created: function() {
                                let self = this;

                                this.images.forEach(function(image) {
                                    self.items.push(image)

                                    self.imageCount++;
                                });
                            },

                            methods: {
                                createFileType: function() {
                                    let self = this;

                                    this.imageCount++;

                                    this.items.push({'id': 'image_' + this.imageCount});
                                },

                                removeImage: function(image) {
                                    let index = this.items.indexOf(image)

                                    Vue.delete(this.items, index);
                                },

                                imageSelected: function(event) {
                                    let self = this;

                                    Array.from(event.files).forEach(function(image, index) {
                                        if (index) {
                                            self.imageCount++;

                                            self.items.push({'id': 'image_' + self.imageCount, file: image});
                                        }
                                    });
                                },

                                onDragEnd: function() {
                                    this.items = this.items.map((item, index) => {
                                        item.position = index;

                                        return item;
                                    });
                                },
                            }
                        });

                        Vue.component('release-image-item', {
                            template: '#release-image-item-template',

                            props: {
                                image: {
                                    type: Object,
                                    required: false,
                                    default: null
                                },
                            },

                            data: function() {
                                return {
                                    imageData: '',

                                    new_image: 0
                                }
                            },

                            mounted () {
                                if (this.image.id && this.image.url) {
                                    this.imageData = this.image.url;
                                } else if (this.image.id && this.image.file) {
                                    this.readFile(this.image.file);
                                }
                            },

                            computed: {
                                finalInputName: function() {
                                    return this.inputName + '[' + this.image.id + ']';
                                }
                            },

                            methods: {
                                addImageView: function() {
                                    let imageInput = this.$refs.imageInput;

                                    if (imageInput.files && imageInput.files[0]) {
                                        if (imageInput.files[0].type.includes('image/')) {
                                            this.readFile(imageInput.files[0])

                                            if (imageInput.files.length > 1) {
                                                this.$emit('onImageSelected', imageInput)
                                            }
                                        } else {
                                            imageInput.value = "";

                                            alert('Only images (.jpeg, .jpg, .png, ..) are allowed.');
                                        }
                                    }
                                },

                                readFile: function(image) {
                                    let reader = new FileReader();

                                    reader.onload = (e) => {
                                        this.imageData = e.target.result;
                                    }

                                    reader.readAsDataURL(image);

                                    this.new_image = 1;
                                },

                                removeImage: function() {
                                    this.$emit('onRemoveImage', this.image)
                                }
                            }
                        });
                    </script>
                @endpush






                <div class="form-container">
                    @method('PUT')

                 @csrf
                 {!! view_render_event('bagisto.admin.cinema.release.edit_form_controls.before', ['release' => $release]) !!}

                    <input type="hidden" name="locale" value="all"/>
                    {!! view_render_event('bagisto.admin.cinema.release.create_form_accordian.general.before') !!}

                    <accordian title="{{ __('admin::app.cinema.release.general') }}" :active="true">


                        <div slot="body">
                            {!! view_render_event('bagisto.admin.cinema.release.create_form_accordian.general.controls.before') !!}



                      <div class="control-group" :class="[errors.has('original_title') ? 'has-error' : '']">
				                <label for="original_title" class="mandatory required">{{ __('admin::app.cinema.release.original_title') }}</label>

				                <input
				                    readonly
				                    class="control"
				                    type="text"
				                    name="original_title"
				                    v-validate="'required'"
				                    value="{{ old('original_title') ?? $release->original_title }}"
				                    data-vv-as="&quot;{{ __('admin::app.cinema.release.original_title') }}&quot;">

				                <span
				                    class="control-error"
				                    v-text="errors.first('original_title')"
				                    v-if="errors.has('original_title')">
				                </span>
				            </div>

                    <div class="control-group" :class="[errors.has('master_id') ? 'has-error' : '']">
                     <label for="master_id" class="mandatory required">{{ __('admin::app.cinema.release.master_id') }}</label>

                     <select  data-mdb-filter="true" class="control js-example-basic-multiple" v-validate="'required'" id="master_id" name="master_id" data-vv-as="&quot;{{ __('admin::app.cinema.release.master_id') }}&quot;">
                         {{-- <option value="">Seleziona Master</option> --}}
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
                                <div class="control-group" :class="[errors.has('url_key') ? 'has-error' : '']">
            <label for="url-key" >{{ __('admin::app.cinema.release.url-key') }}</label>
            <input readonly id="url_key" type="text" class="control" name="url_key"  value="{{ old('url_key')  ?? $release->url_key}}" data-vv-as="&quot;{{ __('admin::app.cinema.release.url-key') }}&quot;" >
            <span class="control-error" v-if="errors.has('url_key')">@{{ errors.first('url_key') }}</span>
        </div>
        <div class="control-group" :class="[errors.has('release_year') ? 'has-error' : '']">
                <label for="release_year" class="mandatory required">{{ __('admin::app.cinema.release.release_year') }}</label>

                <input
                    class="control"
                    type="text"
                    name="release_year"
                    value="{{ old('release_year') ?? $release->release_year }}"
                    v-validate="'required'"
                    data-vv-as="&quot;{{ __('admin::app.cinema.release.release_year') }}&quot;">

                <span
                    class="control-error"
                    v-text="errors.first('release_year')"
                    v-if="errors.has('release_year')">
                </span>
            </div>
     <div class="control-group" :class="[errors.has('release_distribution') ? 'has-error' : '']">
                <label for="release_distribution">{{ __('admin::app.cinema.release.release_distribution') }}
                </label>

                <input
                    class="control"
                    type="text"
                    name="release_distribution"
                    value="{{ old('release_distribution') ?? $release->release_distribution }}"
                    v-validate=""
                    data-vv-as="&quot;{{ __('admin::app.cinema.release.release_distribution') }}&quot;">

                <span
                    class="control-error"
                    v-text="errors.first('release_distribution')"
                    v-if="errors.has('release_distribution')">
                </span>
            </div>



                            @include ('shop::cinema.release.country-state', ['countryCode' => old('country') ?? $release->country])
<div class="control-group" :class="[errors.has('language') ? 'has-error' : '']">
                <label for="language " class="required">{{ __('admin::app.cinema.release.language') }}</label>
                <select class="control js-example-basic-multiple" v-validate="'required'" id="language" name="language[]" data-vv-as="&quot;{{ __('admin::app.cinema.release.language') }}&quot;" multiple="multiple"> <!-- PWS#chiusura-langmulti -->
                    @foreach ($language as $gen)
                        <option {{ in_array($gen->id,$lingue_selezionate) ? 'selected' : ''  }}  value="{{ $gen->id }}">{{ $gen->name }}</option> <!-- PWS#chiusura -->
                    @endforeach
                </select>
            </div>
        {{--<div class="control-group">
            <label for="release_vt18">{{ __('admin::app.cinema.release.release_vt18') }}</label>
            <label class="switch">
                <input type="hidden" name="release_vt18" value="0" @if ((int) $release->release_vt18==0) {{ 'checked' }} @endif>
                <input type="checkbox" id="release_vt18" name="release_vt18" value="1" @if ((int) $release->release_vt18==1) {{ 'checked' }} @endif> <!-- PWS#releasedatagrid -->

                <span class="slider round"></span>
            </label>
        </div>--}}
        <div class="control-group" :class="[errors.has('release_status') ? 'has-error' : '']">
            <label for="release_status " class="required">{{ __('admin::app.cinema.release.release_status') }}</label>
            <select class="control js-example-basic-multiple" v-validate="'required'" id="release_status" name="release_status" data-vv-as="&quot;{{ __('admin::app.cinema.release.release_status') }}&quot;">
                <option {{ -1 == (int) $release->release_status ? 'selected' : ''  }}  value="-1">{{ __('admin::app.cinema.release.release_status_pending') }}</option>
                <option {{ 0 == (int) $release->release_status ? 'selected' : ''  }}  value="0">{{ __('admin::app.cinema.release.release_status_rifiutato') }}</option>
                <option {{ 1 == (int) $release->release_status ? 'selected' : ''  }}  value="1">{{ __('admin::app.cinema.release.release_status_approvato') }}</option>
            </select>
        </div> <!-- PWS#13-status -->
        {{--<div class="control-group" :class="[errors.has('releasetype') ? 'has-error' : '']">
            <label for="releasetype " class="required">{{ __('admin::app.cinema.release.releasetype') }}</label>
            <select class="control js-example-basic-multiple" v-validate="'required'" id="releasetype" name="releasetype" data-vv-as="&quot;{{ __('admin::app.cinema.release.releasetype') }}&quot;">
                <option {{ 'poster' == $release->releasetype ? 'selected' : ''  }}  value="poster">Poster</option>
                <option {{ 'video' == $release->releasetype ? 'selected' : ''  }}  value="video">Video</option>

                 @foreach ($language as $gen)
                    <option {{ $gen->id == $release->releasetype ? 'selected' : ''  }}  value="{{ $releasetype->id }}">{{ $releasetype->name }}</option>
                @endforeach
            </select>
        </div>--}}

        <input type="hidden" name="releasetype" id="releasetype" value="{{ $release->releasetype }}"> <!-- PWS#rel-edit -->

        <!-- PWS#video-poster-2 -->
        @if(!$is_poster)
            <div class="control-group" :class="[errors.has('formato') ? 'has-error' : '']" data-available_for="video">
                <label for="formato" class="mandatory required">
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
                        <option value="{{ $formato->id }}" {{ $formato->id==$release->formato?'selected':'' }}>{{ $formato->nome }}</option>
                    @endforeach
                </select>

                <span
                    class="control-error"
                    v-text="errors.first('formato')"
                    v-if="errors.has('formato')">
                </span>
            </div>
        @endif

        @if(!$is_poster)
        <div class="control-group" :class="[errors.has('aspect_ratio') ? 'has-error' : '']" data-available_for="video">
            <label for="aspect_ratio" class="mandatory required">
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
                    <option value="{{ $aspect_ratio->id }}" {{ $aspect_ratio->id==$release->aspect_ratio?'selected':'' }}>{{ $aspect_ratio->nome }}</option>
                @endforeach
            </select>

            <span
                class="control-error"
                v-text="errors.first('aspect_ratio')"
                v-if="errors.has('aspect_ratio')">
            </span>
        </div>
        @endif

        @if(!$is_poster)
        <div class="control-group" :class="[errors.has('camera_format') ? 'has-error' : '']" data-available_for="video">
            <label for="camera_format" class="mandatory required">
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
                    <option value="{{ $camera_format->id }}" {{ $camera_format->id==$release->camera_format?'selected':'' }}>{{ $camera_format->nome }}</option>
                @endforeach
            </select>

            <span
                class="control-error"
                v-text="errors.first('camera_format')"
                v-if="errors.has('camera_format')">
            </span>
        </div>
        @endif

        @if(!$is_poster)
        <div class="control-group" :class="[errors.has('region_code') ? 'has-error' : '']" data-available_for="video">
            <label for="region_code" class="mandatory required">
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
                    <option value="{{ $region_code->id }}" {{ $region_code->id==$release->region_code?'selected':'' }}>{{ $region_code->nome }}</option>
                @endforeach
            </select>

            <span
                class="control-error"
                v-text="errors.first('region_code')"
                v-if="errors.has('region_code')">
            </span>
        </div>
        @endif

        @if(!$is_poster)
        <div class="control-group" :class="[errors.has('tipologia') ? 'has-error' : '']" data-available_for="video">
            <label for="tipologia" class="mandatory required">
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
                    <option value="{{ $tipologia->id }}" {{ $tipologia->id==$release->tipologia?'selected':'' }}>{{ $tipologia->nome }}</option>
                @endforeach
            </select>

            <span
                class="control-error"
                v-text="errors.first('tipologia')"
                v-if="errors.has('tipologia')">
            </span>
        </div>
        @endif

        @if(!$is_poster)
        <div class="control-group" :class="[errors.has('canali_audio') ? 'has-error' : '']" data-available_for="video">
            <label for="canali_audio" class="mandatory required">
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
                    <option value="{{ $canali_audio->id }}" {{ $canali_audio->id==$release->canali_audio?'selected':'' }}>{{ $canali_audio->nome }}</option>
                @endforeach
            </select>

            <span
                class="control-error"
                v-text="errors.first('canali_audio')"
                v-if="errors.has('canali_audio')">
            </span>
        </div>
        @endif

        @if(!$is_poster)
        <div class="control-group" data-available_for="video">
            <label for="subtitles">{{ __('shop::app.customer.account.release.create.subtitles') }}</label>
            <label class="switch">
                <input type="hidden" name="subtitles" value="0" @if ($release->subtitles==0) {{ 'checked' }} @endif>
                <input type="checkbox" id="subtitles" name="subtitles" value="1" @if ($release->subtitles==1)  {{ 'checked' }} @endif>
                <span class="slider round"></span>
            </label>
        </div>
        @endif

        @if(!$is_poster)
        <div class="control-group" :class="[errors.has('durata') ? 'has-error' : '']" data-available_for="video">
            <label for="durata"  class="mandatory required">{{ __('shop::app.customer.account.release.create.durata') }}</label>
            <input
                class="control"
                type="number"
                name="durata"
                v-validate="'required'"
                data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.durata') }}&quot;"
                value="{{ $release->durata }}">

            <span
                class="control-error"
                v-text="errors.first('durata')"
                v-if="errors.has('durata')">
            </span>
        </div>
        @endif

        @if(!$is_poster)
        <div class="control-group" :class="[errors.has('contenuti_speciali') ? 'has-error' : '']" data-available_for="video">
            <label for="contenuti_speciali"  class="mandatory required">{{ __('shop::app.customer.account.release.create.contenuti_speciali') }}</label>
            <textarea
                class="control"
                name="contenuti_speciali"
                v-validate="'required'"
                data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.contenuti_speciali') }}&quot;">{{ $release->contenuti_speciali }}</textarea>

            <span
                class="control-error"
                v-text="errors.first('contenuti_speciali')"
                v-if="errors.has('contenuti_speciali')">
            </span>
        </div>
        @endif

        @if(!$is_poster)
        <div class="control-group" :class="[errors.has('numero_catalogo') ? 'has-error' : '']" data-available_for="video">
            <label for="numero_catalogo"  class="">{{ __('shop::app.customer.account.release.create.numero_catalogo') }}</label>
            <input
                class="control"
                type="text"
                name="numero_catalogo"
                data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.numero_catalogo') }}&quot;"
                value="{{ $release->numero_catalogo }}"> <!-- PWS#chiusura -->

            <span
                class="control-error"
                v-text="errors.first('numero_catalogo')"
                v-if="errors.has('numero_catalogo')">
            </span>
        </div>
        @endif

        @if(!$is_poster)
        <div class="control-group" :class="[errors.has('barcode') ? 'has-error' : '']" data-available_for="video">
            <label for="barcode"  class="">{{ __('shop::app.customer.account.release.create.barcode') }}</label>
            <input
                class="control"
                type="number"
                name="barcode"
                data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.barcode') }}&quot;"
                value="{{ $release->barcode }}"> <!-- PWS#chiusura -->

            <span
                class="control-error"
                v-text="errors.first('barcode')"
                v-if="errors.has('barcode')">
            </span>
        </div>
        @endif

        @if(!$is_poster)
        <div class="control-group" :class="[errors.has('crediti') ? 'has-error' : '']" data-available_for="video">
            <label for="crediti"  class="">{{ __('shop::app.customer.account.release.create.crediti') }}</label>
            <input
                class="control"
                type="text"
                name="crediti"
                data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.crediti') }}&quot;"
                value="{{ $release->crediti }}"> <!-- PWS#chiusura -->

            <span
                class="control-error"
                v-text="errors.first('crediti')"
                v-if="errors.has('crediti')">
            </span>
        </div>
        @endif

        <!-- END PWS#02-23 -->
        @if($is_poster)
        <div class="control-group" :class="[errors.has('poster_tipo') ? 'has-error' : '']" data-available_for="poster">
            <label for="poster_tipo"  class="mandatory required">{{ __('shop::app.customer.account.release.create.poster_tipo') }}</label>
            <select
                class="control styled-select"
                id="poster_tipo"
                type="text"
                name="poster_tipo"
                v-validate="'required'"
                data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.poster_tipo') }}&quot;">
                <option value="">{{ __('shop::app.customer.account.release.create.select_poster_tipo') }}</option>

                @foreach ($release_poster_tipo as $rpt)
                    <option value="{{ $rpt->id }}" {{ $rpt->id==$release->poster_tipo?'selected':'' }}>{{ $rpt->nome }}</option>
                @endforeach
            </select>
        </div>
        @endif

        @if($is_poster)
        <div class="control-group" :class="[errors.has('poster_formato') ? 'has-error' : '']" data-available_for="poster">
            <label for="poster_formato"  class="mandatory required">{{ __('shop::app.customer.account.release.create.poster_formato') }}</label>
            <select
                class="control styled-select"
                id="poster_formato"
                type="text"
                name="poster_formato"
                v-validate="'required'"
                data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.poster_formato') }}&quot;">
                <option value="">{{ __('shop::app.customer.account.release.create.select_poster_formato') }}</option>

                @foreach ($release_poster_formato as $rpf)
                    <option value="{{ $rpf->id }}" {{ $rpf->id==$release->poster_formato?'selected':'' }}>{{ $rpf->nome }}</option>
                @endforeach
            </select>
        </div>
        @endif

        @if($is_poster)
        <div class="control-group" :class="[errors.has('poster_misure') ? 'has-error' : '']" data-available_for="poster">
            <label for="poster_misure"  class="mandatory required">{{ __('shop::app.customer.account.release.create.poster_misure') }}</label>
            <select
                class="control styled-select"
                id="poster_misure"
                type="text"
                name="poster_misure"
                v-validate="'required'"
                data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.poster_misure') }}&quot;">
                <option value="">{{ __('shop::app.customer.account.release.create.select_poster_misure') }}</option>

                @foreach ($release_poster_misure as $rpm)
                    <option value="{{ $rpm->id }}" {{ $rpm->id==$release->poster_misure?'selected':'' }}>{{ $rpm->nome }} [{{$rpm->descrizione}}]</option>
                @endforeach
            </select>
        </div>
        @endif

        @if($is_poster)
        <div class="control-group" :class="[errors.has('illustratore') ? 'has-error' : '']">
            <label for="illustratore"  class="mandatory required">{{ __('shop::app.customer.account.release.create.illustratore') }}</label>
            <input
                class="control"
                type="text"
                name="illustratore"
                value="{{ $release->illustratore }}"
                v-validate="'required'"
                data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.illustratore') }}&quot;">

            <span
                class="control-error"
                v-text="errors.first('illustratore')"
                v-if="errors.has('illustratore')">
            </span>
        </div>
        @endif

        @if($is_poster)
        <div class="control-group" :class="[errors.has('stampatore') ? 'has-error' : '']">
            <label for="stampatore"  class="mandatory required">{{ __('shop::app.customer.account.release.create.stampatore') }}</label>
            <input
                class="control"
                type="text"
                name="stampatore"
                value="{{ $release->stampatore }}"
                v-validate="'required'"
                data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.stampatore') }}&quot;">

            <span
                class="control-error"
                v-text="errors.first('stampatore')"
                v-if="errors.has('stampatore')">
            </span>
        </div>
        @endif



                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.controls.after') !!}
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.after') !!}



                </div>
                {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.description.before', ['release' => $release]) !!}

                <accordian title="DESCRIZIONE" :active="false">
                    <div slot="body">
                        {!! view_render_event('bagisto.admin.cinema.release.edit_form_accordian.description.controls.before', ['release' => $release]) !!}
                        <div>
                            <div class="control-group textarea">
                                <label for="short_description" >Short Description</label>
                                <textarea  id="short_description" name="short_description" data-vv-as="&quot;Short Description&quot;" class="control " aria-required="true" aria-invalid="true">{{ $release->short_description }}</textarea>
                            </div>
                        <div class="control-group textarea ">
                            <label for="release_description" >Description</label>
                            <textarea  id="release_description" name="release_description" data-vv-as="&quot;Description&quot;" class="control " aria-required="true" aria-invalid="true">{{ $release->release_description }}</textarea>
                        </div>
                        <div class="control-group textarea ">
                            <label for="release_note" >Note</label>
                            <textarea  id="release_note" name="release_note" data-vv-as="Note" class="control " aria-required="true" aria-invalid="true">{{ $release->release_note }}</textarea>
                        </div>
                    </div>

                        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.description.controls.after', ['release' => $release]) !!}
                    </div>
                </accordian>
                <accordian title="META DESCRIPTION" :active="false">
            <div slot="body">
             {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.description.controls.before', ['release' => $release]) !!}

             <div>
                <div class="control-group textarea ">
                    <label for="meta_title">Meta Title</label>
                    <textarea  id="meta_title" name="meta_title" data-vv-as="&quot;Meta Title&quot;" class="control " aria-required="false" aria-invalid="false">{{ $release->meta_title }}</textarea> <!---->
                </div>
                <div class="control-group textarea "><label for="meta_keywords">Meta Keywords</label>
                    <textarea  id="meta_keywords" name="meta_keywords" data-vv-as="&quot;Meta Keywords&quot;" class="control " aria-required="false" aria-invalid="false">{{ $release->meta_keywords }}</textarea> <!---->
                </div>
                <div class="control-group textarea ">
                    <label for="meta_description">Meta Description</label>
                    <textarea  id="meta_description" name="meta_description" data-vv-as="&quot;Meta Description&quot;" class="control " aria-required="false" aria-invalid="false">{{ $release->meta_description }}</textarea> <!---->
                </div>
            </div>

             {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.description.controls.after', ['release' => $release]) !!}
         </div>
     </accordian>

                {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.description.after', ['release' => $release]) !!}

            </div>
        </form>
    </div>
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.4/jquery.rateyo.min.js" integrity="sha512-09bUVOnphTvb854qSgkpY/UGKLW9w7ISXGrN0FR/QdXTkjs0D+EfMFMTB+CGiIYvBoFXexYwGUD5FD8xVU89mw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script>
        $(document).ready(function() {

            $( "#signup" ).click( function() {
                alert("cliccato");
                 $( "#form1" ).toggle( 'slow' );
            });

            $('.js-example-basic-single').select2();
            $('.js-example-basic-multiple').select2();
            $('.js-data-example-ajax').select2({
                ajax: {
                    url: 'https://api.github.com/search/repositories',
                    dataType: 'json'
                    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                }
                });
        });
        Vue.component('date-picker', {
            template: '<input/>',
            props: ['dateFormat', 'startDate', 'endDate', 'defaultDate'],
            data() {
                return {
                    innerValue: this.defaultDate
                }
            },
            mounted: function() {
                var vm = this;
                $(this.$el).datepicker({
                    autoclose: true,
                    format: this.dateFormat,
                    date: this.defaultDate,
                    startDate: this.startDate,
                    endDate: this.endDate
                })
                .on("changeDate", function(e) {
                    vm.innerValue = e.format();
                });
            },
            methods: {
                clearData() {
                    this.innerValue = [];
                }
            },
            watch: {
                  innerValue (val) {
                    this.$emit('input', val);
                  },
            },
            beforeDestroy: function() {
                $(this.$el).datepicker('hide').datepicker('destroy');
            }
        });

        Vue.component('rating', {
            inject: ['$validator'],
            template: '<div/>',
            props: {rating:0, min:0, max:5, target:''},
            mounted: function() {
                var vm = this;
                $(this.$el).rateYo({
                    rating: this.rating,
                    numStars: this.max,
                    minValue: this.min,
                    maxValue: this.max,
                    fullStar: true,
                    spacing: "5px",
                    starWidth: "20px",
                    onSet: function (rating, rateYoInstance) {
                        $('input[name="'+vm.target+'"]').val(rating);
                        vm.$validator.validate(vm.target);
                    }
                });
            },
            beforeDestroy: function() {
                $(this.$el).rateYo('destroy');
            }
        });

        Vue.component('select2', {
            template: '<select><slot/></select>',
            props: ['options', 'value', 'placeholder'],
            data() {
                return {
                    innerValue: this.value
                }
            },
            methods: {
                clearData() {
                    this.innerValue = [];
                    $(this.$el).val([]).trigger('change');
                }
            },
            mounted: function() {
                var vm = this;
                $(this.$el)
                    .select2({
                        data: this.options,
                        placeholder: this.placeholder
                    })
                    .val(this.value)
                    .trigger("change")
                    .on("select2:select select2:unselect", function() {
                        vm.innerValue = $(this).val();
                      });
            },
            watch: {
                options: function(options) {
                    $(this.$el).select2({
                        data: options,
                        placeholder: this.placeholder
                    });
                  },
                  innerValue (val) {
                    this.$emit('input', val);
                  },
            },
            beforeDestroy: function() {
                $(this.$el).off().select2('destroy');
            }
        });

        function resetFBForm() {
            app.$validator.pause();
            app.$validator.errors.clear();

            app.$validator.fields['items'].forEach((field) => {
                if (field.componentInstance != undefined) {
                    field.componentInstance.clearData();
                }
            });

            if ($('.rateyo').length > 0) {
                $('.rateyo').rateYo("rating", 0);
            }

            setTimeout(function() {
                app.$validator.resume();
            }, 50);

            return true;
        }
    </script>
@endpush
@stop
