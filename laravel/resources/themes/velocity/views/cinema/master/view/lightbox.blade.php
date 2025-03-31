@php
  $imageData = [];
  
  foreach ($images as $key => $image) {
      // die();
      $imageData[$key]['type'] = '';
      $imageData[$key]['large_image_url'] = url('cache/large/' . $image->path);
      $imageData[$key]['small_image_url'] = url('cache/small/' . $image->path);
      $imageData[$key]['medium_image_url'] = url('cache/medium/' . $image->path);
      $imageData[$key]['original_image_url'] = Storage::url($image->path);
  }
  //dd($imageData);
@endphp

{!! view_render_event('bagisto.shop.cinema.master.view.gallery.before', ['master' => $master]) !!}

@if (config('app.env') === 'localhost')

  <div class="carousel">
    @php
      foreach ($images as $key => $image) {
          echo '<div class="carousel-cell">';
          echo ' <img src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" />';
          // echo '<img class="copertina" width="400" height="600" src="' . ($imageData[$key]['original_image_url'] = Storage::url($image->path) . '">');
          echo '</div>';
      }
    @endphp
  </div>

  {{-- <img src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" /> --}}
@else
  @if ($imageData)
    <div class="product-image-group">

      <img class="copertina" width="400" height="600" src="{{ $imageData[0]['large_image_url'] }}"
        type="{{ $imageData[0]['type'] }}">

      <vue-it-bigger :master_id="{{ $master->master_id }}" tipo="master">
      </vue-it-bigger> <!-- PWS#13-lightbox -->

    </div>
  @endif

@endif

{!! view_render_event('bagisto.shop.products.view.gallery.after', ['master' => $master]) !!}

{{-- @push('css')
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
  @endpush --}}

{{-- @push('scripts')
  <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
  <script>
    var flky = new Flickity('.carousel', {
      // options, defaults listed

      accessibility: true,
      // enable keyboard navigation, pressing left & right keys

      adaptiveHeight: false,
      // set carousel height to the selected slide

      autoPlay: false,
      // advances to the next cell
      // if true, default is 3 seconds
      // or set time between advances in milliseconds
      // i.e. `autoPlay: 1000` will advance every 1 second

      cellAlign: 'center',
      // alignment of cells, 'center', 'left', or 'right'
      // or a decimal 0-1, 0 is beginning (left) of container, 1 is end (right)

      cellSelector: undefined,
      // specify selector for cell elements

      contain: false,
      // will contain cells to container
      // so no excess scroll at beginning or end
      // has no effect if wrapAround is enabled

      draggable: '>1',
      // enables dragging & flicking
      // if at least 2 cells

      dragThreshold: 3,
      // number of pixels a user must scroll horizontally to start dragging
      // increase to allow more room for vertical scroll for touch devices

      freeScroll: false,
      // enables content to be freely scrolled and flicked
      // without aligning cells

      friction: 0.2,
      // smaller number = easier to flick farther

      groupCells: false,
      // group cells together in slides

      initialIndex: 0,
      // zero-based index of the initial selected cell

      lazyLoad: true,
      // enable lazy-loading images
      // set img data-flickity-lazyload="src.jpg"
      // set to number to load images adjacent cells

      percentPosition: true,
      // sets positioning in percent values, rather than pixels
      // Enable if items have percent widths
      // Disable if items have pixel widths, like images

      prevNextButtons: true,
      // creates and enables buttons to click to previous & next cells

      pageDots: true,
      // create and enable page dots

      resize: true,
      // listens to window resize events to adjust size & positions

      rightToLeft: false,
      // enables right-to-left layout

      setGallerySize: true,
      // sets the height of gallery
      // disable if gallery already has height set with CSS

      watchCSS: false,
      // watches the content of :after of the element
      // activates if #element:after { content: 'flickity' }

      wrapAround: false
      // at end of cells, wraps-around to first for infinite scrolling

    });
  </script>
@endpush --}}

{{-- @push('css')
  <!-- Add the slick-theme.css if you want default styling -->
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css" />
  <!-- Add the slick-theme.css if you want default styling -->
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css" />
@endpush --}}

{{-- @push('scripts')
  <script type="text/javascript" src="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js"></script>

  <script>
    $(".carousel").slick({
      dots: true,
      speed: 500,
      arrows: true
    });
  </script>
@endpush --}}
