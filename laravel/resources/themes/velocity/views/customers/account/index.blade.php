@extends('shop::layouts.master')

@section('content-wrapper')
  {{-- account wrapper  --}}

  <div class="relative bg-black">

    <div
      class="container relative z-10 mx-auto flex flex-wrap items-center justify-center gap-4 py-4 sm:h-60 sm:justify-start sm:gap-8">

      <div class="overflow-hidden rounded-full">
        @if (auth('customer')->user())
          @if (auth('customer')->user()->image)
            <div>
              <img class="h-16 w-16 rounded-full sm:h-32 sm:w-32" src="{{ auth('customer')->user()->image_url }}"
                alt="{{ auth('customer')->user()->first_name }}" />
            </div>
          @endif
        @endif
      </div>

      <div class="text-white">
        @if (auth('customer')->user())
          <p class="mb-2 text-xl capitalize sm:mb-4 sm:text-3xl">{{ auth('customer')->user()->first_name }}</p>
          <p>{{ auth('customer')->user()->email }}</p>
        @endif
      </div>
    </div>

    {{-- @if (auth('customer')->user())
      @if (auth('customer')->user()->image)
        <img class="absolute inset-0 z-0 h-full w-full object-cover" src="{{ auth('customer')->user()->image_url }}"
          alt="{{ auth('customer')->user()->first_name }}" />
      @endif
    @endif --}}

    <img class="absolute inset-0 z-0 h-full w-full object-cover"
      src="{{ asset('/themes/velocity/assets/images/banner-home.webp') }}" alt="ciakit">

    <div class="relative z-10 border-white bg-black bg-opacity-70 placeholder:border-t-[1px]">

      {{-- @foreach ($menu->items as $menuItem)
        <ul class="flex flex-wrap items-center justify-center gap-4 py-4 sm:flex-row sm:gap-8 md:gap-16 navigation">

            @php
                $subMenuCollection = [];

                $showCompare = core()->getConfigData('general.content.shop.compare_option') == "1" ? true : false;

                $showWishlist = core()->getConfigData('general.content.shop.wishlist_option') == "1" ? true : false;

                try {
                    $subMenuCollection['profile'] = $menuItem['children']['profile'];
                    $subMenuCollection['orders'] = $menuItem['children']['orders'];
                    //MOD: NASCONDO VOCI MENU SIDEBAR ACCOUNT
                    //$subMenuCollection['downloadables'] = $menuItem['children']['downloadables'];

                    if ($showWishlist) {
                        $subMenuCollection['wishlist'] = $menuItem['children']['wishlist'];
                    }

                    if ($showCompare) {
                        //$subMenuCollection['compare'] = $menuItem['children']['compare'];
                    }

                    //$subMenuCollection['reviews'] = $menuItem['children']['reviews'];
                    $subMenuCollection['release'] = $menuItem['children']['release'];
                    $subMenuCollection['address'] = $menuItem['children']['address'];
                    

                    unset(
                        $menuItem['children']['profile'],
                        $menuItem['children']['orders'],
                        $menuItem['children']['downloadables'],
                        $menuItem['children']['wishlist'],
                        $menuItem['children']['compare'],
                        $menuItem['children']['reviews'],
                        $menuItem['children']['release'],
                        $menuItem['children']['address']
                        
                    );

                    foreach ($menuItem['children'] as $key => $remainingChildren) {
                        $subMenuCollection[$key] = $remainingChildren;
                    }
                } catch (\Exception $exception) {
                    $subMenuCollection = $menuItem['children'];
                }
            @endphp

          <li><a class="block font-semibold uppercase text-white" href="#">Bacheca</a></li>
          <li><a class="block font-semibold uppercase text-white" href="#">Collezione</a></li>
          <li><a class="block font-semibold uppercase text-white" href="#">Wantlist</a></li>
          <li><a class="block font-semibold uppercase text-white" href="#">Contributi</a></li>
        </ul>
        @endforeach --}}

      {{-- menu account --}}
      @include('shop::customers.account.partials.sidemenu')

    </div>

  </div>

  <div class="account-content row no-margin velocity-divide-page">

    {{-- <div class="sidebar left">
          @include('shop::customers.account.partials.sidemenu')
        </div> --}}

    <div class="account-layout container mx-auto">
      @if (request()->route()->getName() !== 'customer.profile.index')
        @if (Breadcrumbs::exists())
          {{ Breadcrumbs::render() }}
        @endif
      @endif

      @yield('page-detail-wrapper')
    </div>
  </div>

@endsection

@push('scripts')
  <script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>
@endpush
