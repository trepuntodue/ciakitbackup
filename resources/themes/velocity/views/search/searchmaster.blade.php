{{-- @inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar') --}}

@extends('shop::layouts.master')

@section('page_title')
  {{ __('shop::app.search.page-title') }}
@endsection

@push('css')
  <style type="text/css">
    .category-container {
      min-height: unset;
    }

    .toolbar-wrapper .col-4:first-child {
      display: none !important;
    }

    .toolbar-wrapper .col-4:last-child {
      right: 0;
      position: absolute;
    }

    @media only screen and (max-width: 992px) {
      .main-content-wrapper .vc-header {
        box-shadow: unset;
      }

      .toolbar-wrapper .col-4:last-child {
        left: 175px;
      }

      .toolbar-wrapper .sorter {
        left: 35px;
        position: relative;
      }

      .quick-view-btn-container,
      .rango-zoom-plus,
      .quick-view-in-list {
        display: none;
      }
    }
  </style>
@endpush

@section('content-wrapper')
  <div class="category-page-wrapper container">
    <search-component></search-component>
  </div>
@endsection

@push('scripts')
  <script type="text/x-template" id="seach-component-template">
        <section class="search-container row category-container">
          
            @if (
                $results
                && $results['master']->total()
            )
         
                <div class="filters-container col-12" style="
                    margin-top: 20px;
                    padding-left: 0px !important;
                    padding-bottom: 10px !important;
                ">
                    {{-- @include ('shop::products.list.toolbar') --}}
                </div>
            @endif
            @php $nsa =$results['master']->total(); @endphp
            @if($results['master'])
            @php  $nsa +=$results['master']->total(); @endphp
            @else
            @php  $nsa +=0; @endphp
            @endif
            @php // $nsa =$nsa+$results['master']->total();  @endphp
            @if (! $results)
                <h2 class="fw6 col-12 text-themeColor-500 relative z-10 mt-8 mb-8 text-2xl font-bold">
                  {{ __('shop::app.search.no-results') }}</h2>
             @else
                @if ($nsa==0) 
                    <h2 class="fw6 col-12 text-themeColor-500 relative z-10 mt-8 mb-8 text-2xl font-bold">
                      {{ __('shop::app.products.whoops') }}</h2>
                    <span class="col-12">{{ __('shop::app.search.no-results') }}</span>
                @else
                    @if ($nsa == 1)
                        <h5 class="fw6 col-12  text-themeColor-500 relative z-10 mt-8 mb-8 text-2xl font-bold">
                            {{ $nsa }} {{ __('shop::app.search.found-result') }}
                        </h5>
                    @else
                        <h2 class="fw6 col-12  text-themeColor-500 relative z-10 mt-8 mb-8 text-2xl font-bold">
                            {{-- $nsa --}} {{ __('shop::app.search.found-results') }}
                        </h2>
                    @endif

                    <!-- master grid results -->

                    <div class="list grid w-3/4 grid-cols-3 gap-3">

                      @foreach ($results['master'] as $master)
                      <div class="card">    

                        @if ((config('app.env')) === "localhost")

                          <a href="/masters/{{$master->master_id}}/{{$master->url_key}}" rel="{{ $master->master_maintitle }}">          
                            <img src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" />
                          </a>

                        @else

                          @if($master->image)

                            <a href="/masters/{{$master->master_id}}/{{$master->url_key}}" rel="{{ $master->master_maintitle }}">
                              <img src=" {{ url('cache/medium/' . $master->image) }} " alt="{{ $master->master_maintitle }}">
                            </a>

                          @else
                          
                          <a href="/masters/{{$master->master_id}}/{{$master->url_key}}" rel="{{ $master->master_maintitle }}">          
                            <img src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" />
                          </a>

                          @endif

                        @endif

                        <div class="title title--original">
                          <a href="/masters/{{$master->master_id}}/{{$master->url_key}}" rel="{{ $master->master_maintitle }}">  
                            {{ $master->master_maintitle }}
                          </a>
                        </div>

                        <ul class="info">
                          {{-- @if($master->genres_name)
                            <li class="genres" data-value="{{ $master->genres_name }}">{{ $master->genres_name }}</li>
                          @endif --}}
                          @if($master->master_year)
                            <li class="year" data-value="{{ $master->master_year }}">{{ $master->master_year }}</li>
                          @endif
                          {{-- @if($master->lang_name)
                            <li li class="languages" data-value="{{ $master->lang_name }}">{{ $master->lang_name }}</li>
                          @endif --}}
                        </ul>
                        <ul class="info master_director-wrapper notranslate">
                          {{-- @if($master->director_name)
                            <li class="master_director">{{ $master->director_name }}</li>
                          @endif --}}
                        </ul>

                      </div>
                      @endforeach

                    </div>

                    {{-- <ul>

                      @foreach ($results['master'] as $master) 
                      @php $media =url('cache/small/'.$master->image); @endphp
                      @if($media)
                        <div class="product-image-group">
                            <div class="row col-12">
                                <img loading="lazy" alt="" src="{{$media}}" onerror="this.src='https://dev.ciakit.it/vendor/webkul/ui/assets/images/product/small-product-placeholder.png'" class="card-img-top">
                            </div>

                        </div>
                        @endif
                        <li> 
                          {{$master->master_maintitle}} <br/> {{$master->master_type}}  <br/> {{$master->master_description}}  <br/> 
                          {{$master->url_key}} 
                        </li>
                        @endforeach                  
                    
                    </ul> --}}

                    @include('ui::datagrid.pagination')
                @endif     
            @endif
        </section>  
    </script>

  <script>
    Vue.component('search-component', {
      template: '#seach-component-template',
    });

    Vue.component('image-search-result-component', {
      template: '#image-search-result-component-template',

      data: function() {
        return {
          searched_terms: [],
          searchedImageUrl: localStorage.searchedImageUrl,
        }
      },

      created: function() {
        if (localStorage.searched_terms && localStorage.searched_terms != '') {
          this.searched_terms = localStorage.searched_terms.split('_');

          this.searched_terms = this.searched_terms.map(term => {
            return {
              name: term,
              slug: term.split(' ').join('+'),
            }
          });
        }
      }
    });
  </script>
@endpush
