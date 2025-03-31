<!-- PWS#chiusura -->
@php
  $currentCustomer = auth()
      ->guard('customer')
      ->user();
@endphp

@extends('shop::customers.account.index')
@section('page_title')
  {{ __('shop::app.customer.account.master.create.page-title') }}
@endsection

@push('css')
  <style type="text/css">
    input[type="radio"]:checked+.tipo {
      background-color: #d8af00;
    }
  </style>
@endpush

@section('page-detail-wrapper')
  <div class="account-head mb-15">
    <span class="account-heading">{{ __('shop::app.customer.account.master.create.title') }}</span>
  </div>

  {!! view_render_event('bagisto.shop.customers.account.master.create.before') !!}

    <form method="post" action="{{ route('customer.master.send') }}" @submit.prevent="onSubmit"
      enctype="multipart/form-data">
      <!-- START PWS#8-link-master -->
      <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer_id }}">
      <!-- END PWS#8-link-master -->
      <div class="account-table-content mb-2">
        @csrf

        <div class="control-group" :class="[errors.has('original_title') ? 'has-error' : '']">
          <label for="original_title"
            class="mandatory">{{ __('admin::app.cinema.master.master_maintitle') }}</label>

          <input class="control" type="text" name="original_title" value=""
            v-validate="'required'"
            data-vv-as="&quot;{{ __('admin::app.cinema.master.master_maintitle') }}&quot;">

          <span class="control-error" v-text="errors.first('original_title')" v-if="errors.has('original_title')">
          </span>
        </div>

        <div class="control-group" :class="[errors.has('other_title') ? 'has-error' : '']">
          <label for="other_title">{{ __('admin::app.cinema.master.master_othertitle') }}</label>

          <input class="control" type="text" name="other_title" value=""
            data-vv-as="&quot;{{ __('admin::app.cinema.master.master_othertitle') }}&quot;">

          <span class="control-error" v-text="errors.first('other_title')" v-if="errors.has('other_title')">
          </span>
        </div>

        <div class="control-group" :class="[errors.has('master_genres') ? 'has-error' : '']">
          <label for="master_genres" class="mandatory">
            {{ __('admin::app.cinema.master.master_genres') }}
          </label>

          <select class="control  js-example-basic-multiple" id="master_genres" type="text" name="master_genres[]"
            v-validate="'required'"
            data-vv-as="&quot;{{ __('admin::app.cinema.master.master_genres') }}&quot;"  multiple="multiple">

            @foreach ($generi as $genere)
              <option value="{{ $genere->id }}">{{ $genere->generi_name }}</option>
            @endforeach
          </select>

          <div class="select-icon-container">
            <span class="select-icon rango-arrow-down"></span>
          </div>

          <span class="control-error" v-text="errors.first('master_genres')" v-if="errors.has('master_genres')">
          </span>
        </div>

        <div class="control-group" :class="[errors.has('master_subgenres') ? 'has-error' : '']">
          <label for="master_subgenres">
            {{ __('admin::app.cinema.master.master_subgenres') }}
          </label>

          <select class="control  js-example-basic-multiple" id="master_subgenres" type="text" name="master_subgenres[]"
            data-vv-as="&quot;{{ __('admin::app.cinema.master.master_subgenres') }}&quot;"  multiple="multiple">

            @foreach ($sottogeneri as $sottogenere)
              <option value="{{ $sottogenere->id }}">{{ $sottogenere->subge_name }}</option>
            @endforeach
          </select>

          <div class="select-icon-container">
            <span class="select-icon rango-arrow-down"></span>
          </div>

          <span class="control-error" v-text="errors.first('master_subgenres')" v-if="errors.has('master_subgenres')">
          </span>
        </div>

        <div class="control-group">
          <label for="master_vt18">{{ __('admin::app.cinema.master.master_vt18') }}</label>
          <label class="switch">
            <input type="checkbox" id="master_vt18" name="master_vt18" value="1">
          </label>
        </div>

        <div class="control-group">
          <label for="master_bn">{{ __('admin::app.cinema.master.master_bn') }}</label>
          <label class="switch">
            <input type="checkbox" id="master_bn" name="master_bn" value="1">
          </label>
        </div>

        <div class="control-group" :class="[errors.has('country') ? 'has-error' : '']">
          <label for="country" class="mandatory">
            {{ __('admin::app.cinema.master.country') }}
          </label>

          <select class="control  js-example-basic-multiple" id="country" type="text" name="country[]"
            v-validate="'required'"
            data-vv-as="&quot;{{ __('admin::app.cinema.master.master_country') }}&quot;"  multiple="multiple">

            @foreach ($countries as $country)
              <option value="{{ $country->code }}">{{ $country->name }}</option>
            @endforeach
          </select>

          <div class="select-icon-container">
            <span class="select-icon rango-arrow-down"></span>
          </div>

          <span class="control-error" v-text="errors.first('country')" v-if="errors.has('country')">
          </span>
        </div>

        <div class="control-group" :class="[errors.has('master_year') ? 'has-error' : '']">
          <label for="master_year"
            class="mandatory">{{ __('admin::app.cinema.master.master_year') }}</label>

          <input class="control" type="number" name="master_year"
            v-validate="'required'"
            min="1888"
            max="{{ date('Y'); }}"
            data-vv-as="&quot;{{ __('admin::app.cinema.master.master_year') }}&quot;">

          <span class="control-error" v-text="errors.first('master_year')" v-if="errors.has('master_year')">
          </span>
        </div>

        <div class="control-group" :class="[errors.has('master_director') ? 'has-error' : '']">
          <label for="master_director"
            class="mandatory">{{ __('admin::app.cinema.master.master_director') }}</label>

          <input class="control" type="text" name="master_director"
            v-validate="'required'"
            data-vv-as="&quot;{{ __('admin::app.cinema.master.master_director') }}&quot;">

          <span class="control-error" v-text="errors.first('master_director')" v-if="errors.has('master_director')">
          </span>
        </div>

        <div class="control-group" :class="[errors.has('master_actors') ? 'has-error' : '']">
          <label for="attmaster_actorsori"
            class="mandatory">{{ __('admin::app.cinema.master.master_actors') }}</label>

          <input class="control" type="text" name="master_actors"
            v-validate="'required'"
            data-vv-as="&quot;{{ __('admin::app.cinema.master.master_actors') }}&quot;">

          <span class="control-error" v-text="errors.first('master_actors')" v-if="errors.has('master_actors')">
          </span>
        </div>

        <div class="control-group" :class="[errors.has('master_scriptwriters') ? 'has-error' : '']">
          <label for="master_scriptwriters"
            class="mandatory">{{ __('admin::app.cinema.master.master_scriptwriters') }}</label>

          <input class="control" type="text" name="master_scriptwriters"
            v-validate="'required'"
            data-vv-as="&quot;{{ __('admin::app.cinema.master.master_scriptwriters') }}&quot;">

          <span class="control-error" v-text="errors.first('master_scriptwriters')" v-if="errors.has('master_scriptwriters')">
          </span>
        </div>

        <div class="control-group" :class="[errors.has('master_musiccomposers') ? 'has-error' : '']">
          <label for="master_musiccomposers"
            class="mandatory">{{ __('admin::app.cinema.master.master_musiccomposers') }}</label>

          <input class="control" type="text" name="master_musiccomposers"
            v-validate="'required'"
            data-vv-as="&quot;{{ __('admin::app.cinema.master.master_musiccomposers') }}&quot;">

          <span class="control-error" v-text="errors.first('master_musiccomposers')" v-if="errors.has('master_musiccomposers')">
          </span>
        </div>

        <div class="control-group" :class="[errors.has('master_studios') ? 'has-error' : '']">
          <label for="master_studios"
            class="mandatory">{{ __('admin::app.cinema.master.master_studios') }}</label>

          <input class="control" type="text" name="master_studios"
            v-validate="'required'"
            data-vv-as="&quot;{{ __('admin::app.cinema.master.master_studios') }}&quot;">

          <span class="control-error" v-text="errors.first('master_studios')" v-if="errors.has('master_studios')">
          </span>
        </div>

        <div class="control-group" :class="[errors.has('language') ? 'has-error' : '']">
          <label for="language" class="mandatory">
            {{ __('admin::app.cinema.master.master_language') }}
          </label>

          <select class="control" id="language" type="text" name="language"
            v-validate="'required'"
            data-vv-as="&quot;{{ __('admin::app.cinema.master.master_language') }}&quot;">

            @foreach ($lingue as $language)
              <option value="{{ $language->code }}">{{ $language->name }}</option>
            @endforeach
          </select>

          <div class="select-icon-container">
            <span class="select-icon rango-arrow-down"></span>
          </div>

          <span class="control-error" v-text="errors.first('language')" v-if="errors.has('language')">
          </span>
        </div>

        <div class="control-group" :class="[errors.has('master_images') ? 'has-error' : '']">
          <label for="master_images"
            class="mandatory">{{ __('admin::app.cinema.master.images') }}</label>

          <input class="control" type="file" name="master_images[]" accept="image/jpeg, image/png"
            v-validate="'required'"
            id="master_images"
            onchange="checkFiles(this.files)"
            data-vv-as="&quot;{{ __('admin::app.cinema.master.images') }}&quot;" multiple>
          <span class="control-info mt-10">{{ __('shop::app.customer.account.master.create.limits') }}</span>
          <span class="control-error" v-text="errors.first('master_images')" v-if="errors.has('master_images')">
          </span>
        </div>

        <div class="control-group" :class="[errors.has('master_type') ? 'has-error' : '']">
          <label for="master_type" class="required">{{ __('admin::app.cinema.master.master_type') }}</label>
          <select class="control js-example-basic-multiple" v-validate="'required'" id="master_type"
            name="master_type" data-vv-as="&quot;{{ __('admin::app.cinema.master.master_type') }}&quot;">
            <option {{ old('master_type') && old('master_type') == 'movie' ? 'selected' : '' }} value="movie">
              MOVIE</option>
            <option {{ old('master_type') && old('master_type') == 'movie-episode' ? 'selected' : '' }}
              value="movie-episode">MOVIE-EPISODE</option>
            <option {{ old('master_type') && old('master_type') == 'movie-episode-TV' ? 'selected' : '' }}
              value="movie-episode-TV">MOVIE-SERIE TV</option>
          </select>
          <span class="control-error" v-if="errors.has('master_type')">@{{ errors.first('master_type') }}</span>
        </div>

      </div>

      <div class="page-action">
        <button type="submit" class="btn btn-lg btn-primary">
          {{ __('admin::app.cinema.master.save-btn-title') }}
        </button>
      </div>
    </form>
  {!! view_render_event('bagisto.shop.customers.account.master.create.after') !!}
@endsection

@push('scripts')
  <script>
    function checkFiles(files) {
        if(files.length>3) {
            alert("Puoi caricare al massimo 3 file.");

            document.getElementById('master_images').value = "";
        } else{
          for(let i=0; i < 3; i++){
            if(files[i].size > 3145728){
               alert("Le dimensioni del file superano il massimo consentito.");
               document.getElementById('master_images').value = "";
            }
          }
        }
    }
  </script>
@endpush
