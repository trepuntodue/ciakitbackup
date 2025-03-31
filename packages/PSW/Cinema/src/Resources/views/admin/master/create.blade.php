@php
  $currentCustomer = auth()
      ->guard('customer')
      ->user();
@endphp
@extends('admin::layouts.content')

@section('page_title')
  {{ __('admin::app.cinema.master.add-title') }}
@stop

@section('content')
  <div class="content">
    <form method="POST" action="{{ route('admin.cinema.master.store') }}" @submit.prevent="onSubmit"
      enctype="multipart/form-data">
      @if(app('request')->input('customer_id'))
        <input type="hidden" name="customer_id" id="customer_id" value="{{ app('request')->input('customer_id') }}"> <!-- PWS#chiusura -->
      @endif
      <div class="page-header">
        <div class="page-title">
          <h1>
            <i class="icon angle-left-icon back-link"
              onclick="window.location = '{{ route('admin.cinema.master.index') }}'"></i>
            {{ __('admin::app.cinema.master.add-title') }}
          </h1>
        </div>

        <div class="page-action">
          <button type="submit" class="btn btn-lg btn-primary">
            {{ __('admin::app.cinema.master.save-btn-title') }}
          </button>
        </div>
      </div>

      <div class="page-content">
        <div class="form-container">
          @csrf()

          <input type="hidden" name="locale" value="all" />
          {{-- {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.description_images.before') !!}

                    <accordian title="{{ __('admin::app.cinema.master.description-and-images') }}" :active="true">
                        <div slot="body">
                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.description_images.controls.before') !!}

                            <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                                <label>{{ __('admin::app.catalog.categories.image') }}</label>

                                <image-wrapper button-label="{{ __('admin::app.cinema.master.add-image-btn-title') }}" input-name="image" :multiple="false"></image-wrapper>

                                <span class="control-error" v-show="{!! $errors->has('image.*') !!}">
                                    @foreach ($errors->get('image.*') as $key => $message)
                                        @php echo str_replace($key, 'Image', $message[0]); @endphp
                                    @endforeach
                                </span>

                                <span class="control-info mt-10">{{ __('admin::app.cinema.master.image-size') }}</span>
                            </div>

                            {!! view_render_event('bagisto.admin::app.cinema.master.create_form_accordian.description_images.controls.after') !!}
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.description_images.after') !!} --}}
          {!! view_render_event('bagisto.admin.cinema.master.create_form_accordian.general.before') !!}

          <accordian title="{{ __('admin::app.cinema.master.general') }}" :active="true">
            <div slot="body">
              {!! view_render_event('bagisto.admin.cinema.master.create_form_accordian.general.controls.before') !!}
              {{-- <div class="control-group" :class="[errors.has('master_maintitle') ? 'has-error' : '']">
                                 <select  class="control js-data-example-ajax"></select>
                                </div> --}}
              {{-- <div class="control-group" :class="[errors.has('master_maintitle') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.cinema.master.master_maintitle') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="master_maintitle" name="master_maintitle" value="{{ old('master_maintitle') }}" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_maintitle') }}&quot;" v-slugify-target="'slug'"/>
                                <span class="control-error" v-show="errors.has('master_maintitle')">@{{ errors.first('master_maintitle') }}</span>
                            </div> --}}
              <div class="control-group" :class="[errors.has('master_maintitle') ? 'has-error' : '']">
                <label for="name" class="required">{{ __('admin::app.cinema.master.master_maintitle') }}</label>
                <input type="text" v-validate="'required'" class="control" id="master_maintitle"
                  name="master_maintitle" value="{{ app('request')->input('original_title') }}"
                  data-vv-as="&quot;{{ __('admin::app.cinema.master.master_maintitle') }}&quot;"
                  v-slugify-target="'url_key'" /> <!-- PWS#chiusura -->
                <span class="control-error" v-show="errors.has('master_maintitle')">@{{ errors.first('master_maintitle') }}</span>
              </div>
              <div class="control-group" :class="[errors.has('url_key') ? 'has-error' : '']">
                <label for="url_key" class="required">{{ __('admin::app.cinema.master.url-key') }}</label>
                <input id="url_key" type="text" class="control" name="url_key" v-validate="'required'"
                  value="{{ old('url_key') }}" data-vv-as="&quot;{{ __('admin::app.cinema.master.url-key') }}&quot;"
                  v-slugify>
                <span class="control-error" v-show="errors.has('url_key')">@{{ errors.first('url_key') }}</span>
              </div>
              <div class="control-group" :class="[errors.has('master_othertitle') ? 'has-error' : '']">
                <label for="name">{{ __('admin::app.cinema.master.master_othertitle') }}</label>
                <input type="text" class="control" id="master_othertitle" name="master_othertitle"
                  value="{{ app('request')->input('other_title') }}"
                  data-vv-as="&quot;{{ __('admin::app.cinema.master.master_othertitle') }}&quot;"
                  v-slugify-target="'slug'" />
                <span class="control-error" v-show="errors.has('master_othertitle')">@{{ errors.first('master_othertitle') }}</span>
              </div>

              <div class="control-group" :class="[errors.has('master_genres[]') ? 'has-error' : '']">
                <label for="status" class="required">{{ __('admin::app.cinema.master.master_genres') }}</label>
                <select data-mdb-filter="true" class="control js-example-basic-multiple" v-validate="'required'"
                  id="master_genres[]" name="master_genres[]"
                  data-vv-as="&quot;{{ __('admin::app.cinema.master.master_genres') }}&quot;" multiple="multiple">

                  {{-- <option value="">Seleziona Genere</option> --}}
                  @php
                  $query_genres = app('request')->input('master_genres');
                  if(!is_array($query_genres)) $query_genres = array();
                  @endphp
                  @foreach ($generi as $gen)
                    <option {{ $query_genres && in_array($gen->id, $query_genres) ? 'selected' : '' }}
                      value="{{ $gen->id }}">{{ $gen->generi_name }}</option>
                  @endforeach
                </select>
                <span class="control-error" v-show="errors.has('master_genres[]')">@{{ errors.first('master_genres[]') }}</span>
                {{-- <div id="signup">
                                    <button id="signup">Click here to sign-up!</button>
                                    @csrf()
                                        <form action="<?php //echo $_SERVER['PHP_SELF'];
                                        ?>"  method="POST"  id="form1" style="display : none;">
                                            <input name="user" type="text" placeholder="Username" size="30" >
                                            <input name="pass" type="password" placeholder="Password"  size="30" >
                                            <input class="btn" type="submit" value="sign up" name="signup">
                                       </form>
                                </div> --}}

              </div>
              <div class="control-group" :class="[errors.has('master_subgenres[]') ? 'has-error' : '']">
                <label for="status">{{ __('admin::app.cinema.master.master_subgenres') }}</label>
                <select class="control js-example-basic-multiple" id="master_subgenres[]" name="master_subgenres[]"
                  data-vv-as="&quot;{{ __('admin::app.cinema.master.master_subgenres') }}&quot;" multiple="multiple">
                  @php
                  $query_subgenres = app('request')->input('master_subgenres');
                  if(!is_array($query_subgenres)) $query_subgenres = array();
                  @endphp
                  @foreach ($subgeneri as $gen)
                    <option
                      {{ $query_subgenres && in_array($gen->id, $query_subgenres) ? 'selected' : '' }}
                      value="{{ $gen->id }}">{{ $gen->subge_name }}</option> <!-- PWS#chiusura -->
                  @endforeach

                </select>
                <span class="control-error" v-show="errors.has('master_subgenres')">@{{ errors.first('master_subgenres') }}</span>
              </div>

              <div class="control-group">
                <label for="master_vt18">{{ __('admin::app.cinema.master.master_vt18') }}</label>
                <label class="switch">
                  <input type="hidden" name="master_vt18" value="0"
                    @if (app('request')->input('master_vt18') == 0) {{ 'checked' }} @endif>
                  <input type="checkbox" id="master_vt18" name="master_vt18" value="1"
                    @if (app('request')->input('master_vt18') == 1) {{ 'checked' }} @endif>

                  <span class="slider round"></span>
                </label>
              </div>

              <div class="control-group">
                <label for="master_bn">{{ __('admin::app.cinema.master.master_bn') }}</label>
                <label class="switch">
                  <input type="hidden" name="master_bn" value="0"
                    @if (app('request')->input('master_bn') == 0) {{ 'checked' }} @endif>
                  <input type="checkbox" id="master_bn" name="master_bn" value="1"
                    @if (app('request')->input('master_bn') == 1) {{ 'checked' }} @endif>

                  <span class="slider round"></span>
                </label>
              </div> <!-- PWS#13-bn -->
              <div class="control-group">
                <label for="master_is_visible">{{ __('admin::app.cinema.master.master_is_visible') }}</label>
                <label class="switch">
                  <input type="hidden" name="master_is_visible" value="0"
                    @if (old('master_is_visible') == 0) {{ 'checked' }} @endif>
                  <input type="checkbox" id="master_is_visible" name="master_is_visible" value="1"
                    @if (old('master_is_visible') == 1) {{ 'checked' }} @endif>
                  <span class="slider round"></span>
                </label>
              </div>
              <div class="control-group" :class="[errors.has('country[]') ? 'has-error' : '']">
                <label for="country" class="{{ core()->isCountryRequired() ? 'required' : '' }}">
                  {{ __('admin::app.cinema.master.master_country') }}
                </label>

                <select class="control js-example-basic-multiple" id="country" type="text" name="country[]"
                  v-model="country" multiple="multiple"
                  v-validate="'{{ core()->isCountryRequired() ? 'required' : '' }}'"
                  data-vv-as="&quot;{{ __('admin::app.cinema.master.master_country') }}&quot;">
                  <option value=""></option>
                  @php
                  $query_country = app('request')->input('country');
                  if(!is_array($query_country)) $query_country = array();
                  @endphp
                  @foreach (core()->countries() as $country)
                    <option {{ $query_country && in_array($country->code, $query_country) ? 'selected' : '' }}
                      value="{{ $country->code }}">{{ $country->name }}</option>
                  @endforeach
                </select>

                <span class="control-error" v-text="errors.first('country')" v-show="errors.has('country')">
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

              <div class="control-group" :class="[errors.has('master_year') ? 'has-error' : '']">
                <label for="name" class="required">{{ __('admin::app.cinema.master.master_year') }}</label>
                <input type="text" v-validate="'required'" class="control" id="master_year" name="master_year"
                  value="{{ app('request')->input('master_year') }}"
                  data-vv-as="&quot;{{ __('admin::app.cinema.master.master_year') }}&quot;"
                  v-slugify-target="'slug'" />
                <span class="control-error" v-show="errors.has('master_year')">@{{ errors.first('master_year') }}</span>
              </div>
              {{-- <div class="control-group" :class="[errors.has('date_of_birth') ? 'has-error' : '']">
                                <label for="dob">{{ __('admin::app.customers.customers.date_of_birth') }}</label>
                                <input type="date" class="control" id="dob" name="date_of_birth" v-validate="" value="{{ old('date_of_birth') }}" placeholder="{{ __('admin::app.customers.customers.date_of_birth_placeholder') }}" data-vv-as="&quot;{{ __('admin::app.customers.customers.date_of_birth') }}&quot;">
                                <span class="control-error" v-show="errors.has('date_of_birth')">@{{ errors.first('date_of_birth') }}</span>
                            </div> --}}
              <div class="control-group" :class="[errors.has('master_director[]') ? 'has-error' : '']">
                <label for="master_director"
                  class="required">{{ __('admin::app.cinema.master.master_director') }}</label>

                <select class="control js-example-basic-multiple" id="master_director" name="master_director[]"
                  v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_director') }}&quot;"
                  multiple="multiple">

                  @foreach ($registi as $registi)
                    <option value="{{ $registi->id }}"
                      {{ old('master_director') && in_array($registi->id, old('master_director')) ? 'selected' : '' }}>
                      {{ preg_replace('/\s\s+/', ' ', $registi->registi_nome_cognome) }}
                      <!-- PWS#10-20221223 -->
                    </option>
                  @endforeach

                </select>
                <span class="control-info mt-10">{{ app('request')->input('master_director') }}</span>
                <span class="control-error" v-show="errors.has('master_director[]')">@{{ errors.first('master_director[]') }}</span>
                {{-- <button id="addOptions" class="btn btn-success">Add new Regista</button> --}}
              </div>
              <div class="control-group" :class="[errors.has('master_actors[]') ? 'has-error' : '']">
                <label for="master_actors" class="required">{{ __('admin::app.cinema.master.master_actors') }}</label>

                <select class="control js-example-basic-multiple" id="master_actors" name="master_actors[]"
                  data-vv-as="&quot;{{ __('admin::app.cinema.master.master_actors') }}&quot;" multiple="multiple">
                  <!-- PWS#9 -->

                  @foreach ($attori as $registi)
                    <option value="{{ $registi->id }}"
                      {{ old('master_actors') && in_array($registi->id, old('master_actors')) ? 'selected' : '' }}>
                      {{ preg_replace('/\s\s+/', ' ', $registi->attori_nome_cognome) }}
                      <!-- PWS#10-20221223 -->
                    </option>
                  @endforeach

                </select>

                <span class="control-info mt-10">{{ app('request')->input('master_actors') }}</span>
                <span class="control-error" v-show="errors.has('master_actors[]')">@{{ errors.first('master_actors[]') }}</span>
              </div>
              <div class="control-group" :class="[errors.has('master_scriptwriters[]') ? 'has-error' : '']">
                <label for="master_scriptwriters"
                  class="required">{{ __('admin::app.cinema.master.master_scriptwriters') }}</label>

                <select class="control js-example-basic-multiple" id="master_scriptwriters"
                  name="master_scriptwriters[]"
                  data-vv-as="&quot;{{ __('admin::app.cinema.master.master_scriptwriters') }}&quot;"
                  multiple="multiple"><!-- PWS#04-23v2 -->

                  @foreach ($sceneggiatori as $registi)
                    <option value="{{ $registi->id }}"
                      {{ old('master_scriptwriters') && in_array($registi->id, old('master_scriptwriters')) ? 'selected' : '' }}>
                      {{ preg_replace('/\s\s+/', ' ', $registi->scene_nome_cognome) }}
                      <!-- PWS#10-20221223 -->
                    </option>
                  @endforeach
                </select>

                <span class="control-info mt-10">{{ app('request')->input('master_scriptwriters') }}</span>
                <span class="control-error" v-show="errors.has('master_scriptwriters[]')">@{{ errors.first('master_scriptwriters[]') }}</span>
              </div>
              <div class="control-group" :class="[errors.has('master_musiccomposers[]') ? 'has-error' : '']">
                <label for="master_musiccomposers"
                  class="required">{{ __('admin::app.cinema.master.master_musiccomposers') }}</label>

                <select class="control js-example-basic-multiple" id="master_musiccomposers"
                  name="master_musiccomposers[]" v-validate="'required'"
                  data-vv-as="&quot;{{ __('admin::app.cinema.master.master_musiccomposers') }}&quot;"
                  multiple="multiple">

                  @foreach ($compositori as $registi)
                    <option value="{{ $registi->id }}"
                      {{ old('master_musiccomposers') && in_array($registi->id, old('master_musiccomposers')) ? 'selected' : '' }}>
                      {{ preg_replace('/\s\s+/', ' ', $registi->compo_nome_cognome) }}
                      <!-- PWS#10-20221223 -->
                    </option>
                  @endforeach

                </select>

                <span class="control-info mt-10">{{ app('request')->input('master_musiccomposers') }}</span>
                <span class="control-error" v-show="errors.has('master_musiccomposers[]')">@{{ errors.first('master_musiccomposers[]') }}</span>
              </div>
              <div class="control-group" :class="[errors.has('master_studios[]') ? 'has-error' : '']">
                <label for="master_studios js-example-basic-multiple"
                  class="required">{{ __('admin::app.cinema.master.master_studios') }}</label>

                <select class="control js-example-basic-multiple" id="master_studios" name="master_studios[]"
                  v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_studios') }}&quot;"
                  multiple="multiple">

                  {{-- @foreach ($registi as $registi)
                                        <option value="{{ $registi->id }}" {{ old('master_studios') && in_array($registi->id, old('master_studios')) ? 'selected' : '' }}>
                                            {{ $registi->nome }} {{$registi->cognome}}
                                        </option>
                                    @endforeach --}}
                  @foreach ($casaproduzione as $registi)
                    <option value="{{ $registi->id }}"
                      {{ old('master_studios') && in_array($registi->id, old('master_studios')) ? 'selected' : '' }}>
                      {{ $registi->casa_nome }} {{ $registi->casa_cognome }}
                    </option>
                  @endforeach

                </select>

                <span class="control-info mt-10">{{ app('request')->input('master_studios') }}</span>
                <span class="control-error" v-show="errors.has('master_studios[]')">@{{ errors.first('master_studios[]') }}</span>
              </div>
              <div class="control-group" :class="[errors.has('master_language') ? 'has-error' : '']">
                <label for="status " class="required">{{ __('admin::app.cinema.master.master_language') }}</label>
                <select class="control js-example-basic-multiple" v-validate="'required'" id="master_language"
                  name="master_language[]"
                  data-vv-as="&quot;{{ __('admin::app.cinema.master.master_language') }}&quot;" multiple="multiple">
                  <!-- PWS#10-lang -->

                  <option value="">Seleziona Lingua</option>

                  @foreach ($language as $gen)
                    <option value="{{ $gen->id }}"
                      {{ app('request')->input('language') && $gen->code == app('request')->input('language') ? 'selected' : '' }}>
                      {{ $gen->name }}</option>
                    {{-- <option {{ $gen->id === $defaultCountry ? 'selected' : '' }}  value="{{ $gen->id }}">{{ $gen->name }}</option> --}}
                  @endforeach
                </select>
                <span class="control-error" v-show="errors.has('master_type')">@{{ errors.first('master_type') }}</span>
              </div>
              <div class="control-group" :class="[errors.has('master_type') ? 'has-error' : '']">
                <label for="status" class="required">{{ __('admin::app.cinema.master.master_type') }}</label>
                <select class="control js-example-basic-multiple" v-validate="'required'" id="master_type"
                  name="master_type" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_type') }}&quot;">
                  <option {{ app('request')->input('master_type') && app('request')->input('master_type') == 'movie' ? 'selected' : '' }} value="movie">
                    MOVIE</option>
                  <option {{ app('request')->input('master_type') && app('request')->input('master_type') == 'movie-episode' ? 'selected' : '' }}
                    value="movie-episode">MOVIE-EPISODE</option>
                  <option {{ app('request')->input('master_type') && app('request')->input('master_type') == 'movie-episode-TV' ? 'selected' : '' }}
                    value="movie-episode-TV">MOVIE-SERIE TV</option>
                </select>
                <span class="control-error" v-show="errors.has('master_subgenres')">@{{ errors.first('master_subgenres') }}</span>
              </div>

              {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.controls.after') !!}
            </div>
          </accordian>

          {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.after') !!}

        </div>
      </div>
    </form>
  </div>
  @push('css')
    <style>
      #form1 {
        display: none;
      }
    </style>
  @endpush
  @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
      integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
      crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"
      integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ=="
      crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"
      integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ=="
      crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.4/jquery.rateyo.min.js"
      integrity="sha512-09bUVOnphTvb854qSgkpY/UGKLW9w7ISXGrN0FR/QdXTkjs0D+EfMFMTB+CGiIYvBoFXexYwGUD5FD8xVU89mw=="
      crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script>
      $(document).ready(function() {

        $('#master_genres').trigger("change");

        if($('#master_maintitle').val()){
          $('#master_maintitle').trigger("change");
        }

        $("#signup").click(function() {
          alert("cliccato");
          $("#form1").toggle('slow');
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
          innerValue(val) {
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
        props: {
          rating: 0,
          min: 0,
          max: 5,
          target: ''
        },
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
            onSet: function(rating, rateYoInstance) {
              $('input[name="' + vm.target + '"]').val(rating);
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
          innerValue(val) {
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
