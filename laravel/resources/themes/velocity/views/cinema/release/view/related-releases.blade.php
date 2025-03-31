<!-- PWS#video-poster -->

@if (count($related_releases))

  <div class="list grid grid-cols-1 gap-3 sm:grid-cols-2 md:w-2/3 md:grid-cols-3 lg:w-3/4">
    @foreach ($related_releases as $item)
        <div class="card">

        @if (config('app.env') === 'localhost')
            <a href="/releases/{{ $item->id }}/{{ $item->url_key }}" rel="{{ $item->original_title }}">
            <img src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" />
            </a>
        @else
            @if ($item->path)
            <a href="/releases/{{ $item->id }}/{{ $item->url_key }}" rel="{{ $item->original_title }}">
                <img src=" {{ url('cache/medium/' . $item->path) }}" alt="{{ $item->original_title }}">
            </a>
            @elseif($item->master_path) 
            <!-- PWS#18-2 -->
            <a href="/releases/{{ $item->id }}/{{ $item->url_key }}" rel="{{ $item->original_title }}">
              <img src=" {{ url('cache/medium/' . $item->master_path) }}" alt="{{ $item->original_title }}">
            </a>
            @endif
        @endif

        <div class="title title--original">
            <a href="/releases/{{ $item->id }}/{{ $item->url_key }}" rel="{{ $item->original_title }}">
            {{ $item->original_title }}
            </a>
        </div>

        <ul class="info">
          @if($releases->releasetype == config('constants.release.tipo.poster'))
            @if ($item->release_year)
            <li class="year" data-value="{{ $item->release_year }}">{{ $item->release_year }}</li>
            @endif
            @if ($item->country)
            <li li class="country" data-value="{{ core()->country_name($item->country) }}">{{ core()->country_name($item->country) }}</li>
            @endif
            @if ($item->release_distribution)
            <li li class="release_distribution" data-value="{{ $item->release_distribution }}">{{ $item->release_distribution }}</li>
            @endif
            @if ($item->poster_formato)
            <li class="poster_formato" data-value="{{ $item->poster_formato }}">{{ $item->poster_formato }}</li>
            @endif
          @else
            @if ($item->genres_name)
            <li class="genres" data-value="{{ $item->genres_name }}">{{ $item->genres_name }}</li>
            @endif
            @if ($item->release_year)
            <li li class="year" data-value="{{ $item->release_year }}">{{ $item->release_year }}</li>
            @endif
            @if ($item->director_name)
            <li li class="director" data-value="{{ $item->director_name }}">{{ $item->director_name }}</li>
            @endif
            @if ($item->country)
            <li class="country" data-value="{{ $item->country }}">{{ $item->country }}</li>
            @endif
          @endif
        </ul>

        </div>
    @endforeach
  </div>
@endif
