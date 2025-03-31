<!-- PWS#master-list -->
@push('scripts')
  {{-- <script type="text/javascript" src="{{ asset(mix('/js/pw.js', 'themes/velocity/assets')) }}"></script> <!-- PWS#230101-filter --> --}}

  <script type="text/javascript">
    $(document).ready(function(e) {
      $(document).on("change", ".form-check-input, #siderbar_filter select, #show_order_by, #show_per_page", function(e) { // PWS#video-poster
        refreshList();
      });
    });

    function refreshList() {
      $("#siderbar_filter").submit();
      // $j(container).fadeOut();
      // $j(container).load(url, function() {
      //     $j(container).fadeIn();
      // });
    }
  </script> <!-- PWS#13-vt18 -->
@endpush

@extends('shop::layouts.master')
@section('page_title')
  Lista Master
@endsection

@section('head')
  <meta name="title" content="" /><!-- TODO -->

  <meta name="description" content="" /><!-- TODO -->

  <meta name="keywords" content="" /><!-- TODO -->
@endsection

{{-- @section('content-wrapper')
  <div class="hero-movie">
    <div class="info">
      <div class="container mx-auto py-16 text-white">
        <h1 class="mb-6 text-7xl">Lista master</h1><!-- TODO -->

        <div class="searchbar max-w-xl">
          @include('shop::cinema.master.search-bar')
        </div>

      </div>
    </div>
    <img src="{{ asset('/themes/velocity/assets/images/banner-home.webp') }}" alt="ciakit">
  </div>
@endsection --}}

@section('full-content-wrapper')
  <!-- grid prodotti -->

  @include('velocity::cinema.masters.grid')
@endsection
