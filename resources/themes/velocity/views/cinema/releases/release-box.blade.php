<!-- PWS#video-poster -->
{{-- release grid/list content --}}
<div class="list md:w-2/3 lg:w-3/4">

  @if (count($releases))

    <div
      class="sort-container mb-4 flex flex-col items-start gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-8 md:-mt-16 md:mb-8 md:px-4">

      <div class="results-counter w-32">
        {{ ($releases->currentPage() - 1) * $releases->perPage() + 1 }}-{{ ($releases->currentPage() - 1) * $releases->perPage() + $releases->perPage() }}
        di {{ $releases->total() }}
      </div>

      <div class="order flex items-center gap-2 sm:gap-8">

        <div class="flex items-center gap-2 sm:gap-4">

          <h3 class="font-semibold">Ordina</h3>

          <div class="relative">
            <select name="show_order_by" id="show_order_by"
              onchange="document.getElementById('order_by').value = this.value"
              class="border-themeColor-500 w-full appearance-none rounded-xl border-2 bg-white py-2 px-2 text-sm">
              <option value="most_recent" {{ Request::get('order_by') == 'most_recent' ? 'selected' : null }}>dal
                più recente al meno recente</option>
              <option value="alpha_asc" {{ Request::get('order_by') == 'alpha_asc' ? 'selected' : null }}>in ordine
                alfabetico crescente (a-z)</option>
              <option value="alpha_desc" {{ Request::get('order_by') == 'alpha_desc' ? 'selected' : null }}>in
                ordine alfabetico descrescente (z-a)</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <svg class="w-4 bg-white fill-current before:h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-4">
          <h3 class="font-semibold">Mostra</h3>
          <div class="relative">
            <select name="show_per_page" id="show_per_page"
              onchange="document.getElementById('per_page').value = this.value"
              class="border-themeColor-500 w-16 appearance-none rounded-xl border-2 bg-white py-2 px-2 text-sm">
              <option value="15" {{ Request::get('per_page') == 15 ? 'selected' : null }}>15</option>
              <option value="30" {{ Request::get('per_page') == 30 ? 'selected' : null }}>30</option>
              <option value="50" {{ Request::get('per_page') == 50 ? 'selected' : null }}>50</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <svg class="h-4 w-4 bg-white fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
              </svg>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
      @foreach ($releases as $item)
        <div class="card">
            <!-- PWS#18-2 -->
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

          <div class="title title--original">
            <a href="/releases/{{ $item->id }}/{{ $item->url_key }}" rel="{{ $item->original_title }}">
              {{ $item->original_title }}
            </a>
          </div>

          <ul class="info">
            @if ($item->genres_name)
              <li class="genres" data-value="{{ $item->genres_name }}">{{ $item->genres_name }}</li>
            @endif
            @if ($item->release_year)
              <li class="year notranslate" data-value="{{ $item->release_year }}">{{ $item->release_year }}</li>
            @endif <!-- PWS#13-paese -->
            @if ($item->country)
              <li li class="country notranslate" data-value="{{ core()->country_name($item->country) }}">
                {{ core()->country_name($item->country) }}</li>
            @endif
          </ul>
          {{-- <ul class="info master_director-wrapper">
                @if ($item->director_name)
                <li class="master_director notranslate">{{ $item->director_name }}</li>
                @endif
            </ul> --}}

        </div>
      @endforeach
    </div>
  @else
    <p>{{ __('shop::app.common.nessun_risultato') }}</p>
  @endif
</div>
