@extends('shop::layouts.master')

{{-- @inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('customHelper', 'Webkul\Velocity\Helpers\Helper') --}}

@section('page-detail-wrapper')
  @if (!$releases)
    <div>nessun risultato</div>

    @php
      abort(404);
    @endphp
  @else
    {{-- @foreach ($release as $release) --}}

    {{-- <div>TITOLO ORIGINALE: {{$releases->original_title}}</div>
    <div>ALTRI TITOLI: {{$releases->other_title}}</div>
    <div>ANNO DI EDIZIONE: {{$releases->release_year}}</div>
    <div>PAESE DI EDIZIONE: {{ core()->country_name($releases->country) }}</div>
    <div>CASA DI DISTRIBUZIONE: {{$releases->release_distribution}}</div>
    <div>TIPO: {{$releases->releasetype}}</div>
    <div>LANGUAGE: {{$releases->language}}</div> --}}

    @php
      $releaseImages = [];
      // $images = mastersrepository()->getGalleryImages($release);
      //  echo '<pre>';print_r($release);

      // die();
      $images = $images;
      // echo '<pre>';print_r($images);
      // die();

      foreach ($images as $key => $image) {
          $media = url('cache/medium/' . $images[$key]->path);
          // echo '<pre>';print_r($key); echo ' ---- '; print_r($image);
          // die();
          array_push($releaseImages, $media);
      }
      //  echo '<pre>';print_r($releaseImages);
      //  die();
    @endphp

    @section('page_title')
      {{ trim($releases->meta_title) != '' ? $releases->meta_title : $releases->original_title }} - Ciakit
    @stop

    @section('seo')
      <meta name="description"
        content="{{ trim($releases->meta_description) != '' ? $releases->meta_description : \Illuminate\Support\Str::limit(strip_tags($releases->original_title), 120, '') }}" />

      <meta name="keywords" content="{{ $releases->meta_keywords }}" />

      @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {!! app('PSW\Cinema\Film\Helpers\SEORelease')->getProductJsonLd($release) !!}
        </script>
      @endif

      <?php
      //$releaseBaseImage = productimage()->getProductBaseImage($release, $images);
      ?>

      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:title" content="{{ $releases->original_title }}" />
      <meta name="twitter:description"
        content="{{ trim($releases->release_description) != '' ? $releases->release_description : $releases->original_title }}" />
      <meta name="twitter:image:alt" content="" />
      {{-- <meta name="twitter:image" content="{{ $releaseBaseImage['medium_image_url'] }}" /> --}}
      <meta name="twitter:image" content="{{ $releaseImages ? $releaseImages[0] : '' }}" />
      <meta property="og:type" content="og:product" />
      <meta property="og:title" content="{{ $releases->original_title }}" />
      {{-- <meta property="og:image" content="{{ $releaseBaseImage['medium_image_url'] }}" /> --}}
      <meta property="og:image" content="{{ $releaseImages ? $releaseImages[0] : '' }}" />
      <meta property="og:description"
        content="{{ trim($releases->release_description) != '' ? $releases->release_description : $releases->original_title }}" />
      <meta property="og:url" content="{{ route('shop.productOrCategory.index', $releases->url_key) }}" />

    @stop

    @push('css')
      <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"> <!-- PWS#18-2 -->
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

    {{-- release page --}}

    @section('full-content-wrapper')
      {!! view_render_event('bagisto.shop.products.view.before', ['releases' => $releases]) !!}

      <div class="row no-margin release">
        <section class="w-full bg-black">

          <div class="flex relative min-h-[10vh]">

            <div class="inset-0 z-20 flex items-center">
              <div class="container mx-auto px-4 py-16 sm:px-0">
                <h1 class="text-shadow-lg text-3xl font-bold text-white sm:text-5xl">
                  {{ $releases->original_title }}
                </h1>
                <div class="mt-4 flex gap-2 text-white">
                  <a class="hover:text-themeColor-500 hover:underline hover:underline-offset-1" href="/">Home</a>
                  <span>></span>
                  <a class="hover:text-themeColor-500 hover:underline hover:underline-offset-1" href="/masters">Lista
                    film</a>
                  <span>></span>
                  <span><a class="hover:text-themeColor-500 hover:underline hover:underline-offset-1"
                      href="/masters/{{ $releases->master_id }}/{{ $releases->master_url_key }}">{{ $releases->master_maintitle }}</a></span>
                  <span>></span>
                  <span>{{ $releases->original_title }}</span>
                </div>
              </div>
            </div>

            <div class="absolute inset-0 z-10 bg-black bg-opacity-20"></div>

            <img width="100%" height="150" class="absolute h-full w-full object-cover object-center"
              src="{{ asset('/themes/velocity/assets/images/banner-home.webp') }}" alt="ciakit">

            {{-- @php
              $imageData = [];

              foreach ($images as $key => $image) {
                  $imageData[$key]['type'] = '';
                  $imageData[$key]['large_image_url'] = url('cache/large/' . $image->path);
                  $imageData[$key]['small_image_url'] = url('cache/small/' . $image->path);
                  $imageData[$key]['medium_image_url'] = url('cache/medium/' . $image->path);
                  $imageData[$key]['original_image_url'] = Storage::url($image->path);
              }
              // dd($imageData);
            @endphp

            @if (config('app.env') === 'localhost')
              <img src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" width="100%" height="150"
                class="absolute h-full w-full object-cover object-center" />
            @else
              <img class="absolute h-full w-full object-cover object-center overflow-hidden blur-lg" width="100%" height="150"
                src="{{ $imageData[0]['large_image_url'] }}" type="{{ $imageData[0]['type'] }}">
            @endif --}}
          </div>

        </section>

        <section class="movie product-detail container">
          <div class="layouter">
            <product-view>
              <div class="form-container">
                @csrf()

                <input type="hidden" name="product_id" value="{{ $releases->master_id }}">

                <div class="mb-8 flex flex-col items-center gap-4 sm:gap-8 md:flex-row">

                  {{-- product-gallery --}}

                  <div class="relative m-0 w-full md:w-5/12">
                    {{-- @include ('shop::cinema.release.view.gallery') --}}
                    @include ('shop::cinema.release.view.carousel')
                  </div>

                  {{-- right-section --}}
                  <div class="m-0 w-full md:w-7/12">
                    {{-- product-info-section --}}
                    <div class="info">
                      <span class="block px-0 text-3xl font-bold text-themeColor-500"> <!-- PWS#chiusura -->
                        {{ $releases->original_title }}
                      </span>

                      <div class="mt-4 mb-4 flex flex-col items-center gap-4 px-8 sm:flex-row sm:px-0">
                        <!-- PWS#02-23 -->

                        <a class="btn release @if ($favorite) removeFromCollection btn--black @else addToCollection @endif favorite group relative"
                          href="#"
                          onclick="addToCollection(this, {{ $releases->id }}, 'favorite', 'release'); return false;"> <!-- PWS#chiusura -->

                          @if (!Auth::check())
                            <span class="label-over group-hover:flex">
                              accedi per aggiungere il film ai preferiti
                            </span>
                          @endif

                          <div class="flex items-center justify-center gap-2">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                              class="h-8 w-8 flex-shrink-0 @if (!$favorite) fill-black @else fill-themeColor-500 @endif"> <!-- PWS#chiusura -->
                              <g>
                                <path
                                  d="M20.68 13.908v-.01c-.88-.93-2.11-1.5-3.48-1.5-2.65 0-4.8 2.15-4.8 4.8 0 1.23.47 2.35 1.23 3.2.88.98 2.15 1.6 3.57 1.6 2.65 0 4.8-2.15 4.8-4.8 0-1.27-.5-2.43-1.32-3.29Zm-1.69 4.1h-1.04v1.09c0 .41-.34.75-.75.75s-.75-.34-.75-.75v-1.09h-1.04c-.42 0-.75-.33-.75-.75 0-.41.32-.74.74-.75h1.05v-1c0-.02 0-.04.01-.06.02-.38.35-.69.74-.69.4 0 .73.32.75.72v1.03h1.04c.42 0 .75.34.75.75 0 .42-.33.75-.75.75Z" />
                                <path
                                  d="M22 8.73c0 1.19-.19 2.29-.52 3.31-.06.21-.31.27-.49.14a6.346 6.346 0 0 0-3.79-1.24c-3.47 0-6.3 2.83-6.3 6.3 0 1.08.28 2.14.81 3.08.16.28-.03.64-.33.53-2.41-.82-7.28-3.81-8.86-8.81C2.19 11.02 2 9.92 2 8.73c0-3.09 2.49-5.59 5.56-5.59 1.81 0 3.43.88 4.44 2.23a5.549 5.549 0 0 1 4.44-2.23c3.07 0 5.56 2.5 5.56 5.59Z" />
                              </g>
                            </svg>
                            <span class='in_collection btn-action'
                              style='@if (!$favorite) display:none; @endif'>
                              Nei tuoi preferiti
                            </span>
                            <span class='out_collection btn-action'
                              style='@if ($favorite) display:none; @endif'>
                              Aggiungi ai preferiti
                            </span>
                          </div>
                        </a>

                        <a class="btn release @if ($collection) removeFromCollection btn--black @else addToCollection @endif collection group relative"
                          href="#"
                          onclick="addToCollection(this, {{ $releases->id }}, 'collection', 'release'); return false;"> <!-- PWS#chiusura -->

                          @if (!Auth::check())
                            <span class="label-over group-hover:flex">
                              accedi per aggiungere il film alla tua collezione
                            </span>
                          @endif

                          <div class="flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 416.723 416.723" xml:space="preserve"
                              class="h-8 w-8 flex-shrink-0 @if (!$collection) fill-black @else fill-themeColor-500  @endif"> <!-- PWS#chiusura -->
                              <path
                                d="M88.57 64.705H16.002C7.164 64.705 0 71.871 0 80.705v255.312c0 8.832 7.164 16 16.002 16H88.57c8.834 0 16-7.168 16-16V80.705c0-8.834-7.166-16-16-16zM72.084 261.92H32.486v-24.381h39.598v24.381zm0-43.24H32.486V107.097h39.598V218.68zM212.57 64.705h-72.564c-8.838 0-16 7.166-16 16v255.312c0 8.832 7.162 16 16 16h72.564c8.838 0 16-7.168 16-16V80.705c0-8.834-7.162-16-16-16zM196.086 261.92H156.49v-24.381h39.596v24.381zm0-43.24H156.49V107.097h39.596V218.68zM416.15 313.047 350.072 76.61c-2.285-8.185-10.775-12.964-18.959-10.679l-67.205 18.782c-8.188 2.288-12.969 10.778-10.678 18.959l66.082 236.438c2.282 8.185 10.772 12.963 18.954 10.679l67.207-18.779c8.187-2.291 12.966-10.781 10.677-18.963zm-97.125-89.998-28.882-103.338 36.675-10.25 28.879 103.338-36.672 10.25zm11.19 40.035-6.307-22.574 36.674-10.25 6.307 22.578-36.674 10.246z" />
                            </svg>
                            <span class='in_collection btn-action'
                              style='@if (!$collection) display:none; @endif'>
                              Nella tua collezione
                            </span>
                            <span class='out_collection btn-action'
                              style='@if ($collection) display:none; @endif'>
                              Aggiungi alla tua collezione
                            </span>
                          </div>
                        </a> <!-- PWS#related-release-2 -->
                      </div>
                      <ul>
                        <!-- <li><span>Altri titoli:</span>{{ $releases->other_title }}</li> -->
                        <!-- PWS#13-release -->
                        @if ($releases->genres_name)
                          <li><span class='label'>Genere:</span><span class='notranslate'>{{ $releases->genres_name }}</span></li>
                        @endif <!-- PWS#7 -->
                        @if ($releases->subgenres_name)
                          <li><span class='label'>Sottogenere:</span><span class='notranslate'>{{ $releases->subgenres_name }}</span></li>
                        @endif
                        @if ($releases->release_year)
                          <li><span class='label'>Anno di edizione:</span><span class='notranslate'>{{ $releases->release_year }}</span></li>
                        @endif
                        @if ($releases->country)
                          <li><span class='label'>Paese di edizione:</span><span class='notranslate'>{{ $releases->country }}</span></li>
                        @endif
                        @if ($releases->director_name)
                          <li><span class='label'>Regista:</span><span class='notranslate'>{{ $releases->director_name }}</span></li>
                        @endif
                        <!-- <li><span>Sceneggiatori:</span>{{ $releases->sceneggiatori_name }}</li> -->
                        <!-- PWS#7 -->
                        @if ($releases->attori_name)
                          <li><span class='label'>Cast:</span><span class='notranslate'>{{ $releases->attori_name }}</span></li> <!-- PWS#04-23 -->
                        @endif <!-- PWS#10 -->
                        <!-- <li><span>Musiche:</span>{{ $releases->compositori_name }}</li> -->
                        <!-- PWS#7 -->
                        @if ($releases->casaproduzione_nome)
                          <li><span class='label'>Casa di produzione:</span><span class='notranslate'>{{ $releases->casaproduzione_nome }}</span></li>
                        @endif <!-- PWS#7 -->
                        @if ($releases->release_distribution)
                          <li><span class='label'>Casa di distribuzioni:</span><span class='notranslate'>{{ $releases->release_distribution }}</span></li>
                        @endif <!-- PWS#7 -->
                        @if ($releases->poster_tipo)
                          <li><span class='label'>Originale | Riproduzione:</span><span class='notranslate'>{{ $releases->poster_tipo }}</span></li>
                        @endif
                        @if ($releases->poster_formato)
                          <li><span class='label'>Formato:</span><span class='notranslate'>{{ $releases->poster_formato }}</span></li>
                        @endif
                        @if ($releases->poster_misure)
                          <li><span class='label'>Misure:</span><span class='notranslate'>{{ $releases->poster_misure }}</span></li>
                        @endif
                        @if ($releases->formato)
                          <li><span class='notrlabelanslate'>Formato:</span><span class='notranslate'>{{ $releases->formato }}</span></li>
                        @endif
                        @if ($releases->numero_catalogo)
                          <li><span class='label'>Numero di catalogo:</span><span class='notranslate'>{{ $releases->numero_catalogo }}</span></li>
                        @endif
                        @if ($releases->illustratore)
                          <li><span class='label'>Illustratore/i:</span><span class='notranslate'>{{ $releases->illustratore }}</span></li>
                        @endif
                        @if ($releases->stampatore)
                          <li><span class='label'>Stampato da:</span><span class='notranslate'>{{ $releases->stampatore }}</span></li>
                        @endif
                      </ul>
                    </div>

                    {{-- <div class="mt-4 mb-4 flex flex-col items-center gap-4 px-8 sm:flex-row sm:px-0">
                      <!-- PWS#02-23 -->

                      <a class="btn release buy group relative" href="#">

                        <div class="flex items-center justify-center gap-2">
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" xml:space="preserve"
                            class="h-8 w-8 flex-shrink-0">
                            <circle style="fill:none;stroke:#000;stroke-width:2;stroke-miterlimit:10" cx="22"
                              cy="24" r="2" />
                            <circle style="fill:none;stroke:#000;stroke-width:2;stroke-miterlimit:10" cx="13"
                              cy="24" r="2" />
                            <path
                              d="m25.658 10-2.422 9H10.781L8.159 8.515A2 2 0 0 0 6.219 7H4a1 1 0 0 0 0 2h2.219L8.84 19.485A2.001 2.001 0 0 0 10.781 21h12.455c.902 0 1.692-.604 1.93-1.474L27.764 10h-2.106z" />
                            <path style="fill:none;stroke:#000;stroke-width:2;stroke-miterlimit:10" d="M17 7v6" />
                            <path d="m21 12-4 4-4-4z" />
                          </svg>
                          <span class='btn-action'>Compra</span>
                        </div>

                      </a>

                      <a class="btn btn--black release buy group relative flex items-center gap-2"
                        href="{{ route('customer.product.create-step-two', $releases->id) }}">
                        <!-- PWS#prod -->

                        <div class="flex items-center justify-center gap-2">
                          <svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"
                            class="fill-themeColor-500 h-8 w-6 flex-shrink-0">
                            <path
                              d="M704 288h131.072a32 32 0 0 1 31.808 28.8L886.4 512h-64.384l-16-160H704v96a32 32 0 1 1-64 0v-96H384v96a32 32 0 0 1-64 0v-96H217.92l-51.2 512H512v64H131.328a32 32 0 0 1-31.808-35.2l57.6-576a32 32 0 0 1 31.808-28.8H320v-22.336C320 154.688 405.504 64 512 64s192 90.688 192 201.664v22.4zm-64 0v-22.336C640 189.248 582.272 128 512 128c-70.272 0-128 61.248-128 137.664v22.4h256zm201.408 483.84L768 698.496V928a32 32 0 1 1-64 0V698.496l-73.344 73.344a32 32 0 1 1-45.248-45.248l128-128a32 32 0 0 1 45.248 0l128 128a32 32 0 1 1-45.248 45.248z" />
                          </svg>
                          <span class='btn-action'>Vendi</span>
                        </div>

                      </a>
                    </div> --}} <!-- PWS#finale -->

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

              @if ($releases->releasetype != config('constants.release.tipo.poster'))
                <!-- Tab links -->
                <div class="tab flex flex-row gap-1 sm:gap-4">
                  <section class="movie description tablinks active container" onclick="openTab(event, 0)">
                    <h2>
                      {{ __('shop::app.customer.account.release.view.info_aggiuntive') }}</h2>
                  </section>
                  <section class="movie description tablinks container" onclick="openTab(event, 1)">
                    <h2>
                      {{ __('shop::app.customer.account.release.view.descrizione') }}</h2>
                  </section>
                </div>

                <!-- Tab content -->
                <div id="tab-0" class="tabcontent" style="display:block;">

                  <div class="info px-3">
                    <ul>
                      @if ($releases->lingua)
                        <li><span class='label'>Lingua:</span><span class='notranslate'>{{ $releases->lingua }}</span></li>
                      @endif <!-- PWS#02-23 -->
                      <li><span class='label'>Sottotitoli disponibili:</span><span class='notranslate'>
                        @if ($releases->subtitles == 1)
                          Sì
                        @else
                          No
                        @endif
                      </span></li>
                      <li><span class='label'>B/N - Colori:</span><span class='notranslate'>
                        @if ($releases->master_bn == 1)
                          B/N
                        @else
                          Colori
                        @endif
                      </span></li>
                      @if ($releases->durata)
                        <li><span class='label'>Durata:</span><span class='notranslate'>{{ $releases->durata }} min</span></li>
                      @endif
                      @if ($releases->release_type)
                        <li><span class='label'>Supporto:</span><span class='notranslate'>{{ $releases->release_type }}</span></li>
                      @endif
                      @if ($releases->aspect_ratio)
                        <li><span class='label'>Aspect ratio:</span><span class='notranslate'>{{ $releases->aspect_ratio }}</span></li>
                      @endif
                      @if ($releases->camera_format)
                        <li><span class='label'>Camera format/video:</span><span class='notranslate'>{{ $releases->camera_format }}</span></li>
                      @endif
                      @if ($releases->tipologia)
                        <li><span class='label'>Tipologia di edizione:</span><span class='notranslate'>{{ $releases->tipologia }}</span></li>
                      @endif
                      @if ($releases->canali_audio)
                        <li><span class='label'>Canali audio:</span><span class='notranslate'>{{ $releases->canali_audio }}</span></li>
                      @endif
                      @if ($releases->barcode)
                        <li><span class='label'>Barcode:</span><span class='notranslate'>{{ $releases->barcode }}</span></li>
                      @endif
                      @if ($releases->crediti)
                        <li><span class='label'>Crediti:</span><span class='notranslate'>{{ $releases->crediti }}</span></li>
                      @endif
                      @if ($releases->contenuti_speciali)
                        <li><span class='label'>Contenuti speciali:</span><span class='notranslate'>{{ $releases->contenuti_speciali }}</span></li>
                      @endif
                    </ul>
                  </div>

                </div>

                <div id="tab-1" class="tabcontent" style="display:none;">
                  <div class="info px-4">
                    <p>
                      @if ($releases->release_description)
                        <span class='notranslate'>{{ $releases->release_description }}</span>
                      @elseif ($releases->master_description)
                        <span class='notranslate'>{{ $releases->master_description }}</span>
                      @endif
                    </p>
                  </div>
                </div>
              @elseif($releases->release_description)
                <section class="movie description container">
                  <h2 class="text-themeColor-500 mb-4 text-2xl font-bold">Descrizione</h2>
                  @if ($releases->release_description)
                    <span class='notranslate'>{{ $releases->release_description }}</span>
                  @elseif ($releases->master_description)
                    <span class='notranslate'>{{ $releases->master_description }}</span>
                  @endif
                </section>
              @endif

            </product-view>
          </div>
        </section>

        @if ($products)
          <section class="movie release container">
            <h2 class="text-themeColor-500 mb-4 text-2xl font-bold">
              Prodotti in vendita di questa edizione
            </h2>
            @include('shop::cinema.release.view.related-products')
          </section>
        @endif <!-- PWS#18-2 -->

        @if ($related_releases)
          <!-- PWS#02-23-x PWS#video-poster -->
          <section class="movie release container px-4">
            <h2 class="text-themeColor-500 mb-4 mt-4 text-2xl font-bold">
              @if ($releases->releasetype == config('constants.release.tipo.poster'))
                {{ __('shop::app.customer.account.release.view.related_poster') }}
              @else
                {{ __('shop::app.customer.account.release.view.related_video') }}
              @endif
            </h2>
            @include('shop::cinema.release.view.related-releases')
          </section>
        @endif

      </div>
      {{-- view_render_event('bagisto.shop.products.view.after', ['releases' => $release]) --}}
    @endsection

    @push('scripts')
      <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> <!-- PWS#18-2 -->
      <script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>

      <meta name="_token" content="{{ csrf_token() }}" /> <!-- PWS#13-release -->
      <script>
        // global app configuration object
        var config = {
          routes: {
            addToCollection: "{{ route('customer.addToCollection') }}"
          },
          params: {
            user_id: @php
              if (Auth::check()) {
                  echo Auth::id();
              } else {
                  echo -1;
              }
            @endphp,
            table: 'release',
          }
        };
      </script>
      <script type="text/javascript" src="{{ asset('themes/velocity/assets/js/pw_frontend.js') }}"></script> <!-- PWS#13-release -->

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
      <script>
        // TABS
        function openTab(evt, tab_num) {
          // Declare all variables
          var i, tabcontent, tablinks;

          // Get all elements with class="tabcontent" and hide them
          tabcontent = document.getElementsByClassName("tabcontent");
          for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
          }

          // Get all elements with class="tablinks" and remove the class "active"
          tablinks = document.getElementsByClassName("tablinks");
          for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
          }

          // Show the current tab, and add an "active" class to the button that opened the tab
          document.getElementById('tab-' + tab_num).style.display = "block";
          evt.currentTarget.className += " active";
        }
      </script>
      <style>
        .tab section.container {
          width: auto;
        }

        .tab section:hover {
          cursor: pointer;
        }

        .tab {
          display: inline-flex;
        }

        .tablinks:not(.active) h2 {
          color: rgba(0, 0, 0, 0.83) !important;
        }

        /* Go from zero to full opacity */
        @keyframes fadeEffect {
          from {
            opacity: 0;
          }

          to {
            opacity: 1;
          }
        }
      </style>
    @endpush
    {{-- @endforeach --}}
  @endif
@endsection
