@extends('shop::layouts.master')

{{-- @inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('customHelper', 'Webkul\Velocity\Helpers\Helper') --}}

@section('page_title')
  {{ trim($master->meta_title) != '' ? $master->meta_title : $master->master_maintitle }}
@endsection

@section('page-detail-wrapper')
  @if (!$master)
    <div>nessun risultato</div>

    @php
      abort(404);
    @endphp
  @else
    {{-- @foreach ($master as $master) --}}

    {{-- <div>TITOLO ORIGINALE: {{$master->original_title}}</div>
    <div>ALTRI TITOLI: {{$master->other_title}}</div>
    <div>ANNO DI EDIZIONE: {{$master->release_year}}</div>
    <div>PAESE DI EDIZIONE: {{ core()->country_name($master->country) }}</div>
    <div>CASA DI DISTRIBUZIONE: {{$master->release_distribution}}</div>
    <div>TIPO: {{$master->releasetype}}</div>
    <div>LANGUAGE: {{$mxaster->language}}</div> --}}

    @php
      $masterImages = [];
      // $images = mastersrepository()->getGalleryImages($master);
      //  echo '<pre>';print_r($master);
      
      // die();
      $images = $images;
      // echo '<pre>';print_r($images);
      // die();
      
      foreach ($images as $key => $image) {
          $media = url('cache/medium/' . $images[$key]->path);
          // echo '<pre>';print_r($key); echo ' ---- '; print_r($image);
          // die();
          array_push($masterImages, $media);
      }
      //  echo '<pre>';print_r($masterImages);
      //  die();
    @endphp

    @section('page_title')
      {{ trim($master->meta_title) != '' ? $master->meta_title : $master->master_maintitle }}
    @stop

    @section('seo')
      <meta name="description"
        content="{{ trim($master->meta_description) != '' ? $master->meta_description : \Illuminate\Support\Str::limit(strip_tags($master->master_maintitle), 120, '') }}" />

      <meta name="keywords" content="{{ $master->meta_keywords }}" />

      @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {!! app('PSW\Cinema\Film\Helpers\SEO')->getProductJsonLd($master) !!}
        </script>
      @endif

      <?php
      //$masterBaseImage = productimage()->getProductBaseImage($master, $images);
      ?>

      <meta name="twitter:card" content="summary_large_image" />

      <meta name="twitter:title" content="{{ $master->master_maintitle }}" />

      <meta name="twitter:description"
        content="{{ trim($master->master_description) != '' ? $master->master_description : $master->master_maintitle }}" />

      <meta name="twitter:image:alt" content="" />

      {{-- <meta name="twitter:image" content="{{ $masterBaseImage['medium_image_url'] }}" /> --}}
      <meta name="twitter:image" content="{{ $masterImages ? $masterImages[0] : '' }}" />

      <meta property="og:type" content="og:product" />

      <meta property="og:title" content="{{ $master->master_maintitle }}" />

      {{-- <meta property="og:image" content="{{ $masterBaseImage['medium_image_url'] }}" /> --}}
      <meta property="og:image" content="{{ $masterImages ? $masterImages[0] : '' }}" />

      <meta property="og:description"
        content="{{ trim($master->master_description) != '' ? $master->master_description : $master->master_maintitle }}" />

      <meta property="og:url" content="{{ route('shop.productOrCategory.index', $master->url_key) }}" />
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
      {!! view_render_event('bagisto.shop.products.view.before', ['master' => $master]) !!}

      <div class="row no-margin master">
        <section class="movie product-detail container">
          <div class="layouter">
            <product-view>
              <div class="form-container">
                @csrf()

                <input type="hidden" name="product_id" value="{{ $master->master_id }}">

                <div class="row items-center">
                  {{-- product-gallery --}}
                  <div class="left col-lg-5 col-md-6">
                    @include ('shop::cinema.master.view.gallery')
                  </div>

                  {{-- right-section --}}
                  <div class="right col-lg-7 col-md-6">
                    {{-- product-info-section --}}
                    <div class="info">
                      <h1 class="col-12 px-0 text-3xl uppercase">{{ $master->master_maintitle }}</h1>
                      <ul>
                        <li><span>Altri titoli:</span>{{ $master->master_othertitle }}</li>
                        <li><span>Genere:</span>{{ $master->genres_name }}</li> <!-- PWS#7 -->
                        <li><span>Sottogenere:</span>{{ $master->subgenres_name }}</li>
                        <li><span>Anno:</span>{{ $master->master_year }}</li>
                        <li><span>Paese:</span>{{ $master->country }}</li>
                        <li><span>Regista:</span>{{ $master->director_name }}</li>
                        <li><span>Sceneggiatori:</span>{{ $master->sceneggiatori_name }}</li> <!-- PWS#7 -->
                        <li><span>Musiche:</span>{{ $master->compositori_name }}</li> <!-- PWS#7 -->
                        <li><span>Case di produzione:</span>{{ $master->casaproduzione_nome }}</li> <!-- PWS#7 -->
                        <li><span>Lingue:</span>{{ $master->lingua }}</li>
                        <li><span>Tipo:</span>{{ $master->master_type }}</li>
                      </ul>
                    </div>

                    <div class="mt-4 flex gap-8">
                      <a class="btn" href="#">Aggiungi ai preferiti</a>
                      <a class="btn btn--black" href="#">Aggiungi alla tua collezione</a>
                    </div>

                    @php
                      //dd($master);
                    @endphp

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

        <section class="movie release container">
          <h2 class="text-themeColor-500 mb-4 text-2xl font-bold">Altre versioni</h2>
          @include('shop::cinema.master.view.releases-list')
        </section>

        <section class="movie description container">
          <h2 class="text-themeColor-500 mb-4 text-2xl font-bold">Descrizione / note</h2>
          {{ $master->master_description }}
        </section>

        <section class="movie related-products container">
          <h2 class="text-themeColor-500 mb-4 text-2xl font-bold">Altri elementi simili</h2>
          <div class="carousel">
            @include('shop::cinema.master.view.related-products')
            {{-- @include('shop::products.view.up-sells') --}}
          </div>
        </section>
      </div>
      {!! view_render_event('bagisto.shop.products.view.after', ['master' => $master]) !!}
    @endsection

    @push('scripts')
      <script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>

      <script type="text/javascript" src="{{ asset('themes/velocity/assets/js/jquery-ez-plus.js') }}"></script>
      \
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
                let currentProductId = '{{ $master->url_key }}';
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
