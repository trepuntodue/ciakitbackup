@foreach ($menu->items as $menuItem)
  <ul type="none"
    class="navigation flex flex-wrap items-center justify-center gap-4 py-4 sm:flex-row sm:gap-8 md:gap-16">
    {{-- rearrange menu items --}}
    @php
      $subMenuCollection = [];
      
      $showCompare = core()->getConfigData('general.content.shop.compare_option') == '1' ? true : false;
      
      $showWishlist = core()->getConfigData('general.content.shop.wishlist_option') == '1' ? true : false;
      
      try {
          $subMenuCollection['profile'] = $menuItem['children']['profile'];
          $subMenuCollection['release'] = $menuItem['children']['release'];
      
          //$subMenuCollection['downloadables'] = $menuItem['children']['downloadables'];
      
          if ($showWishlist) {
              $subMenuCollection['wishlist'] = $menuItem['children']['wishlist'];
          }
      
          // if ($showCompare) {
          //     $subMenuCollection['compare'] = $menuItem['children']['compare'];
          // }
      
          //$subMenuCollection['reviews'] = $menuItem['children']['reviews'];
      
          $subMenuCollection['address'] = $menuItem['children']['address'];
          $subMenuCollection['orders'] = $menuItem['children']['orders'];
      
          unset($menuItem['children']['profile'], $menuItem['children']['orders'], $menuItem['children']['downloadables'], $menuItem['children']['wishlist'], $menuItem['children']['compare'], $menuItem['children']['reviews'], $menuItem['children']['release'], $menuItem['children']['address']);
      
          foreach ($menuItem['children'] as $key => $remainingChildren) {
              $subMenuCollection[$key] = $remainingChildren;
          }
      } catch (\Exception $exception) {
          $subMenuCollection = $menuItem['children'];
      }
    @endphp

    @foreach ($subMenuCollection as $index => $subMenuItem)
      <li class="{{ $menu->getActive($subMenuItem) }}" title="{{ trans($subMenuItem['name']) }}">
        <a class="block font-semibold uppercase text-white" href="{{ $subMenuItem['url'] }}">
          {{-- <i class="icon {{ $index }} text-down-3"></i> --}}
          <span>{{ trans($subMenuItem['name']) }}<span>
              {{-- <i class="rango-arrow-right float-right text-down-3"></i> --}}
        </a>
      </li>
    @endforeach
  </ul>
@endforeach

@push('css')
  <style type="text/css">
    .main-content-wrapper {
      margin-bottom: 0px;
      min-height: 100vh;
    }
  </style>
@endpush
