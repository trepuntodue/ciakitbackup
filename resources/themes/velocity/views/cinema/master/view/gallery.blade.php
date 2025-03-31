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
  // dd($imageData);
@endphp

{!! view_render_event('bagisto.shop.cinema.master.view.gallery.before', ['master' => $master]) !!}
@if ($imageData)
  <div class="product-image-group">

    @php
      //dd($imageData[0]['large_image_url']);
    @endphp

    <div class="row col-12">
      @if (config('app.env') === 'localhost')
        <div class="mx-auto">

          <img width="400" height="600" src="{{ $imageData[0]['large_image_url'] }}" type="{{ $imageData[0]['type'] }}"
            alt="">
          <product-gallery></product-gallery>

        </div>
      @else
        <div class="mx-auto">
          <img width="400" height="600" src="{{ $imageData[0]['large_image_url'] }}"
            type="{{ $imageData[0]['type'] }}" alt="">

          <product-gallery></product-gallery>
        </div>
        {{-- <magnify-image width="400" height="600" src="{{ $imageData[0]['large_image_url'] }}"
          type="{{ $imageData[0]['type'] }}"></magnify-image> --}}
      @endif
    </div>

    {{-- <div class="row col-12">
      <product-gallery></product-gallery>
    </div> --}}

  </div>
@endif
{!! view_render_event('bagisto.shop.products.view.gallery.after', ['master' => $master]) !!}

<script type="text/x-template" id="product-gallery-template">
    <ul class="thumb-list col-12 row ltr" type="none">
        <li class="arrow left" @click="scroll('prev')" v-if="thumbs.length > 4">
            <i class="rango-arrow-left fs24"></i>
        </li>

        <carousel-component
            slides-per-page="4"
            :id="galleryCarouselId"
            pagination-enabled="hide"
            navigation-enabled="hide"
            add-class="product-gallery"
            :slides-count="thumbs.length">

            <slide :slot="`slide-${index}`" v-for="(thumb, index) in thumbs">
                <li
                    @mouseover="changeImage({
                        largeImageUrl: thumb.large_image_url,
                        originalImageUrl: thumb.original_image_url,
                        currentType: thumb.type
                    })"
                    :class="`thumb-frame ${index + 1 == 4 ? '' : 'mr5'} ${thumb.large_image_url == currentLargeImageUrl ? 'active' : ''}`"
                    >

                    <video v-if="thumb.type == 'video'" width="110" height="110" controls>
                        <source :src="thumb.small_image_url" type="video/mp4">
                        {{ __('admin::app.catalog.products.not-support-video') }}
                    </video>

                    <div v-else
                        class="bg-image"
                        :style="`background-image: url(${thumb.small_image_url})`">
                    </div>
                </li>
            </slide>
        </carousel-component>

        <li class="arrow right" @click="scroll('next')" v-if="thumbs.length > 4">
            <i class="rango-arrow-right fs24"></i>
        </li>
    </ul>
</script>

@push('scripts')
  <script type="text/javascript">
    (() => {
      var galleryImages = @json($imageData);

      Vue.component('product-gallery', {
        template: '#product-gallery-template',
        data: function() {
          return {
            images: galleryImages,
            thumbs: [],
            galleryCarouselId: 'product-gallery-carousel',
            currentLargeImageUrl: '',
            currentOriginalImageUrl: '',
            currentType: '',
            counter: {
              up: 0,
              down: 0,
            }
          }
        },

        watch: {
          'images': function(newVal, oldVal) {
            if (this.images[0]) {
              this.changeImage({
                largeImageUrl: this.images[0]['large_image_url'],
                originalImageUrl: this.images[0]['original_image_url'],
                currentType: this.images[0]['type']
              })
            }

            this.prepareThumbs()
          }
        },

        created: function() {
          this.changeImage({
            largeImageUrl: this.images[0]['large_image_url'],
            originalImageUrl: this.images[0]['original_image_url'],
            currentType: this.images[0]['type']
          });

          eventBus.$on('configurable-variant-update-images-event', this.updateImages);

          this.prepareThumbs();
        },

        methods: {
          updateImages: function(galleryImages) {
            this.images = galleryImages;
          },

          prepareThumbs: function() {
            this.thumbs = [];

            this.images.forEach(image => {
              this.thumbs.push(image);
            });
          },

          changeImage: function({
            largeImageUrl,
            originalImageUrl,
            currentType
          }) {
            this.currentLargeImageUrl = largeImageUrl;

            this.currentOriginalImageUrl = originalImageUrl;

            this.currentType = currentType;

            this.$root.$emit('changeMagnifiedImage', {
              smallImageUrl: this.currentOriginalImageUrl,
              largeImageUrl: this.currentLargeImageUrl,
              currentType: this.currentType
            });

            let productImage = $('.vc-small-product-image');
            if (productImage && productImage[0]) {
              productImage = productImage[0];

              productImage.src = this.currentOriginalImageUrl;
            }
          },

          scroll: function(navigateTo) {
            let navigation = $(
              `#${this.galleryCarouselId} .VueCarousel-navigation .VueCarousel-navigation-${navigateTo}`);

            if (navigation && (navigation = navigation[0])) {
              navigation.click();
            }
          },
        }
      });
    })
    ()
  </script>
@endpush
