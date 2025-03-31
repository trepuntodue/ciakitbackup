@extends('shop::layouts.master')

{{-- @inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('customHelper', 'Webkul\Velocity\Helpers\Helper') --}}

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
      {{ trim($master->master_maintitle) != '' ? $master->master_maintitle : $master->master_maintitle }} - Ciakit
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
      <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"> <!-- PWS#18 -->
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

    {{-- master page --}}

    @section('full-content-wrapper')
      {!! view_render_event('bagisto.shop.products.view.before', ['master' => $master]) !!}

      <div class="row no-margin master">
        <section class="w-full bg-black">

          <div class="flex relative min-h-[10vh] overflow-hidden">

            <div class=" inset-0 z-20 flex items-center">
              <div class="container mx-auto px-4 py-16 sm:px-0">
                <h1 class="text-shadow-lg text-3xl font-bold text-white sm:text-5xl">
                  {{ $master->master_maintitle }}
                </h1>
                <div class="mt-4 flex gap-2 text-white">
                  <a class="hover:text-themeColor-500 hover:underline hover:underline-offset-1" href="/">Home</a>
                  <span>></span>
                  <a class="hover:text-themeColor-500 hover:underline hover:underline-offset-1" href="/masters">Lista
                    film</a>
                  <span>></span>
                  <span>{{ $master->master_maintitle }}</span>
                </div>
              </div>
            </div>

            <div class="absolute inset-0 z-10 bg-black bg-opacity-20"></div>
            {{--
            <img width="100%" height="150" class="h-full w-full object-cover object-center"
              src="{{ asset('/themes/velocity/assets/images/banner-home.webp') }}" alt="ciakit"> --}}

            @php
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
            @elseif($imageData)
              <!-- PWS#18-2 -->
              <img class="absolute h-full w-full object-cover object-center blur-lg" width="100%" height="150"
                src="{{ $imageData[0]['large_image_url'] }}" type="{{ $imageData[0]['type'] }}">
            @endif

          </div>

        </section>

        <section class="product-detail movie container">
          <div class="layouter">
            <product-view>
              <div class="form-container">
                @csrf()

                <input type="hidden" name="product_id" value="{{ $master->master_id }}">

                <div class="mb-8 flex flex-col items-center gap-4 sm:gap-8 md:flex-row">

                  {{-- product-gallery --}}

                  <div class="relative m-0 w-full md:w-5/12">

                    {{-- @include ('shop::cinema.master.view.lightbox') --}}
                    @include ('shop::cinema.master.view.carousel')
                    {{-- @include ('shop::cinema.master.view.gallery') --}}

                  </div>

                  {{-- right-section --}}
                  <div class="m-0 w-full md:w-7/12">
                    {{-- product-info-section --}}
                    <div class="info">
                      <span class="mb-4 block px-0 text-3xl font-bold">
                        {{ $master->master_maintitle }}
                      </span>
                      <ul>
                        @if ($master->master_othertitle)
                          <li><span class='label'>Altri titoli:</span><span class='notranslate'>{{ $master->master_othertitle }}</span></li>
                        @endif
                        @if ($master->genres_name)
                          <li><span class='label'>Genere:</span><span class='notranslate'>{{ $master->genres_name }}</span></li> <!-- PWS#7 -->
                        @endif
                        @if ($master->subgenres_name)
                          <li><span class='label'>Sottogenere:</span><span class='notranslate'>{{ $master->subgenres_name }}</span></li>
                        @endif
                        @if ($master->master_year)
                          <li><span class='label'>Anno:</span><span class='notranslate'>{{ $master->master_year }}</span></li>
                        @endif
                        @if ($master->country)
                          <li><span class='label'>Paesi:</span><span class='notranslate'>{{ $master->country }}</span></li>
                        @endif
                        @if ($master->director_name)
                          <li><span class='label'>Regista:</span><span class='notranslate'>
                          @php

                          $directors = explode(", ",$master->director_name);
                          foreach($directors as $key => $director){
                            $director = explode("_*_",$director);
                            $director_id = $director[0];
                            $director_name = trim($director[1]);
                            $separator = "";
                            if ($key !== array_key_last($directors)) {
                                $separator = ", ";
                            }
                            echo "<a href='/masters?regista={$director_id}'>{$director_name}</a>{$separator}";
                          }
                          @endphp
                          </span></li></a>
                        @endif
                        @if ($master->sceneggiatori_name)
                          <li><span class='label'>Sceneggiatori:</span><span class='notranslate'>{{ $master->sceneggiatori_name }}</span></li> <!-- PWS#7 -->
                        @endif
                        @if ($master->attori_name)
                          <li><span class='label'>Cast:</span><span class='notranslate'>
                            @php

                            $attori = explode(", ",$master->attori_name);
                            foreach($attori as $key => $attore){
                              $attore = explode("_*_",$attore);
                              $attore_id = $attore[0];
                              $attore_name = trim($attore[1]);
                              $separator = "";
                              if ($key !== array_key_last($attori)) {
                                  $separator = ", ";
                              }
                              echo "<a href='/masters?attore={$attore_id}'>{$attore_name}</a>{$separator}";
                            }
                            @endphp
                          </span></li> <!-- PWS#10 -->
                          <!-- PWS#04-23 -->
                        @endif
                        @if ($master->compositori_name)
                          <li><span class='label'>Musiche:</span><span class='notranslate'>{{ $master->compositori_name }}</span></li> <!-- PWS#7 -->
                        @endif
                        @if ($master->casaproduzione_nome)
                          <li><span class='label'>Case di produzione:</span><span class='notranslate'>{{ $master->casaproduzione_nome }}</span></li> <!-- PWS#7 -->
                        @endif
                        <li><span class='label'>B/N - Colori:</span><span class='notranslate'>
                          @if ($master->master_bn == 1)
                            B/N
                          @else
                            Colori
                          @endif
                        </span></li> <!-- PWS#13-bn -->
                        @if ($master->lingua)
                          <li><span class='label'>Lingue:</span><span class='notranslate'>{{ $master->lingua }}</span></li> <!-- PWS#02-23 -->
                        @endif
                        @if ($master->master_type)
                          <li><span class='label'>Tipo:</span><span class='notranslate'>{{ $master->master_type }}</span></li>
                        @endif
                        <li><span class='label'>Vietato ai minori:</span><span class='notranslate'> <!-- PWS#04-23 -->
                          @if ((int) $master->master_vt18 == 1)
                            Sì
                          @else
                            No
                          @endif
                        </span></li>
                        <!-- PWS#13-vt18-2 -->
                      </ul>
                    </div>

                    @if (1 == 2)
                      <!-- nascondo i preferiti -->
                      <div class="mt-4 mb-4 flex flex-col items-center gap-4 px-8 sm:flex-row sm:px-0">

                        <a class="btn master @if ($favorite) removeFromCollection @else addToCollection @endif favorite group relative"
                          href="#"
                          onclick="addToCollection(this, {{ $master->master_id }}, 'favorite', 'master'); return false;">

                          @if (!Auth::check())
                            <span class="label-over group-hover:flex">accedi per aggiungere il film ai preferiti</span>
                          @endif

                          <div class="flex items-center justify-center gap-2">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                              class="h-8 w-8 flex-shrink-0 fill-black">
                              <g>
                                <path
                                  d="M20.68 13.908v-.01c-.88-.93-2.11-1.5-3.48-1.5-2.65 0-4.8 2.15-4.8 4.8 0 1.23.47 2.35 1.23 3.2.88.98 2.15 1.6 3.57 1.6 2.65 0 4.8-2.15 4.8-4.8 0-1.27-.5-2.43-1.32-3.29Zm-1.69 4.1h-1.04v1.09c0 .41-.34.75-.75.75s-.75-.34-.75-.75v-1.09h-1.04c-.42 0-.75-.33-.75-.75 0-.41.32-.74.74-.75h1.05v-1c0-.02 0-.04.01-.06.02-.38.35-.69.74-.69.4 0 .73.32.75.72v1.03h1.04c.42 0 .75.34.75.75 0 .42-.33.75-.75.75Z" />
                                <path
                                  d="M22 8.73c0 1.19-.19 2.29-.52 3.31-.06.21-.31.27-.49.14a6.346 6.346 0 0 0-3.79-1.24c-3.47 0-6.3 2.83-6.3 6.3 0 1.08.28 2.14.81 3.08.16.28-.03.64-.33.53-2.41-.82-7.28-3.81-8.86-8.81C2.19 11.02 2 9.92 2 8.73c0-3.09 2.49-5.59 5.56-5.59 1.81 0 3.43.88 4.44 2.23a5.549 5.549 0 0 1 4.44-2.23c3.07 0 5.56 2.5 5.56 5.59Z" />
                              </g>
                            </svg>
                            <span class='in_collection btn-action'
                              style='@if (!$favorite) display:none; @endif'>Nei tuoi preferiti</span>
                            <span class='out_collection btn-action'
                              style='@if ($favorite) display:none; @endif'>Aggiungi ai preferiti</span>
                          </div>

                        </a>

                        <a class="btn btn--black master addToCollection collection group relative"
                          href="@if (Auth::check()) {{ route('customer.release.create', $master->master_id) }} @endif">

                          @if (!Auth::check())
                            <span class="label-over group-hover:flex">
                              accedi per proporre una nuova versione
                            </span>
                          @endif

                          <div class="flex flex-wrap items-center justify-center gap-2">
                            <svg class="fill-themeColor-500 h-6 w-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <g fill-rule="nonzero" fill="none">
                                <path
                                  d="M24 0v24H0V0h24ZM12.593 23.258l-.011.002-.071.035-.02.004-.014-.004-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01-.017.428.005.02.01.013.104.074.015.004.012-.004.104-.074.012-.016.004-.017-.017-.427c-.002-.01-.009-.017-.017-.018Zm.265-.113-.013.002-.185.093-.01.01-.003.011.018.43.005.012.008.007.201.093c.012.004.023 0 .029-.008l.004-.014-.034-.614c-.003-.012-.01-.02-.02-.022Zm-.715.002a.023.023 0 0 0-.027.006l-.006.014-.034.614c0 .012.007.02.017.024l.015-.002.201-.093.01-.008.004-.011.017-.43-.003-.012-.01-.01-.184-.092Z" />
                                <path
                                  d="M20.245 14.75c.935.614.892 2.037-.129 2.576l-7.181 3.796a2 2 0 0 1-1.87 0l-7.181-3.796c-1.02-.54-1.064-1.962-.129-2.576l.063.04 7.247 3.832a2 2 0 0 0 1.87 0l7.181-3.796a1.59 1.59 0 0 0 .13-.076Zm0-4a1.5 1.5 0 0 1 0 2.501l-.129.075-7.181 3.796a2 2 0 0 1-1.707.077l-.162-.077-7.182-3.796c-1.02-.54-1.064-1.962-.129-2.576l.063.04 7.247 3.832a2 2 0 0 0 1.708.077l.162-.077 7.181-3.796a1.59 1.59 0 0 0 .13-.076Zm-7.31-7.872 7.181 3.796c1.066.563 1.066 2.09 0 2.652l-7.181 3.796a2 2 0 0 1-1.87 0L3.884 9.327c-1.066-.563-1.066-2.089 0-2.652l7.181-3.796a2 2 0 0 1 1.87 0Z"
                                  class="fill-themeColor-500" />
                              </g>
                            </svg>

                            <span class="btn-action">Proponi nuova versione</span>
                          </div>

                        </a> <!-- PWS#related-release-2 -->
                      </div>
                    @endif

                    <div class="mt-4 mb-4 flex flex-col items-center gap-4 px-8 sm:flex-row sm:px-0">
                      <a class="btn master group relative"
                        href="{{ route('shop.cinema.releases.list') }}?tipo=video&master={{ $master->master_id }}">

                        <div class="flex items-center justify-center gap-2">

                          <svg class="h-8 w-8 flex-shrink-0" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                            <path
                              d="M30.335 26.445c-.128-.221-.538-.289-1.231-.202-1.275.165-2.201-.251-2.778-1.251-.437-.756-.55-1.516-.339-2.28s.836-1.854 1.874-3.272l.649-.904c1.928-2.663 2.323-4.979 1.186-6.95-1.157-2.006-4.5-1.837-6.903-.806C20.849 6.771 16.753 4 12 4 5.372 4 0 9.373 0 16s5.372 12 12 12c6.627 0 12-5.373 12-12 0-1.469-.277-2.871-.76-4.171a9.297 9.297 0 0 1 2.696-.536c1.202-.044 2.29.208 2.722.956.963 1.669.498 3.641-1.395 5.913l-.388.466c-2.214 2.657-2.743 4.984-1.591 6.982.466.806 1.207 1.381 2.225 1.727 1.018.345 1.879.312 2.585-.096.35-.202.431-.468.241-.796zM6.5 18.562a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm5.5 5.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm-.98-8a1.001 1.001 0 1 1 2.002.002 1.001 1.001 0 0 1-2.002-.002zm.98-3a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm5.5 5.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z" />
                          </svg>

                          <span class='out_collection btn-action'>Edizioni video</span>
                        </div>

                      </a>

                      <a class="btn btn--black master group relative"
                        href="{{ route('shop.cinema.releases.list') }}?tipo=poster&master={{ $master->master_id }}">

                        <div class="flex items-center justify-center gap-2">
                          <svg class="fill-themeColor-500 h-8 w-8" viewBox="0 0 256 256"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                              d="M231.385 46.541a12.002 12.002 0 0 0-10.295-2.183l-59.694 14.924-60.03-30.015c-.026-.013-.054-.02-.08-.034a11.962 11.962 0 0 0-1.295-.546c-.072-.026-.145-.044-.217-.068q-.561-.187-1.141-.317c-.117-.027-.233-.05-.35-.073q-.555-.108-1.122-.162c-.092-.01-.183-.023-.274-.03a11.929 11.929 0 0 0-2.79.123c-.146.023-.29.057-.436.086-.19.038-.38.065-.571.112l-64 16A12 12 0 0 0 20 56v144a12 12 0 0 0 14.91 11.642l59.694-14.924 60.03 30.015c.052.026.106.043.159.068q.493.238 1.007.432c.06.023.12.049.18.07a11.965 11.965 0 0 0 1.224.364c.064.015.127.026.19.04q.541.12 1.095.19l.229.03a10.948 10.948 0 0 0 2.839-.034c.17-.022.339-.065.509-.095.281-.049.563-.086.844-.156l64-16A12 12 0 0 0 236 200V56a11.999 11.999 0 0 0-4.615-9.459ZM44 65.37l40-10v119.262l-40 10Zm104 131.215-40-20V59.416l40 20Zm64-5.953-40 10V81.369l40-10Z" />
                          </svg>

                          <span class='out_collection btn-action'>Edizioni poster</span>
                        </div>
                      </a> <!-- PWS#02-23 -->
                    </div>

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

      @if ($related_releases)
        <section class="movie release container">
          <h2 class="text-themeColor-500 mb-4 px-4 text-2xl font-bold">
            Edizioni collegate
          </h2>
          @include('shop::cinema.master.view.releases-list')
        </section>
      @endif <!-- PWS#13-release -->

      @if ($master->master_description)
        <section class="movie description container mx-auto mb-4 mt-4 px-4 md:px-0">
          <h2 class="text-themeColor-500 mb-4 text-2xl font-bold">
            Descrizione / note
          </h2>
          <span class='notranslate'>{{ $master->master_description }}</span>
        </section>
      @endif

      @if ($master->master_trama)
        <!-- PWS#02-23-x -->
        <section class="movie trama container mx-auto mb-4 mt-4 px-4">
          <!-- PWS#02-23 -->
          <h2 class="text-themeColor-500 mb-4 text-2xl font-bold">Trama</h2>
          <span class='notranslate'>{{ $master->master_trama }}</span>
        </section>
      @endif

      @if ($relatedmaster)
        <section class="movie related-products container mt-4">
          <div class="px-4">
            <h2 class="text-themeColor-500 mb-4 text-2xl font-bold">Altri film simili</h2>
            <div class="carousel">
              @include('shop::cinema.master.view.related-products')
              {{-- @include('shop::products.view.up-sells') --}}
            </div>
          </div>
        </section> <!-- PWS#chiusura -->
      @endif

      </div>
      {!! view_render_event('bagisto.shop.products.view.after', ['master' => $master]) !!}
    @endsection

    @push('scripts')
      <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> <!-- PWS#18 -->
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
            table: 'master',
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
