{{-- <header class="sticky-header"> --}}
<header>

  <div class="container relative">

    <div class="flex items-center justify-between">

      {{-- <a class="left navbar-brand" href="{{ route('shop.home.index') }}" aria-label="Logo"> --}}

      <div class="flex">

        <a class="w-16 ml-8 sm:ml-4 sm:w-24" href="{{ route('shop.home.index') }}" aria-label="Logo">
          <img class="logo" width="200" height="150"
            src="{{ core()->getCurrentChannel()->logo_url ?? asset('themes/velocity/assets/images/ciakit-logo-white-600.png') }}"
            alt="ciakit" />
        </a>

        <div class="hidden main-menu lg:flex">

          <ul>

            <li>

              <div class="relative px-4 py-4 font-bold text-white cursor-pointer group hover:underline">

                <a class="hover:text-white" href="/masters">Esplora
                  <i class="absolute right-0 text-xl translate-x-2 icon fs16 cell rango-arrow-down"></i></a>

                <div
                  class="absolute flex-col hidden w-64 py-4 mt-2 overflow-hidden font-normal text-left text-black -translate-x-4 rounded-lg shadow-2xl bg-slate-50 hover:flex group-hover:flex">

                  <div class="flex">
                    <ul>
                      <li class="px-4 pb-2 my-0 text-sm font-bold uppercase">
                        GENERI
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block px-4 py-1 cursor-pointer"
                          href="/masters?generi[]=@php echo config('constants.master.generi.animazione'); @endphp">Animazione</a>
                        <!-- PWS#18 -->
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block px-4 py-1 cursor-pointer"
                          href="/masters?generi[]=@php echo config('constants.master.generi.azione'); @endphp">Azione</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block px-4 py-1 cursor-pointer"
                          href="/masters?generi[]=@php echo config('constants.master.generi.commedia'); @endphp">Commedia</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block px-4 py-1 cursor-pointer"
                          href="/masters?generi[]=@php echo config('constants.master.generi.fantascienza'); @endphp">Fantascienza</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block px-4 py-1 cursor-pointer"
                          href="/masters?generi[]=@php echo config('constants.master.generi.thriller'); @endphp">Thriller</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block cursor-pointer py-1 px-4 underline decoration-[1px]" href="/masters">Scopri
                          tutti
                          ></a>
                      </li>
                    </ul>

                    <ul>
                      <li class="px-4 pb-2 my-0 text-sm font-bold uppercase">
                        PAESI
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block px-4 py-1 cursor-pointer" href="/masters?country=FR">Francia</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block px-4 py-1 cursor-pointer" href="/masters?country=DE">Germania</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block px-4 py-1 cursor-pointer" href="/masters?country=IT">Italia</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block px-4 py-1 cursor-pointer" href="/masters?country=ES">Spagna</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block px-4 py-1 cursor-pointer" href="/masters?country=US">Stati Uniti</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block cursor-pointer py-1 px-4 underline decoration-[1px]" href="/masters">Scopri
                          tutti
                          ></a>
                      </li>
                    </ul>

                  </div>

                </div>

              </div>

            </li>

            <li>
            {{--
              <div class="relative px-4 py-4 font-bold text-white cursor-pointer group hover:underline">
                <span>
                  Shop
                  <i class="absolute right-0 text-xl translate-x-2 icon fs16 cell rango-arrow-down"></i>
                </span>

                <div
                  class="absolute flex-col hidden py-4 mt-2 overflow-hidden font-normal text-left text-black -translate-x-4 rounded-lg shadow-2xl w-72 bg-slate-50 hover:flex group-hover:flex">
                  <div class="flex pr-2">
                    <ul class="w-1/3">
                      <li class="pb-2 pl-2 pl-4 my-0 text-sm font-bold uppercase">
                        VIDEO
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block py-1 pl-4 pr-2 cursor-pointer" href="/dvd">DVD</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block py-1 pl-4 pr-2 cursor-pointer" href="/dvd">Blu-Ray</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block py-1 pl-4 pr-2 cursor-pointer" href="/dvd">VHS</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block py-1 pl-4 pr-2 cursor-pointer" href="/dvd">Laser Disc</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block py-1 pl-4 pr-2 cursor-pointer" href="/dvd">Pellicola</a>
                      </li>

                    </ul>

                    <ul class="w-2/3">
                      <li class="pb-2 pl-2 my-0 text-sm font-bold uppercase">
                        POSTER
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block py-1 pl-2 cursor-pointer" href="/dvd">Brochure</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block py-1 pl-2 cursor-pointer" href="/dvd">Playbill/locandina</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block py-1 pl-2 cursor-pointer" href="/dvd">Lobby card/fotobusta</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block py-1 pl-2 cursor-pointer" href="/dvd">One sheet/soggettone</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block py-1 pl-2 cursor-pointer" href="/dvd">2 sheet/poster 2 fogli</a>
                      </li>
                      <li class="my-0 text-sm">
                        <a class="block py-1 pl-2 cursor-pointer" href="/dvd">4 sheet/poster 4 fogli</a>
                      </li>
                    </ul>

                  </div>
                </div>

              </div>
            --}} <!-- PWS#finale -->
            </li>
          </ul>

          @php
            
            //MOD: nascondo il menu dinamico di bagisto
            
            // <right-side-header
            // :header-content="{{ json_encode(app('Webkul\Velocity\Repositories\ContentRepository')->getAllContents()) }}">
            // {{-- this is default content if js is not loaded --}}
            // <ul type="none" class="no-margin">
            // </ul>
            // </right-side-header>
          @endphp

        </div>

      </div>

      {{-- top nav which contains currency, locale and login header --}}

      {{-- @include('shop::layouts.top-nav.index') --}}

      <nav class="content-center h-full row lg:flex" id="top">

        {{-- @include('velocity::layouts.top-nav.locale-currency') --}}

        <div class="hidden lg:block">

          @include('velocity::layouts.top-nav.login-section')

        </div>

      </nav>

      <!-- hamburger menu -->

      <div class="dropdown">

        <button id="menu-mobile"
          class="mx-4 transition-all border-0 rounded-lg bg-themeColor-500 hover:scale-110 focus:outline-none"
          type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

          <div class="p-2 space-y-2 rounded shadow">
            <span class="block h-0.5 w-8 animate-pulse bg-black"></span>
            <span class="block h-0.5 w-8 animate-pulse bg-black"></span>
            <span class="block h-0.5 w-8 animate-pulse bg-black"></span>
          </div>

        </button>

        <div class="menu-mobile flex-column" aria-labelledby="menu-mobile">

          <a class="text-white border-bottom mx-4 py-2 text-uppercase" href="/masters">Esplora</a>

          <div class="flex flex-nowrap border-bottom mx-4">

            <div class="mx-4 my-2">
              <span class="type">Generi</span>
              <ul>
                <li><a href="/masters?generi[]=3">Animazione</a></li>
                <li><a href="/masters?generi[]=5">Azione</a></li>
                <li><a href="/masters?generi[]=9">Commedia</a></li>
                <li><a href="/masters?generi[]=14">Fantascienza</a></li>
                <li><a href="/masters?generi[]=29">Thriller</a></li>
                <li><a href="/masters">Tutti i generi</a></li>
              </ul>
            </div>

            <div class="mx-4 my-2">
              <span class="type">Paesi</span>
              <ul>
                <li><a href="/masters?country=FR">Francia</a></li>
                <li><a href="/masters?country=DE">Germania</a></li>
                <li><a href="/masters?country=IT">Italia</a></li>
                <li><a href="/masters?country=ES">Spagna</a></li>
                <li><a href="/masters?country=US">Stati uniti</a></li>
                <li><a href="/masters">Tutti i paesi</a></li>
              </ul>
            </div>

          </div>

        </div>
      </div>

      {{-- 
      <div class="right searchbar">
        <div class="row">
          <div class="col-lg-5 col-md-12">
            @include('velocity::shop.layouts.particals.search-bar')
          </div>

          <div class="col-lg-7 col-md-12 vc-full-screen">
            <div class="left-wrapper">

              {!! view_render_event('bagisto.shop.layout.header.wishlist.before') !!}

              @include('velocity::shop.layouts.particals.wishlist', ['isText' => true])

              {!! view_render_event('bagisto.shop.layout.header.wishlist.after') !!}

              {!! view_render_event('bagisto.shop.layout.header.compare.before') !!}

              @include('velocity::shop.layouts.particals.compare', ['isText' => true])

              {!! view_render_event('bagisto.shop.layout.header.compare.after') !!}

              {!! view_render_event('bagisto.shop.layout.header.cart-item.before') !!}

              @include('shop::checkout.cart.mini-cart')

              {!! view_render_event('bagisto.shop.layout.header.cart-item.after') !!}
            </div>
          </div>
        </div>
      </div> --}}

    </div>

    <div class="topsearch top-[10px] sm:top-[20px] right-20 z-50 mb-2 px-4 sm:absolute sm:mb-0 lg:right-40">
      @php
        //dd(Request::path());
      @endphp

        @if (Request::path() !== 'masters' && Request::path() !== 'releases')
          <div class="max-w-xl searchbar">
            @include('shop::cinema.master.search-bar')
          </div>
        @endif

    </div>

  </div>
</header>

@push('scripts')
  <script type="text/javascript">
    (() => {
      document.addEventListener('scroll', e => {
        scrollPosition = Math.round(window.scrollY);

        if (scrollPosition > 50) {
          document.querySelector('header').classList.add('header-shadow');
        } else {
          document.querySelector('header').classList.remove('header-shadow');
        }
      });
    })();
  </script>
@endpush
