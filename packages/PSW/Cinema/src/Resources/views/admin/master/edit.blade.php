@php
$currentCustomer = auth()->guard('customer')->user();
@endphp
@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cinema.master.update-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.cinema.master.update', $master->master_id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.cinema.master.index') }}'"></i>
                        {{ __('admin::app.cinema.master.update-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.cinema.master.update-btn-title') }}
                    </button>
                </div>
            </div>
            <div class="page-content">
                @csrf()

                <input name="_method" type="hidden" value="PUT">
                {{-- {!! view_render_event('bagisto.admin.cinema.product.images.before', ['master' => $master] !!}

                <accordian title="images" :active="false">
                    <div slot="body">
                        {!! view_render_event('bagisto.admin.cinema.master.images.controls.before', ['master' => $master] !!}

                            <?php

                                // $validations = [];

                                //     $retVal = (core()->getConfigData('cinema.products.attribute.image_attribute_upload_size')) ? core()->getConfigData('cinema.products.attribute.image_attribute_upload_size') : '2048';

                                //     array_push($validations, 'size:' . $retVal . '|mimes:bmp,jpeg,jpg,png,webp');

                                // array_push($validations, $attribute->validation);

                                // $validations = implode('|', array_filter($validations));

                            ?>
                                <div class="control-group" >

                                    <label>Image</label>



                                    <span class="control-error"></span>
                                </div>

                        {!! view_render_event('bagisto.admin.cinema.master.images.controls.after', ['master' => $master] !!}
                    </div>
                </accordian>

                {!! view_render_event('bagisto.admin.cinema.master.images.after', ['master' => $master] !!} --}}
                {!! view_render_event('bagisto.admin.cinema.master.images.before', ['master' => $master]) !!}

                <accordian title="{{ __('admin::app.cinema.master.images') }}" :active="false">
                    <div slot="body">
                        {!! view_render_event('bagisto.admin.cinema.master.images.controls.before', ['master' => $master]) !!}

                        <div class="control-group {{ $errors->has('images.files.*') ? 'has-error' : '' }}">
                            <label>{{ __('admin::app.cinema.master.image') }}</label>

                            <master-image></master-image>

                            <span
                                class="control-error"
                                v-text="'{{ $errors->first('images.files.*') }}'">
                            </span>

                            <span class="control-info mt-10">{{ __('admin::app.cinema.master.image-size') }}</span>
                        </div>

                        {!! view_render_event('bagisto.admin.cinema.master.images.controls.after', ['master' => $master]) !!}
                    </div>
                </accordian>

                {!! view_render_event('bagisto.admin.cinema.master.images.after', ['master' => $master]) !!}

                @push('scripts')
                    <script type="text/x-template" id="master-image-template">
                        <div>
                            <div class="image-wrapper">
                                <draggable v-model="items" group="people" @end="onDragEnd">
                                    <master-image-item
                                        v-for='(image, index) in items'
                                        :key='image.id'
                                        :image="image"
                                        class="draggable"
                                        @onRemoveImage="removeImage($event)"
                                        @onImageSelected="imageSelected($event)">
                                    </master-image-item>
                                </draggable>
                            </div>

                            <label class="btn btn-lg btn-primary" style="display: table; width: auto" @click="createFileType">
                                {{ __('admin::app.cinema.master.add-image-btn-title') }}
                            </label>
                        </div>
                    </script>

                    <script type="text/x-template" id="master-image-item-template">
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
                                {{ __('admin::app.cinema.master.remove-image-btn-title') }}
                            </label>
                        </label>
                    </script>

                    <script>
                        Vue.component('master-image', {
                            template: '#master-image-template',

                            data: function() {
                                return {
                                    images: @json($master->images),

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

                        Vue.component('master-image-item', {
                            template: '#master-image-item-template',

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





            {{-- </div>
            <div class="page-content"> --}}
                <div class="form-container">
                    @method('PUT')

                 @csrf
                 {!! view_render_event('bagisto.admin.cinema.master.edit_form_controls.before', ['master' => $master]) !!}

                    <input type="hidden" name="locale" value="all"/>
                    {{-- {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.description_images.before') !!} --}}

                    {{-- <accordian title="{{ __('admin::app.cinema.master.description-and-images') }}" :active="true">
                        <div slot="body">
                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.description_images.controls.before') !!}

                            <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                                <label>{{ __('admin::app.catalog.categories.image') }}</label>

                                <image-wrapper button-label="{{ __('admin::app.cinema.master.add-image-btn-title') }}" input-name="image" :multiple="false"></image-wrapper>

                                <span class="control-error" v-if="{!! $errors->has('image.*') !!}">
                                    @foreach ($errors->get('image.*') as $key => $message)
                                        @php echo str_replace($key, 'Image', $message[0]); @endphp
                                    @endforeach
                                </span>

                                <span class="control-info mt-10">{{ __('admin::app.cinema.master.image-size') }}</span>
                            </div>

                            {!! view_render_event('bagisto.admin::app.cinema.master.create_form_accordian.description_images.controls.after') !!}
                        </div>
                    </accordian> --}}

                    {{-- {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.description_images.after') !!} --}}
                    {!! view_render_event('bagisto.admin.cinema.master.create_form_accordian.general.before') !!}

                    <accordian title="{{ __('admin::app.cinema.master.general') }}" :active="true">
                        <div slot="body">
                            {!! view_render_event('bagisto.admin.cinema.master.create_form_accordian.general.controls.before') !!}

                            <div class="control-group" :class="[errors.has('master_maintitle') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.cinema.master.master_maintitle') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="master_maintitle" name="master_maintitle" value="{{ old('master_maintitle') ?: $master->master_maintitle }}" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_maintitle') }}&quot;" v-slugify-target="'url_key'" />
                                <span class="control-error" v-if="errors.has('master_maintitle')">@{{ errors.first('master_maintitle') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('url_key') ? 'has-error' : '']">
                                <label for="url-key" class="required">{{ __('admin::app.cinema.master.url-key') }}</label>
                                <input id="url_key" type="text" class="control" name="url_key" v-validate="'required'" value="{{ $master->url_key ? $master->url_key : strtolower($master->master_maintitle) }}" data-vv-as="&quot;{{ __('admin::app.cinema.master.url-key') }}&quot;"  v-slugify>
                                <span class="control-error" v-if="errors.has('url_key')">@{{ errors.first('url_key') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('master_othertitle') ? 'has-error' : '']">
                                <label for="name" >{{ __('admin::app.cinema.master.master_othertitle') }}</label>
                                <input type="text" class="control" id="master_othertitle" name="master_othertitle" value="{{ old('master_othertitle') ?: $master->master_othertitle }}" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_othertitle') }}&quot;" />
                                <span class="control-error" v-if="errors.has('master_othertitle')">@{{ errors.first('master_othertitle') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('master_genres[]') ? 'has-error' : '']">
                                <label for="status" class="required">{{ __('admin::app.cinema.master.master_genres') }}</label>
                                <select  data-mdb-filter="true" class="control js-example-basic-multiple" v-validate="'required'" id="master_genres[]" name="master_genres[]" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_genres') }}&quot;" multiple="multiple">

                                    {{-- <option value="">Seleziona Genere</option> --}}
                                @php
                                // $dt=explode(",",$master->master_genres); // PWS#7
                                $dt = $generi_selezionati; // PWS#7
                                @endphp
                                    @foreach ($generi as $gen)
                                        <option {{ in_array($gen->id, $dt) ? 'selected' : '' }}  value="{{ $gen->id }}">{{ $gen->generi_name }}</option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('master_subgenres')">@{{ errors.first('master_subgenres') }}</span>
                                {{-- <div id="signup">
                                    <button id="signup">Click here to sign-up!</button>
                                    @csrf()
                                        <form action="<?php //echo $_SERVER['PHP_SELF']; ?>"  method="POST"  id="form1" style="display : none;">
                                            <input name="user" type="text" placeholder="Username" size="30" >
                                            <input name="pass" type="password" placeholder="Password"  size="30" >
                                            <input class="btn" type="submit" value="sign up" name="signup">
                                       </form>
                                </div> --}}

                            </div>
                            <div class="control-group" :class="[errors.has('master_subgenres[]') ? 'has-error' : '']">
                                <label for="status" >{{ __('admin::app.cinema.master.master_subgenres') }}</label>
                                <select class="control js-example-basic-multiple" id="master_subgenres[]" name="master_subgenres[]" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_subgenres') }}&quot;" multiple="multiple">
                                    @php
                                        // $dt=explode(",",$master->master_subgenres); // PWS#7
                                        $dt = $sottogeneri_selezionati; // PWS#7
                                    @endphp
                                    @foreach ($subgeneri as $gen)
                                    <option {{  in_array($gen->id , $dt)? 'selected' : '' }}  value="{{ $gen->id }}">{{ $gen->subge_name }}</option>
                                @endforeach

                                </select>
                                <span class="control-error" v-if="errors.has('master_subgenres')">@{{ errors.first('master_subgenres') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="master_vt18">{{ __('admin::app.cinema.master.master_vt18') }}</label>
                                <label class="switch">
                                    <input type="hidden" name="master_vt18" value="0" @if ($master->master_vt18==0) {{ 'checked' }} @endif>
                                    <input type="checkbox" id="master_vt18" name="master_vt18" value="1" @if ($master->master_vt18==1) {{ 'checked' }} @endif>

                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="control-group">
                                <label for="master_bn">{{ __('admin::app.cinema.master.master_bn') }}</label>
                                <label class="switch">
                                    <input type="hidden" name="master_bn" value="0" @if ($master->master_bn==0) {{ 'checked' }} @endif>
                                    <input type="checkbox" id="master_bn" name="master_bn" value="1" @if ($master->master_bn==1) {{ 'checked' }} @endif>

                                    <span class="slider round"></span>
                                </label>
                            </div> <!-- PWS#13-bn -->
                            <div class="control-group">
                                <label for="master_is_visible">{{ __('admin::app.cinema.master.master_is_visible') }}</label>
                                <label class="switch">
                                    <input type="hidden" name="master_is_visible" value="0" @if ((old('master_is_visible') ?: $master->master_is_visible) ==0) {{ 'checked' }} @endif>
                                    <input type="checkbox" id="master_is_visible" name="master_is_visible" value="1" @if ((old('master_is_visible') ?: $master->master_is_visible) ==1)  {{ 'checked' }} @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="control-group" :class="[errors.has('country[]') ? 'has-error' : '']">
                                <label for="country" class="{{ core()->isCountryRequired() ? 'required' : '' }}">
                                    {{ __('admin::app.cinema.master.master_country') }}
                                </label>

                                <select
                                    class="control js-example-basic-multiple"
                                    id="country"
                                    type="text"
                                    name="country[]"
                                    v-model="country"
                                    multiple="multiple"
                                    v-validate="'{{ core()->isCountryRequired() ? 'required' : '' }}'"
                                    data-vv-as="&quot;{{ __('admin::app.cinema.master.master_country') }}&quot;">
                                    <option value=""></option>
                                    @php
                                    // $dt=explode(",",$master->country); // PWS#7
                                    $dt = $country_selezionati; // PWS#7
                                 @endphp
                                @foreach ($countries as $country) // PWS#7
                                    <option {{ in_array($country->code , $dt) ? 'selected' : '' }}  value="{{ $country->code }}">{{ $country->name }}</option>
                                @endforeach
                                    {{-- @foreach ($countries as $country) // PWS#7
                                        <option {{ $country->code === $defaultCountry ? 'selected' : '' }}  value="{{ $country->code }}">{{ $country->name }}</option>
                                    @endforeach --}}
                                </select>

                                <span
                                    class="control-error"
                                    v-text="errors.first('country')"
                                    v-if="errors.has('country')">
                                </span>
                            </div>
                            {{-- <div class="control-group date">
                                <label for="master_year">{{ __('admin::app.cinema.master.master_year') }}</label>
                                <datetime>
                                    <input type="text" name="master_year" class="control" value="{{ old('master_year') }}"/>
                                </datetime>
                            </div> --}}

                {{-- @include ('admin::app.cinema.master.country-state', ['countryCode' => old('country') ])

                {!! view_render_event('bagisto.app.cinema.account.address.edit_form_controls.country-state.after') !!} --}}
                <span class="control-error" v-if="errors.has('master_year')">@{{ errors.first('master_year') }}</span>

                <div class="control-group" :class="[errors.has('master_year') ? 'has-error' : '']">
                    <label for="name" class="required">{{ __('admin::app.cinema.master.master_year') }}</label>
                    <input type="text" v-validate="'required'" class="control" id="master_year" name="master_year" value="{{ $master->master_year }}" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_year') }}&quot;" v-slugify-target="'slug'"/>
                    <span class="control-error" v-if="errors.has('master_year')">@{{ errors.first('master_year') }}</span>

                <span class="control-error" v-if="errors.has('master_subgenres')">@{{ errors.first('master_subgenres') }}</span>
            </div>
                            {{-- <div class="control-group" :class="[errors.has('date_of_birth') ? 'has-error' : '']">
                                <label for="dob">{{ __('admin::app.customers.customers.date_of_birth') }}</label>
                                <input type="date" class="control" id="dob" name="date_of_birth" v-validate="" value="{{ old('date_of_birth') }}" placeholder="{{ __('admin::app.customers.customers.date_of_birth_placeholder') }}" data-vv-as="&quot;{{ __('admin::app.customers.customers.date_of_birth') }}&quot;">
                                <span class="control-error" v-if="errors.has('date_of_birth')">@{{ errors.first('date_of_birth') }}</span>
                            </div> --}}
                            <div class="control-group" :class="[errors.has('master_directorroups[]') ? 'has-error' : '']">
                                <label for="master_director" class="required">{{ __('admin::app.cinema.master.master_director') }}</label>

                                <select class="control js-example-basic-multiple" id="master_director" name="master_director[]" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_director') }}&quot;" multiple="multiple">
                                    @php
                                    // $dt=explode(",",$master->master_director); // PWS#7
                                    $dt = $registi_selezionati; // PWS#7
                                 @endphp
                                    @foreach($registi as $registi)
                                    <option value="{{ $registi->id }}" {{ in_array($registi->id , $dt) ? 'selected' : '' }}>
                                        {{ preg_replace('/\s\s+/', ' ', $registi->registi_nome_cognome); }} <!-- PWS#10-20221223 -->
                                    </option>
                                @endforeach

                                </select>

                                <span class="control-error" v-if="errors.has('master_actors[]')">@{{ errors.first('master_actors[]') }}</span>
                                {{-- <button id="addOptions" class="btn btn-success">Add new Regista</button> --}}
                            </div>
                            <div class="control-group" :class="[errors.has('master_actors[]') ? 'has-error' : '']">
                                <label for="master_actors" class="required">{{ __('admin::app.cinema.master.master_actors') }}</label>

                                <select class="control js-example-basic-multiple" id="master_actors" name="master_actors[]" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_actors') }}&quot;" multiple="multiple"> <!-- PWS#9 -->
                                    @php
                                    $dt=explode(",",$master->master_actors);
                                 @endphp
                                    @foreach($attori as $registi)
                                    <option value="{{ $registi->id }}" {{ in_array($registi->id , $dt) ? 'selected' : ''  }}>
                                        {{ preg_replace('/\s\s+/', ' ', $registi->attori_nome_cognome); }} <!-- PWS#10-20221223 -->
                                    </option>
                                @endforeach

                                </select>

                                <span class="control-error" v-if="errors.has('master_scriptwriters[]')">@{{ errors.first('master_scriptwriters[]') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('master_scriptwriters[]') ? 'has-error' : '']">
                                <label for="master_scriptwriters" class="required">{{ __('admin::app.cinema.master.master_scriptwriters') }}</label>

                                <select class="control js-example-basic-multiple" id="master_scriptwriters" name="master_scriptwriters[]" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_scriptwriters') }}&quot;" multiple="multiple"> <!-- PWS#04-23 -->
                                    @php
                                    //$dt=explode(",",$master->master_scriptwriters); // PWS#7
                                    $dt = $sceneggiatori_selezionati; // PWS#7
                                 @endphp
                                    @foreach($sceneggiatori as $registi)
                                    <option value="{{ $registi->id }}" {{ in_array($registi->id , $dt) ? 'selected' : ''  }}>
                                        {{ preg_replace('/\s\s+/', ' ', $registi->scene_nome_cognome); }} <!-- PWS#10-20221223 -->
                                    </option>
                                    @endforeach
                                </select>

                                <span class="control-error" v-if="errors.has('master_musiccomposers[]')">@{{ errors.first('master_musiccomposers[]') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('master_musiccomposers[]') ? 'has-error' : '']">
                                <label for="master_musiccomposers" class="required">{{ __('admin::app.cinema.master.master_musiccomposers') }}</label>

                                <select class="control js-example-basic-multiple" id="master_musiccomposers" name="master_musiccomposers[]" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_musiccomposers') }}&quot;" multiple="multiple">
                                    @php
                                    // $dt=explode(",",$master->master_musiccomposers); // PWS#7
                                    $dt = $compositori_selezionati; // PWS#7
                                 @endphp
                                    @foreach($compositori as $registi)
                                    <option value="{{ $registi->id }}" {{ in_array($registi->id , $dt) ? 'selected' : ''  }}>
                                        {{ preg_replace('/\s\s+/', ' ', $registi->compo_nome_cognome); }} <!-- PWS#10-20221223 -->
                                    </option>
                                    @endforeach

                                </select>

                                <span class="control-error" v-if="errors.has('master_studios[]')">@{{ errors.first('master_studios[]') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('master_studios[]') ? 'has-error' : '']">
                                <label for="master_studios js-example-basic-multiple" class="required">{{ __('admin::app.cinema.master.master_studios') }}</label>

                                <select class="control js-example-basic-multiple" id="master_studios" name="master_studios[]" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_studios') }}&quot;" multiple="multiple">

                                    @php
                                    // $dt=explode(",",$master->master_studios); // PWS#7
                                    $dt = $casaproduzione_selezionati; // PWS#7
                                 @endphp
                                    @foreach($casaproduzione as $registi)
                                    <option value="{{ $registi->id }}" {{ in_array($registi->id , $dt) ? 'selected' : ''  }}>
                                        {{ $registi->casa_nome }}
                                    </option>
                                @endforeach

                                </select>

                                <span class="control-error" v-if="errors.has('master_language[]')">@{{ errors.first('master_language[]') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('master_language') ? 'has-error' : '']">
                                <label for="status " class="required">{{ __('admin::app.cinema.master.master_language') }}</label>
                                <select class="control js-example-basic-multiple" v-validate="'required'" id="master_language" name="master_language[]" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_language') }}&quot;" multiple="multiple">  <!-- PWS#10-lang -->
                                  @php
                                  $dt = $lingue_selezionate; // PWS#10-lang
                                  @endphp
                                    @foreach ($language as $gen)
                                    <option value="{{ $gen->id }}" {{ in_array($gen->id , $dt) ? 'selected' : ''  }}>
                                        {{ $gen->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('master_type')">@{{ errors.first('master_type') }}</span>
                            </div>
                            <div class="control-group" :class="[errors.has('master_type') ? 'has-error' : '']">
                                <label for="status" class="required">{{ __('admin::app.cinema.master.master_type') }}</label>
                                <select class="control js-example-basic-multiple" v-validate="'required'" id="master_type" name="master_type" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_type') }}&quot;">
                                    <option  {{ "movie" == $master->master_type ? 'selected' : ''  }}  value="movie">MOVIE</option>
                                    <option  {{ "movie-episode"== $master->master_type ? 'selected' : ''  }}  value="movie-episode">MOVIE-EPISODE</option>
                                    <option {{ "movie-episode-TV" == $master->master_type ? 'selected' : ''  }}   value="movie-episode-TV">MOVIE-SERIE TV</option>
                                </select>
                                <span class="control-error" v-if="errors.has('master_subgenres')">@{{ errors.first('master_subgenres') }}</span>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.controls.after') !!}
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.after') !!}



                </div>
                {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.description.before', ['master' => $master]) !!}

                <accordian title="DESCRIZIONE" :active="false">
                    <div slot="body">
                        {!! view_render_event('bagisto.admin.cinema.master.edit_form_accordian.description.controls.before', ['master' => $master]) !!}
                        <div>
                            <div class="control-group textarea">
                                <label for="short_description" >Short Description</label>
                                <textarea  id="short_description" name="short_description" data-vv-as="&quot;Short Description&quot;" class="control " aria-required="true" aria-invalid="true">{{ $master->short_description }}</textarea>
                            </div>
                        <div class="control-group textarea ">
                            <label for="master_description" >Description</label>
                            <textarea  id="master_description" name="master_description" data-vv-as="&quot;Description&quot;" class="control " aria-required="true" aria-invalid="true">{{ $master->master_description }}</textarea>
                        </div>
                        <div class="control-group textarea ">
                            <label for="master_trama" >Trama</label>
                            <textarea  id="master_trama" name="master_trama" data-vv-as="Trama" class="control " aria-required="true" aria-invalid="true">{{ $master->master_trama }}</textarea> <!-- PWS#13-trama -->
                        </div>
                    </div>

                        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.description.controls.after', ['master' => $master]) !!}
                    </div>
                </accordian>
                <accordian title="META DESCRIPTION" :active="false">
            <div slot="body">
             {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.description.controls.before', ['master' => $master]) !!}

             <div>
                <div class="control-group textarea ">
                    <label for="meta_title">Meta Title</label>
                    <textarea  id="meta_title" name="meta_title" data-vv-as="&quot;Meta Title&quot;" class="control " aria-required="false" aria-invalid="false">{{ $master->meta_title }}</textarea> <!---->
                </div>
                <div class="control-group textarea "><label for="meta_keywords">Meta Keywords</label>
                    <textarea  id="meta_keywords" name="meta_keywords" data-vv-as="&quot;Meta Keywords&quot;" class="control " aria-required="false" aria-invalid="false">{{ $master->meta_keywords }}</textarea> <!---->
                </div>
                <div class="control-group textarea ">
                    <label for="meta_description">Meta Description</label>
                    <textarea  id="meta_description" name="meta_description" data-vv-as="&quot;Meta Description&quot;" class="control " aria-required="false" aria-invalid="false">{{ $master->meta_description }}</textarea> <!---->
                </div>
            </div>

             {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.description.controls.after', ['master' => $master]) !!}
         </div>
     </accordian>

                {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.description.after', ['master' => $master]) !!}

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
