<nav class="row flex h-full content-center" id="top">
  <div>
    {{-- @include('velocity::layouts.top-nav.locale-currency') --}}

    @php
      //dd(Request::path());
    @endphp

    @if (!Request::is('/'))
      @if (Request::path() !== 'masters')
        <div class="searchbar max-w-xl">
          @include('shop::cinema.master.search-bar')
        </div>
      @endif
    @endif

  </div>
  <div>
    @include('velocity::layouts.top-nav.login-section')
  </div>
</nav>
