@extends('admin::layouts.content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.18/vue.min.js"></script>
@php
    $currentCustomer = auth()->guard('customer')->user();
@endphp
@section('page_title')
    {{ __('admin::app.cinema.release.add-title') }}
@stop

@push('css')
    <style>
        .table td .label {
            margin-right: 10px;
        }
        .table td .label:last-child {
            margin-right: 0;
        }
        .table td .label .icon {
            vertical-align: middle;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="content">
        <form method="POST" action="" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.cinema.release.index') }}'"></i>
                        CREATE RELEASE
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        SAVE RELEASE
                    </button>
                </div>
            </div>

            <div class="page-content">
                @csrf()
                {{-- {!! view_render_event('bagisto.admin.catalog.master.edit_form_accordian.master_links.before') !!}

                <div class="control-group"><!----> <!----> <label for="related_masters">
                    Related Master
                </label> <input type="text" autocomplete="off" placeholder="Start typing master original title" class="control"> <div class="linked-master-search-result"><ul> <!----> <!----></ul></div>    <!----></div>
                
                {!! view_render_event('bagisto.admin.catalog.master.edit_form_accordian.master_links.before') !!}
                
                @push('scripts') --}}
                
                <script type="text/x-template" id="linked-masters-template">
                    <div>
                
                        <div class="control-group" >
                     
                            <label for="related_masters" >
                                {{ __('admin::app.cinema.master.related-masters') }}
                            </label>
                
                            <input type="text" class="control" autocomplete="off"  placeholder="{{ __('admin::app.catalog.masters.master-search-hint') }}" >
                
                            <div class="linked-master-search-result">
                                <ul>
                                    <li >
                                        @{{ master.name }}
                                    </li>
                
                                    <li>
                                        {{ __('admin::app.catalog.masters.no-result-found') }}
                                    </li>
                
                                    <li >
                                        {{ __('admin::app.catalog.masters.searching') }}
                                    </li>
                                </ul>
                            </div>
                
                            
                            <input type="hidden" name="related_masters[]" />
                
                            <span class="filter-tag linked-master-filter-tag" >
                                <span class="wrapper linked-master-wrapper " >
                                    <span class="do-not-cross-linked-master-arrow">
                                        @{{ master.name }}
                                    </span>
                                    <span class="icon cross-icon"></span>
                                </span>
                            </span>
                        </div>
                
                    </div>
                </script>
                
                <script>
                
                    // Vue.component('linked-masters', {
                
                    //     template: '#linked-masters-template',
                
                    //     data: function() {
                    //         return {
                    //             masters: {
                      
                    //                 'related_masters': []
                    //             },
                
                    //             search_term: {
                                
                    //                 'related_masters': ''
                    //             },
                
                    //             addedmasters: {
                                
                    //                 'related_masters': []
                    //             },
                
                    //             is_searching: {
                                  
                    //                 'related_masters': false
                    //             },
                
                    //             linkedmasters: [ 'related_masters'],

                
                    //         }
                    //     },
                
                    //     created: function () {
                
                    //         if (this.relatedMasters.length >= 1) {
                    //             for (var index in this.relatedMasters) {
                    //                 this.addedmasters.related_masters.push(this.relatedMasters[index]);
                    //             }
                    //         }
                    //     },
                
                    //     methods: {
                    //         addmaster: function (master, key) {
                    //             this.addedmasters[key].push(master);
                    //             this.search_term[key] = '';
                    //             this.masters[key] = []
                    //         },
                
                    //         removemaster: function (master, key) {
                    //             for (var index in this.addedmasters[key]) {
                    //                 if (this.addedmasters[key][index].id == master.id ) {
                    //                     this.addedmasters[key].splice(index, 1);
                    //                 }
                    //             }
                    //         },
                
                    //         search: function (key) {
                    //             this_this = this;
                
                    //             this.is_searching[key] = true;
                
                    //             if (this.search_term[key].length >= 1) {
                    //                 this.$http.get ("{{ route('admin.cinema.release.masterLinkSearch') }}", {params: {query: this.search_term[key]}})
                    //                     .then (function(response) {
                
                    //                         for (var index in response.data) {
                    //                             if (response.data[index].id == this_this.masterId) {
                    //                                 response.data.splice(index, 1);
                    //                             }
                    //                         }
                
                    //                         if (this_this.addedmasters[key].length) {
                    //                             for (var master in this_this.addedmasters[key]) {
                    //                                 for (var masterId in response.data) {
                    //                                     if (response.data[masterId].id == this_this.addedmasters[key][master].id) {
                    //                                         response.data.splice(masterId, 1);
                    //                                     }
                    //                                 }
                    //                             }
                    //                         }
                
                    //                         this_this.masters[key] = response.data;
                
                    //                         this_this.is_searching[key] = false;
                    //                     })
                
                    //                     .catch (function (error) {
                    //                         this_this.is_searching[key] = false;
                    //                     })
                    //             } else {
                    //                 this_this.masters[key] = [];
                    //                 this_this.is_searching[key] = false;
                    //             }
                    //         }
                    //     }
                    // });
                
                </script>
                
                {{-- @endpush --}}
{{-- 
                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.before') !!}

                <div class="control-group" :class="[errors.has('original_title') ? 'has-error' : '']">
                    <label for="original_title"  class="mandatory">{{ __('shop::app.customer.account.release.create.original_title') }}</label>

                    <input
                        class="control"
                        type="text"
                        name="original_title"
                        value="{{ old('original_title') }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.original_title') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('original_title')"
                        v-if="errors.has('original_title')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.original_title.after') !!} --}}

                {{-- <div class="control-group" :class="[errors.has('other_title') ? 'has-error' : '']">
                    <label for="other_title">{{ __('shop::app.customer.account.release.create.other_title') }}</label>

                    <input
                        class="control"
                        type="text"
                        name="other_title"
                        value="{{ old('other_title') }}"
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
                    <input
                        class="control"
                        type="text"
                        name="release_year"
                        value="{{ old('release_year') }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_year') }}&quot;"> 

                    <span
                        class="control-error"
                        v-text="errors.first('release_year')"
                        v-if="errors.has('release_year')">
                    </span>
                </div>

               


                 {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.country-state.after') !!}

                <div class="control-group" :class="[errors.has('release_distribution') ? 'has-error' : '']">
                    <label for="release_distribution" class="mandatory">{{ __('shop::app.customer.account.release.create.release_distribution') }}</label>

                    <input
                        type="text"
                        class="control"
                        name="release_distribution"
                        value="{{ old('release_distribution') }}"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.release_distribution') }}&quot;">

                    <span
                        class="control-error"
                        v-text="errors.first('release_distribution')"
                        v-if="errors.has('release_distribution')">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.release_distribution.after') !!} --}}
              

                {!! view_render_event('bagisto.shop.customers.account.release.edit_form_controls.releasetype.after') !!} 
                
                {!! view_render_event('bagisto.shop.customers.account.release.edit_form_controls.language.after') !!}
               
                {!! view_render_event('bagisto.shop.customers.account.release.create_form_controls.after') !!}
            </div>
        </form>
    </div>
@stop

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.label .cross-icon').on('click', function(e) {
                $(e.target).parent().remove();
            })

            $('.actions .trash-icon').on('click', function(e) {
                $(e.target).parents('tr').remove();
            })
        });
    </script>
@endpush
