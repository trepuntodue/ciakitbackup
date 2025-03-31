@if (!empty($masters_series))
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

      @foreach (array_slice($masters_series, 0, 28) as $item)
        {{-- <slide slot="slide-{{ $index }}"> --}}
        <slide>
          <div class="card">
            @if ($item->path)
              <a href="/masters/{{ $item->master_id }}/{{ $item->url_key }}" rel="{{ $item->master_maintitle }}">
                <img src=" {{ url('cache/medium/' . $item->path) }} " alt="{{ $item->master_maintitle }}">
              </a>
            @else
              <a href="/masters/{{ $item->master_id }}/{{ $item->url_key }}" rel="{{ $item->master_maintitle }}">
                <img src="/app/public/img/ciakit-placeholder.jpg" alt="placeholder" />
              </a>
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
              <li class="master_director">
                @php
                  $directors = explode(", ",$item->director_name);
                  foreach($directors as $key => $director){
                    $dir = explode("_*_",$director);
                    $director_id = $dir[0];
                    $director_name = $dir[1];
                    $separator = "";
                    if ($key !== array_key_last($directors)) {
                        $separator = ", ";
                    }
                    echo "<a href='/masters?regista={$director_id}'>{$director_name}</a>{$separator}";
                  }
                @endphp
                </li> <!-- PWS#chiusura -->
              @endif
            </ul>

          </div>
        </slide>
      @endforeach

      {{-- </carousel-component> --}}

  </div>

@endif
