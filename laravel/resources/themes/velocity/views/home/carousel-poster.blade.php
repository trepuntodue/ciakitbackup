@if (!empty($releases_poster))
  <!-- PWS#04-23 -->

  <div>
    {{-- <carousel-component slides-per-page="4" navigation-enabled="hide" pagination-enabled="hide"
    id="related-products-carousel" :slides-count="{{ sizeof($masters_series) }}"> --}}

    <carousel :pagination-enabled="true" :loop="true" :navigation-enabled="true"
      :per-page-custom="[
          [320, 1],
          [480, 2],
          [768, 3],
          [1280, 4]
      ]">

      @foreach (array_slice($releases_poster, 0, 28) as $item)
        {{-- <slide slot="slide-{{ $index }}"> --}}
        <slide>
          <div class="card">
            @if ($item->path)
              <a href="/releases/{{ $item->id }}/{{ $item->url_key }}" rel="{{ $item->original_title }}">
                <img src=" {{ url('cache/medium/' . $item->path) }} " alt="{{ $item->original_title }}">
              </a>
            @elseif($item->master_path)
              <a href="/releases/{{ $item->id }}/{{ $item->url_key }}" rel="{{ $item->original_title }}">
                <img src=" {{ url('cache/medium/' . $item->master_path) }} " alt="{{ $item->original_title }}">
              </a>
            @else
              <a href="/releases/{{ $item->id }}/{{ $item->url_key }}" rel="{{ $item->original_title }}">
                <img src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" />
              </a>
            @endif

            <div class="title title--original text-lg">
              <a href="/releases/{{ $item->id }}/{{ $item->url_key }}" rel="{{ $item->original_title }}">
                {{ $item->original_title }}
              </a>
            </div>

            <ul class="info">
              {{-- PWS#frontend --}}
              @if ($item->release_year)
                <li class="year" data-value="{{ $item->release_year }}">{{ $item->release_year }}</li>
              @endif
              @if ($item->country)
                <li li class="country" data-value="{{ $item->country }}">{{ $item->country }}</li>
              @endif
              @if ($item->release_distribution)
                <li class="distribution" data-value="{{ $item->release_distribution }}">
                  {{ $item->release_distribution }}</li>
              @endif
              @if ($item->poster_formato)
                <li class="formato" data-value="{{ $item->poster_formato }}">{{ $item->poster_formato }}</li>
              @endif
            </ul>

          </div>
        </slide>
      @endforeach

      {{-- </carousel-component> --}}

  </div>

@endif
