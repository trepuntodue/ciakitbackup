@extends('shop::layouts.master')
@inject ('productRatingHelper', 'Webkul\Product\Helpers\Review')

@php
  $channel = core()->getCurrentChannel();

  $homeSEO = $channel->home_seo;

  if (isset($homeSEO)) {
      $homeSEO = json_decode($channel->home_seo);

      $metaTitle = $homeSEO->meta_title;

      $metaDescription = $homeSEO->meta_description;

      $metaKeywords = $homeSEO->meta_keywords;
  }
@endphp

@section('page_title')
  {{ isset($metaTitle) ? $metaTitle : '' }}
@endsection

@section('head')
  @if (isset($homeSEO))
    @isset($metaTitle)
      <meta name="title" content="{{ $metaTitle }}" />
    @endisset

    @isset($metaDescription)
      <meta name="description" content="{{ $metaDescription }}" />
    @endisset

    @isset($metaKeywords)
      <meta name="keywords" content="{{ $metaKeywords }}" />
    @endisset
  @endif
@endsection

@push('css')
  @if (!empty($sliderData))
    <link rel="preload" as="image" href="{{ Storage::url($sliderData[0]['path']) }}">
  @else
    <link rel="preload" as="image" href="{{ asset('/themes/velocity/assets/images/banner.webp') }}">
  @endif

  <style type="text/css">
    .product-price span:first-child,
    .product-price span:last-child {
      font-size: 18px;
      font-weight: 600;
    }
  </style>
@endpush

@section('content-wrapper')
  {{-- @include('shop::home.slider') --}}

  @if(1==2)
    <div class="hero-movie">
      <div class="info">
        <div class="container px-4 py-4 mx-auto text-white sm:px-0 sm:py-8">
          {{-- <h1 class="mb-6 text-2xl sm:text-6xl">Benvenuti su Ciakit</h1> --}} <!-- PWS#chiusura -->

          {{-- <form class="relative my-8" action="">
            <input class="w-full h-12 p-4 rounded-full placeholder:text-xl placeholder:font-semibold" type="text"
              placeholder="cerca il tuo film">
            <input class="absolute top-0 right-0 h-12 px-4 rounded-full bg-gradient-to-t from-rose-500 to-orange-600"
              type="button" value="CERCA">
          </form> --}}

          <div class="max-w-xl searchbar"> <!-- PWS#chiusura -->
            @include('shop::cinema.master.search-bar')

            {{-- velocity::shop.layouts.particals.search-bar
            @include('velocity::shop.layouts.particals.search-bar') --}} <!-- PWS#chiusura -->
          </div>

        </div>
      </div>
      {{-- <img width="100%" height="200" class="object-cover object-center"
        src="{{ asset('/themes/velocity/assets/images/banner-home.webp') }}" alt="ciakit"> --}}
    </div>
  @endif
@endsection

{{-- @section('content')
  <!-- Blog -->
  @include('shop::home.latest_articles')
  @include('shop::home.new-products')
@endsection --}}

@section('full-content-wrapper')
  @include('shop::home.latest_articles')

  {{-- <!-- blog placeholder -->
  <div class="hidden px-4 my-8 overflow-hidden full-content-wrapper bglight sm:my-0 sm:py-4">
    <div
      class="container grid grid-cols-1 justify-items-stretch sm:mt-8 sm:gap-8 md:grid-flow-col md:grid-cols-2 md:grid-rows-2">

      <div class="relative w-full overflow-hidden sm:mb-0 sm:pb-[65%] md:row-span-2">

        <div class="absolute inset-0 z-10 hidden w-full h-full bg-gradient-to-b from-transparent to-black sm:block"></div>

        <img class="relative inset-0 z-0 object-cover w-full h-96 sm:absolute sm:h-full"
          src='https://dev.ciakit.it/blog/wp-content/uploads/2023/07/intervistaNICCOLO-FALSETTI1.jpg' alt='' />

        <div class="relative bottom-0 left-0 z-20 w-full pt-2 sm:absolute sm:p-8">
          <h3 class="text-2xl text-themeColor-500 sm:mb-4">Intervista Ciakit - Niccolò Falsetti</h3>
          <p class="text-black sm:text-white">Intervista a Niccolò Falsetti, regista e autore di Margini.</p>
          <a target="_blank" class="underline text-themeColor-500 hover:text-themeColor-600 hover:decoration-2"
            href="https://dev.ciakit.it/blog/">Leggi tutto...</a>
        </div>

      </div>

      <div class="flex flex-col col-span-2 pt-8 sm:flex-row sm:gap-8 sm:py-0">

        <div class="w-full sm:w-1/3">
          <img class="relative z-0 object-cover w-full h-full aspect-3/4"
            src='https://dev.ciakit.it/blog/wp-content/uploads/2023/04/cinecomics.jpg' alt='' />
        </div>
        <div class="flex flex-col gap-2 sm:w-2/3">
          <h3 class="mt-2 text-xl text-themeColor-500">Cinecomics</h3>
          <p>Non c’è dubbio, oggi i cosiddetti Cinecomics hanno invaso e saturato, nel bene e nel male,
            il
            mercato cinematografico e televisivo mondiale; l’eterna sfida fra DC Comics e Marvel, su questo
            versante decisamente vinta da quest’ultima, si è trasferita dalla carta stampata agli schermi e, in
            questi ultimissimi anni, allo streaming.</p>
          <a class="underline text-themeColor-500 hover:text-black" href="#">Leggi tutto...</a>
        </div>
      </div>

      <div class="flex flex-col col-span-2 pt-8 sm:flex-row sm:gap-8 sm:py-0">
        <div class="w-full sm:w-1/3">
          <img class="w-full h-full opacity-50" src='https://dummyimage.com/500x400.gif' alt='' />
        </div>
        <div class="flex flex-col gap-2 sm:w-2/3">
          <h3 class="mt-2 text-xl text-themeColor-500">Titolo articolo</h3>
          <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Iusto sapiente asperiores aliquam! </p>
          <a class="underline text-themeColor-500 hover:text-black" href="#">Leggi tutto...</a>
        </div>
      </div>

    </div>
  </div> --}}

  <!-- most viewed carousel-->
  <div class="px-4 overflow-hidden full-content-wrapper bglight sm:py-4">
    <div class="container">
      <h2 class="relative z-10 text-2xl font-bold text-themeColor-500 sm:mt-8">Movie</h2> <!-- PWS#04-23 -->
      <div class="carousel">
        @include('velocity::home.carousel-last')
      </div>
    </div>
    <a class="mx-auto my-4 btn" href="/masters?master_type=movie">Visualizza tutti</a> <!-- PWS#chiusura -->
  </div>

  <!-- last insert carousel-->
  <div class="px-4 overflow-hidden full-content-wrapper bgdark sm:py-4">
    <div class="container">
      <h2 class="relative z-10 text-2xl font-bold text-themeColor-500 sm:mt-8">Serie TV</h2>
      <div class="carousel">
        @include('velocity::home.carousel-series')
      </div>
    </div>
    <a class="mx-auto my-4 btn" href="/masters?master_type=serie">Visualizza tutti</a> <!-- PWS#chiusura -->
  </div>

  <!-- most viewed carousel-->
  <div class="px-4 overflow-hidden bg-gray-100 full-content-wrapper sm:py-4">
    <div class="container">
      <h2 class="relative z-10 text-2xl font-bold text-themeColor-500 sm:mt-8">Poster</h2> <!-- PWS#04-23 -->
      <div class="carousel">
        @include('velocity::home.carousel-poster')
      </div>
    </div>
    <a class="mx-auto my-4 btn" href="/releases?tipo=poster">Visualizza tutti</a> <!-- PWS#finale-1 PWS#chiusura -->
  </div>

  <!-- intro ciakit -->
  {{-- <div class="container full-content-wrapper">
    <div class="flex flex-col items-center max-w-5xl gap-4 mx-auto center-content sm:mt-16 sm:flex-row sm:gap-16">
      <div class="sm:w-1/3">
        <img src="/storage/img/ciakit-placeholder.jpg" alt="placeholder">
      </div>
      <div class="sm:w-2/3">
        <h3 class="mb-6 text-3xl">Come funziona Ciakit</h3>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et fugit, maiores consectetur, repellat a iure soluta
          perspiciatis quisquam consequatur ipsa quia. Earum, cumque atque! Placeat voluptatibus necessitatibus laboriosam
          nihil totam?</p>
        <a class="my-4 btn" href="/come-funziona-ciakit">Scopri di più</a>
      </div>
    </div>
  </div> --}}

  {{-- <div class="p-4 full-content-wrapper bglight">
    <div class="container">
      <h2 class="relative z-10 mt-8 text-2xl font-bold text-themeColor-500">Template</h2>
        @include('velocity::home.template')
    </div>
  </div> --}}

  <div class="container full-content-wrapper">
    {!! view_render_event('bagisto.shop.home.content.before') !!}

    @if ($velocityMetaData)
      {{-- {!! DbView::make($velocityMetaData)->field('home_page_content')->render() !!} --}}
    @else
      {{-- @include('shop::home.advertisements.advertisement-four') --}}
      {{-- @include('shop::home.featured-products') --}}
      {{-- @include('shop::home.advertisements.advertisement-three') --}}
      {{-- @include('shop::home.new-products') --}}
      {{-- @include('shop::home.advertisements.advertisement-two') --}}
    @endif

    {{ view_render_event('bagisto.shop.home.content.after') }}
  </div>
@endsection
