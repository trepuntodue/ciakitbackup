@php
  //dd($masters);
@endphp

@if (!empty($masters))

  <div>
    {{-- <carousel-component slides-per-page="3" navigation-enabled="hide" pagination-enabled="hide"
    id="related-products-carousel" :slides-count="{{ sizeof($masters) }}"> --}}

    {{-- <div data-v-453ad8cd="" class="VueCarousel-navigation"><button data-v-453ad8cd="" type="button" aria-label="Previous page" tabindex="0" class="VueCarousel-navigation-button VueCarousel-navigation-prev" style="padding: 8px; margin-right: -8px;"><span class="rango-arrow-left"></span></button> <button data-v-453ad8cd="" type="button" aria-label="Next page" tabindex="0" class="VueCarousel-navigation-button VueCarousel-navigation-next" style="padding: 8px; margin-left: -8px;"><span class="rango-arrow-right"></span></button></div> --}}

    @php
      /* TODO: formattare responsive*/
    @endphp

    {{-- carosello home --}}
    <carousel :loop="true" :pagination-enabled="true" :navigation-enabled="true"
      :per-page-custom="[
          [320, 1],
          [480, 2],
          [768, 3],
          [1280, 4]
      ]">

      @php
        // dd($masters);
      @endphp

      {{-- @foreach ($masters as $item) --}}

      @foreach (array_slice($masters, 0, 28) as $item)
        {{-- <slide slot="slide-{{ $index }}"> --}}
        <slide>

          <div class="card">

            @if (config('app.env') === 'localhost')
              <a href="/masters/{{ $item->master_id }}/{{ $item->url_key }}" rel="{{ $item->master_maintitle }}">
                <img src="/app/public/img/ciakit-placeholder.jpg" alt="placeholder" />
              </a>
            @else
              @if ($item->path)
                <a href="/masters/{{ $item->master_id }}/{{ $item->url_key }}" rel="{{ $item->master_maintitle }}">
                  <img src=" {{ url('cache/medium/' . $item->path) }} " alt="{{ $item->master_maintitle }}">
                </a>
              @else
                <a href="/masters/{{ $item->master_id }}/{{ $item->url_key }}" rel="{{ $item->master_maintitle }}">
                  <img src="/app/public/img/ciakit-placeholder.jpg" alt="placeholder" />
                </a>
              @endif
            @endif

            <div class="title title--original text-lg notranslate">
              <a href="/masters/{{ $item->master_id }}/{{ $item->url_key }}" rel="{{ $item->master_maintitle }}">
                {{ $item->master_maintitle }}
              </a>
            </div>

            <ul class="info">
              {{-- PWS#frontend --}}

              @if ($item->genres_name)
                <li class="genres" data-value="{{ $item->genres_name }}">{{ $item->genres_name }}</li>
              @endif

              @if ($item->master_year)
                <li class="year notranslate" data-value="{{ $item->master_year }}">{{ $item->master_year }}</li>
              @endif

              @if ($item->country)
                <li li class="country notranslate" data-value="{{ $item->country }}">{{ $item->country }}</li>
              @endif

            </ul>
            <ul class="info master_director-wrapper notranslate">
              @if ($item->director_name)
                <li class="master_director">{{ $item->director_name }}</li>
              @endif
            </ul>

          </div>

        </slide>
      @endforeach

      {{-- </carousel-component> --}}

  </div>

@endif
