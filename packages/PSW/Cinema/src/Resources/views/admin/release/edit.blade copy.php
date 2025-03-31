@php
$currentCustomer = auth()->guard('customer')->user();
// echo '<pre>';print_r($release->images);
// die();
@endphp
@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cinema.release.update-title') }}
@stop
@section('content')
    <div class="content">
        <form method="post" action="{{ route('admin.cinema.release.update', $release->id) }}" @submit.prevent="onSubmit">
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

            <input type="hidden" name="locale" value="all"/>
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
                {{-- @method('PUT') --}}

             {{-- @csrf  --}}

             {!! view_render_event('bagisto.admin.cinema.release.create_form_accordian.general.before') !!}

        <accordian title="{{ __('admin::app.cinema.release.general') }}" :active="true">
        <div slot="body">
            <div class="control-group" :class="[errors.has('original_title') ? 'has-error' : '']">
                <label for="original_title" class="mandatory">{{ __('admin::app.cinema.release.original_title') }}</label>

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

            {!! view_render_event('bagisto.admin.cinema.release.edit_form_controls.original_title.after') !!}
        </div>
        <div slot="body">
        <div class="control-group" :class="[errors.has('url_key') ? 'has-error' : '']">
            <label for="url-key" >{{ __('admin::app.cinema.release.url-key') }}</label>
            <input readonly id="url_key" type="text" class="control" name="url_key"  value="{{ old('url_key')  ?? $release->url_key}}" data-vv-as="&quot;{{ __('admin::app.cinema.release.url-key') }}&quot;" >
            <span class="control-error" v-if="errors.has('url_key')">@{{ errors.first('url_key') }}</span>
        </div>
        </div>
        {{-- <div slot="body">

            <div class="control-group" :class="[errors.has('other_title') ? 'has-error' : '']">
                <label for="other_title" >{{ __('admin::app.cinema.release.other_title') }}</label>

                <input
                    type="text"
                    class="control"
                    name="other_title"
                    value="{{ old('other_title') ?? $release->other_title }}"
                   
                    data-vv-as="&quot;{{ __('admin::app.cinema.release.other_title') }}&quot;">

                <span
                    class="control-error"
                    v-text="errors.first('other_title')"
                    v-if="errors.has('other_title')">
                </span>
            </div>

            {!! view_render_event('bagisto.admin.cinema.release.edit_form_controls.other_title.after') !!}
        </div> --}}
        <div slot="body">

            <div class="control-group" :class="[errors.has('release_year') ? 'has-error' : '']">
                <label for="release_year" class="mandatory">{{ __('admin::app.cinema.release.release_year') }}</label>

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

            {!! view_render_event('bagisto.admin.cinema.release.edit_form_controls.release_year.after') !!}
        </div>
        <div slot="body">

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

            {!! view_render_event('bagisto.admin.cinema.release.edit_form_controls.release_distribution.after') !!}
        </div>
        <div slot="body">

            @include ('shop::cinema.release.country-state', ['countryCode' => old('country') ?? $release->country])
        </div>
            {!! view_render_event('bagisto.admin.cinema.release.edit_form_controls.country-state.after') !!}
            
            {{-- @include ('shop::customers.account.release.releasetype', ['releasetypeCode' => old('releasetype') ?? $release->releasetype]) --}}

            {!! view_render_event('bagisto.admin.cinema.release.edit_form_controls.releasetype.after') !!}
            
        <div slot="body">
            <span class="control-error" v-if="errors.has('language[]')">@{{ errors.first('language[]') }}</span>

            {{-- @include ('shop::customers.account.release.language', ['languageCode' => old('language') ?? $release->language]) --}}
            <div class="control-group" :class="[errors.has('language') ? 'has-error' : '']">
                <label for="status " class="required">{{ __('admin::app.cinema.release.language') }}</label>
                <select class="control js-example-basic-multiple" v-validate="'required'" id="language" name="language" data-vv-as="&quot;{{ __('admin::app.cinema.release.language') }}&quot;">                                   
                    @foreach ($language as $gen)
                        <option {{ $gen->id == $release->language ? 'selected' : ''  }}  value="{{ $gen->id }}">{{ $gen->name }}</option>
                    @endforeach
                </select>
            </div>
            {!! view_render_event('bagisto.admin.cinema.release.edit_form_controls.language.after') !!}
        </div>
        <div slot="body">
        <div class="control-group">
            <label for="release_vt18">{{ __('admin::app.cinema.release.release_vt18') }}</label>
            <label class="switch">
                <input type="hidden" name="release_vt18" value="0" @if (old('release_vt18')==0) {{ 'checked' }} @endif>
                <input type="checkbox" id="release_vt18" name="release_vt18" value="1" @if (old('release_vt18')==1) {{ 'checked' }} @endif>

                <span class="slider round"></span>
            </label>
        </div>
        </div>
        <div slot="body">
        <div class="control-group">
            <label for="release_status">{{ __('admin::app.cinema.release.release_status') }}</label>
            <label class="switch">
                <input type="hidden" name="release_status" value="0" @if (old('release_status')==0) {{ 'checked' }} @endif>
                <input type="checkbox" id="release_status" name="release_status" value="1" @if (old('release_status')==1)  {{ 'checked' }} @endif>
                <span class="slider round"></span>
            </label>
        </div>
        </div>
        </accordian>
                   
        {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.after') !!}
            {!! view_render_event('bagisto.admin.cinema.release.edit_form_controls.after', ['release' => $release]) !!}

         

            {{-- <div class="button-group">
                <button class="theme-btn" type="submit">
                    {{ __('admin::app.cinema.release.submit') }}
                </button>
            </div> --}}

                   

        {{-- </div> --}}
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
        </div>
    </div>
    </form>
</div>
    {!! view_render_event('bagisto.admin.cinema.release.after', ['release' => $release]) !!}
 
@endsection
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
