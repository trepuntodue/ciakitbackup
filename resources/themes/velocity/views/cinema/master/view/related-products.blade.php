<?php
// related
// dd($relatedmaster);
// PWS#13-related
$relatedmasters = $relatedmaster; ?>

@if (count($relatedmasters))

  <div class="carousel-masters vc-full-screen">
    <carousel-component slides-per-page="4" navigation-enabled="show" pagination-enabled="hide"
      id="related-masters-carousel" :slides-count="{{ sizeof($relatedmasters) }}">

      @foreach ($relatedmasters as $index => $relatedmaster)
        @php
          echo $index;
          //dd($relatedmaster);
        @endphp
        <slide slot="slide-{{ $index }}">
          {{-- @include ('shop::releases.list.card', [
              'master' => $relatedmaster,
              'addToCartBtnClass' => 'small-padding',
          ]) --}}

          <div class="card">

            @if (config('app.env') === 'localhost')
              <img class="aspect-square h-72 w-72" src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" />
            @else
              @if ($relatedmaster->path)
                <img class="aspect-square h-72 w-72" src=" {{ url('cache/medium/' . $relatedmaster->path) }}">
              @else
                <img class="aspect-square h-72 w-72" src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" />
              @endif
            @endif

            <div class="title title--original text-xl">
              <a href="/masters/{{ $relatedmaster->master_id }}/{{ $relatedmaster->url_key }}"
                rel="{{ $relatedmaster->master_maintitle }}">
                {{ $relatedmaster->master_maintitle }}
              </a>
            </div>

            <ul class="info">
              <li class="genres" data-value="{{ $relatedmaster->genres_name }}">{{ $relatedmaster->genres_name }}</li>
              <li class="year notranslate" data-value="{{ $relatedmaster->master_year }}">{{ $relatedmaster->master_year }}</li>
              <li class="master_director notranslate" data-value="{{ $relatedmaster->director_name }}">
                  @php
                    $directors = explode(", ",$relatedmaster->director_name);
                    foreach($directors as $key => $director){
                      $dir = explode("_*_",$director);
                      $director_id = $dir[0];
                      $director_name = isset($dir[1]) ? $dir[1] : ""; // PWS#chiusura
                      $separator = "";
                      if ($key !== array_key_last($directors)) {
                          $separator = ", ";
                      }
                      echo "<a href='/masters?regista={$director_id}'>{$director_name}</a>{$separator}";
                    }
                  @endphp
                </li> <!-- PWS#chiusura -->
              <li class="languages" data-value="{{ $relatedmaster->country }}">{{ $relatedmaster->country }}</li>
            </ul>
          </div>
        </slide>
      @endforeach
    </carousel-component>
  </div>

  <div class="carousel-masters vc-small-screen">
    <carousel-component :slides-count="{{ sizeof($relatedmasters) }}" slides-per-page="2" id="related-masters-carousel"
      navigation-enabled="hide" pagination-enabled="hide">

      @foreach ($relatedmasters as $index => $relatedmaster)
        <slide slot="slide-{{ $index }}">
          {{-- @include ('shop::releases.list.card', [
              'master' => $relatedmaster,
              'addToCartBtnClass' => 'small-padding',
          ]) --}}
        </slide>
      @endforeach
    </carousel-component>
  </div>
@endif
