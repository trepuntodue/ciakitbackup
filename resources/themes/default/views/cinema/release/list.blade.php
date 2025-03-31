@extends('shop::layouts.master')

{{-- @inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('customHelper', 'Webkul\Velocity\Helpers\Helper') --}}

@section('page_title')
  {{ trim($releases->meta_title) != '' ? $releases->meta_title : $releases->original_title }}
@endsection
@php//echo '<pre>';print_r($releases);
@endphp
@section('page-detail-wrapper')
  @if (!$releases)
    <div>nessun risultato</div>
  @else
    {{-- @foreach ($releases as $releases) --}}

    {{-- <div>TITOLO ORIGINALE: {{$releases->original_title}}</div>
    <div>ALTRI TITOLI: {{$releases->other_title}}</div>
    <div>ANNO DI EDIZIONE: {{$releases->release_year}}</div>
    <div>PAESE DI EDIZIONE: {{ core()->country_name($releases->country) }}</div>
    <div>CASA DI DISTRIBUZIONE: {{$releases->release_distribution}}</div>
    <div>TIPO: {{$releases->releasetype}}</div>
    <div>LANGUAGE: {{$releases->language}}</div> --}}

    @php
      $releasesImages = [];
      // $images = mastersrepository()->getGalleryImages($releases);
      //  echo '<pre>';print_r($releases);
      
      // die();
      $images = $images;
      // echo '<pre>';print_r($images);
      // die();
      
      foreach ($images as $key => $image) {
          $media = url('cache/medium/' . $images[$key]->path);
          // echo '<pre>';print_r($key); echo ' ---- '; print_r($image);
          // die();
          array_push($releasesImages, $media);
      }
      //  echo '<pre>';print_r($releasesImages);
      //  die();
    @endphp

    @section('page_title')
      {{ trim($releases->meta_title) != '' ? $releases->meta_title : $releases->original_title }}
    @stop

    @section('seo')
      <meta name="description"
        content="{{ trim($releases->meta_description) != '' ? $releases->meta_description : \Illuminate\Support\Str::limit(strip_tags($releases->original_title), 120, '') }}" />

      <meta name="keywords" content="{{ $releases->meta_keywords }}" />

      @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {!! app('PSW\Cinema\Film\Helpers\SEORelease')->getProductJsonLd($releases) !!}
        </script>
      @endif

      <?php
      //$releasesBaseImage = productimage()->getProductBaseImage($releases, $images);
      ?>

      <meta name="twitter:card" content="summary_large_image" />

      <meta name="twitter:title" content="{{ $releases->original_title }}" />

      <meta name="twitter:description"
        content="{{ trim($releases->release_description) != '' ? $releases->release_description : $releases->original_title }}" />

      <meta name="twitter:image:alt" content="" />

      {{-- <meta name="twitter:image" content="{{ $releasesBaseImage['medium_image_url'] }}" /> --}}
      <meta name="twitter:image" content="{{ $releasesImages ? $releasesImages[0] : '' }}" />

      <meta property="og:type" content="og:product" />

      <meta property="og:title" content="{{ $releases->original_title }}" />

      {{-- <meta property="og:image" content="{{ $releasesBaseImage['medium_image_url'] }}" /> --}}
      <meta property="og:image" content="{{ $releasesImages ? $releasesImages[0] : '' }}" />

      <meta property="og:description"
        content="{{ trim($releases->release_description) != '' ? $releases->release_description : $releases->original_title }}" />

      <meta property="og:url" content="{{ route('shop.productOrCategory.index', $releases->url_key) }}" />
    @stop

    @push('css')
      <style type="text/css">
        .related-products {
          width: 100%;
        }

        .recently-viewed {
          margin-top: 20px;
        }

        .store-meta-images>.recently-viewed:first-child {
          margin-top: 0px;
        }

        .main-content-wrapper {
          margin-bottom: 0px;
        }

        .buynow {
          height: 40px;
          text-transform: uppercase;
        }
      </style>
    @endpush

    @section('full-content-wrapper')
      {!! view_render_event('bagisto.shop.products.view.before', ['releases' => $releases]) !!}
      <div class="row no-margin">
        <section class="col-12 product-detail">
          <div class="layouter">
            <product-view>
              <div class="form-container">
                @csrf()

                <input type="hidden" name="product_id" value="{{ $releases->id }}">

                <div class="row">
                  {{-- product-gallery --}}
                  <div class="left col-lg-5 col-md-6">
                    @include ('shop::cinema.release.view.gallery')
                  </div>

                  {{-- right-section --}}
                  <div class="right col-lg-7 col-md-6">
                    {{-- product-info-section --}}
                    <div class="info">
                      <h2 class="col-12">{{ $releases->original_title }}</h2>

                    </div>

                    @if (Auth::check())
                      <a class="btn btn--black" href="{{ route('customer.release.create', $releases->master_id) }}">Duplica
                        release</a> <!-- PWS#230101 -->
                    @endif

                    {{-- @include ('shop::products.view.short-description') --}}

                    {{-- product long description --}}
                    {{-- @include ('shop::products.view.description') --}}

                    {{-- reviews count --}}
                    {{-- @include ('shop::products.view.reviews', ['accordian' => true]) --}}
                  </div>
                </div>
              </div>
            </product-view>
          </div>
        </section>

        <div class="related-products">
          {{-- @include('shop::products.view.related-products') --}}
          {{-- @include('shop::products.view.up-sells') --}}
        </div>
      </div>
      {!! view_render_event('bagisto.shop.products.view.after', ['releases' => $releases]) !!}
    @endsection

    @push('scripts')
      <script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>

      <script type="text/javascript" src="{{ asset('themes/velocity/assets/js/jquery-ez-plus.js') }}"></script>

      <script type='text/javascript' src='https://unpkg.com/spritespin@4.1.0/release/spritespin.js'></script>

      {{-- <script>
        Vue.component('product-view', {
            inject: ['$validator'],
            template: '#product-view-template',
            data: function () {
                return {
                    slot: true,
                    is_buy_now: 0,
                }
            },

            mounted: function () {
                let currentProductId = '{{ $releases->url_key }}';
                let existingViewed = window.localStorage.getItem('recentlyViewed');

                if (! existingViewed) {
                    existingViewed = [];
                } else {
                    existingViewed = JSON.parse(existingViewed);
                }

                if (existingViewed.indexOf(currentProductId) == -1) {
                    existingViewed.push(currentProductId);

                    if (existingViewed.length > 3)
                        existingViewed = existingViewed.slice(Math.max(existingViewed.length - 4, 1));

                    window.localStorage.setItem('recentlyViewed', JSON.stringify(existingViewed));
                } else {
                    var uniqueNames = [];

                    $.each(existingViewed, function(i, el){
                        if ($.inArray(el, uniqueNames) === -1) uniqueNames.push(el);
                    });

                    uniqueNames.push(currentProductId);

                    uniqueNames.splice(uniqueNames.indexOf(currentProductId), 1);

                    window.localStorage.setItem('recentlyViewed', JSON.stringify(uniqueNames));
                }
            },

            methods: {
                onSubmit: function(event) {
                    if (event.target.getAttribute('type') != 'submit')
                        return;

                    event.preventDefault();

                    this.$validator.validateAll().then(result => {
                        if (result) {
                            this.is_buy_now = event.target.classList.contains('buynow') ? 1 : 0;

                            setTimeout(function() {
                                document.getElementById('product-form').submit();
                            }, 0);
                        }
                    });
                },
            }
        });

        window.onload = function() {
            var thumbList = document.getElementsByClassName('thumb-list')[0];
            var thumbFrame = document.getElementsByClassName('thumb-frame');
            var productHeroImage = document.getElementsByClassName('product-hero-image')[0];

            if (thumbList && productHeroImage) {
                for (let i=0; i < thumbFrame.length ; i++) {
                    thumbFrame[i].style.height = (productHeroImage.offsetHeight/4) + "px";
                    thumbFrame[i].style.width = (productHeroImage.offsetHeight/4)+ "px";
                }

                if (screen.width > 720) {
                    thumbList.style.width = (productHeroImage.offsetHeight/4) + "px";
                    thumbList.style.minWidth = (productHeroImage.offsetHeight/4) + "px";
                    thumbList.style.height = productHeroImage.offsetHeight + "px";
                }
            }

            window.onresize = function() {
                if (thumbList && productHeroImage) {

                    for(let i=0; i < thumbFrame.length; i++) {
                        thumbFrame[i].style.height = (productHeroImage.offsetHeight/4) + "px";
                        thumbFrame[i].style.width = (productHeroImage.offsetHeight/4)+ "px";
                    }

                    if (screen.width > 720) {
                        thumbList.style.width = (productHeroImage.offsetHeight/4) + "px";
                        thumbList.style.minWidth = (productHeroImage.offsetHeight/4) + "px";
                        thumbList.style.height = productHeroImage.offsetHeight + "px";
                    }
                }
            }
        };
    </script> --}}
    @endpush
    {{-- @endforeach --}}
  @endif
@endsection
