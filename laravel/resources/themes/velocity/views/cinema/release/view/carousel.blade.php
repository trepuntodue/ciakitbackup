@php
  $imageData = [];
  
  if (empty($images) && isset($master_images)) {
      $images = $master_images;
  }
  
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

<div class="copertine-carousel">
  <carousel :loop="true" :pagination-enabled="true" :navigation-enabled="true"
    :per-page-custom="[
        [320, 1],
        [480, 1],
        [768, 1],
        [1280, 1]
    ]">

    @if (config('app.env') === 'localhost')
      @php
        echo '<img class="absolute inset-0 h-full w-full" src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" />';
      @endphp
    @else
      @if(is_iterable($images) && count($images))
        @php
          foreach ($images as $key => $image) {
            echo '<slide>';
            echo '<img class="absolute inset-0 z-0 m-auto img-fluid" width="400" height="600" src="' . ($imageData[$key]['original_image_url'] = Storage::url($image->path) . '">');
            echo '</slide>';
          }
        @endphp
      @else
        @php
          echo '<slide>';
          echo '<img class="absolute inset-0 z-0 m-auto img-fluid" src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" />';
          echo '</slide>';
        @endphp
      @endif
    @endif

</div>
