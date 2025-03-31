<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  {{-- title --}}
  <title>Ciakit - Movie Database</title>

  <script src='https://acconsento.click/script.js' id='acconsento-script' data-key='sWLNJKq6eeKg9DJEo03y77pQ7fnv1480ftz5Ys2c'></script>
  
  {{-- meta data --}}
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="base-url" content="{{ url()->to('/') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="max-image-preview:large">

  {!! view_render_event('bagisto.shop.layout.head') !!}

  {{-- for extra head data --}}
  @yield('head')

  {{-- seo meta data --}}
  @yield('seo')

  {{-- fav icon --}}
  @if ($favicon = core()->getCurrentChannel()->favicon_url)
    <link rel="icon" sizes="16x16" href="{{ $favicon }}" />
  @else
    <link rel="icon" sizes="16x16" href="{{ asset('/themes/velocity/assets/images/static/v-icon.png') }}" />
  @endif

  <!-- Google tag (gtag.js) --> 
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-7VJ26RCW99"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-7VJ26RCW99'); </script>

  {{-- all styles --}}
  @include('shop::layouts.styles')

  <script type="text/javascript" src="https://cdn.weglot.com/weglot.min.js"></script>
  <script>
      Weglot.initialize({
          api_key: 'wg_5cfd7ca46d220193e483b8dcb17508748'
      });
  </script>

</head>

<body @if (core()->getCurrentLocale() && core()->getCurrentLocale()->direction === 'rtl') class="rtl" @endif>
  {!! view_render_event('bagisto.shop.layout.body.before') !!}

  {{-- main app --}}
  <div id="app">

    @php
      // MOD: NASCONDO COMPONENTE product-quick-view
    @endphp
    {{-- <product-quick-view v-if="$root.quickView"></product-quick-view> --}}

    <div class="main-container-wrapper">

      @section('body-header')

        {!! view_render_event('bagisto.shop.layout.header.before') !!}

        {{-- primary header after top nav --}}
        @include('shop::layouts.header.index')

        {!! view_render_event('bagisto.shop.layout.header.after') !!}

        {{-- <div class="main-content-wrapper col-12 no-padding"> --}}

        {{-- secondary header --}}

        {{-- <header class="row velocity-divide-page vc-header header-shadow active"> --}}

        {{-- mobile header --}}

        {{-- <div class="container vc-small-screen">

              @include('shop::layouts.header.mobile')
            
              </div> --}}

        {{-- desktop header --}}

        {{-- @include('shop::layouts.header.desktop') --}}

        {{-- </header> --}}

        {{-- <div class="row col-12 remove-padding-margin">

            <sidebar-component main-sidebar=true id="sidebar-level-0" url="{{ url()->to('/') }}"
              category-count="{{ $velocityMetaData ? $velocityMetaData->sidebar_category_count : 10 }}"
              add-class="category-list-container pt10">
            </sidebar-component>

            <div class="col-12 no-padding content" id="home-right-bar-container">

              <div class="container-right row no-margin col-12 no-padding">

                {!! view_render_event('bagisto.shop.layout.content.before') !!}

                @yield('content-wrapper')

                {!! view_render_event('bagisto.shop.layout.content.after') !!}
              </div>

            </div>

          </div> --}}

        {{-- </div> --}}

        {{-- banner benvenuti su ciakit home --}}
        @yield('content-wrapper')
      @show

      {!! view_render_event('bagisto.shop.layout.full-content.before') !!}

      @yield('full-content-wrapper')

      {!! view_render_event('bagisto.shop.layout.full-content.after') !!}

    </div>

    {{-- overlay loader --}}
    <velocity-overlay-loader></velocity-overlay-loader>

    {{-- <go-top bg-color="#26A37C"></go-top> --}}
  </div>

  {{-- footer --}}
  @section('footer')
    {!! view_render_event('bagisto.shop.layout.footer.before') !!}

    @include('shop::layouts.footer.index')

    {!! view_render_event('bagisto.shop.layout.footer.after') !!}
  @show

  {!! view_render_event('bagisto.shop.layout.body.after') !!}

  {{-- alert container --}}
  <div id="alert-container"></div>

  {{-- all scripts --}}
  @include('shop::layouts.scripts')
</body>

</html>
