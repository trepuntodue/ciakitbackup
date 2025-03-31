<!-- PWS#master-list -->
{{-- master grid/list --}}
<div class="full-content-wrapper bglight mx-2 py-4 sm:px-4">
  <div class="container">
    <h2 class="text-themeColor-500 relative z-10 inline-block text-2xl font-bold">Esplora tutto</h2>

    <div class="my-8 flex flex-col-reverse gap-8 md:flex-row">
      <!-- filter col -->
      <div class="flex flex-col gap-6 filter md:w-1/3 lg:w-1/4">
        <form action="
        " id="siderbar_filter" method="GET">
          <!-- PWS#230101 -->
          @csrf
          <!-- filter -->
          <div class="mb-8 rounded-md p-4 shadow">
            <h3 class="mb-4 font-semibold">Cerca per titolo o etichetta</h3>
            <div class="relative">
              <input class="border-themeColor-500 w-full rounded-xl border-2 py-2 px-2 text-sm" type="text"
                name="term" id="term" value="{{ Request::get('term') }}"
                placeholder="Cerca per titolo o etichetta"> <!-- PWS#04-23 -->
            </div>
          </div> <!-- PWS#13-filtri -->
          <!-- filter PWS#chiusura -->
          <div class="mb-8 rounded-md p-4 shadow">
            <h3 class="mb-4 font-semibold">Cerca per #catalogo</h3>
            <div class="relative">
              <input class="border-themeColor-500 w-full rounded-xl border-2 py-2 px-2 text-sm" type="text"
                name="catalogo" id="catalogo" value="{{ Request::get('catalogo') }}"
                placeholder="Cerca per #catalogo">
            </div>
          </div>
          <!-- filter -->
          <input type="hidden" id="order_by" name="order_by" value="{{ Request::get('order_by'); }}">
          <!-- filter -->
          <div class="mb-8 rounded-md p-4 shadow">
            <h3 class="mb-4 font-semibold">Generi</h3>

            @foreach ($generi as $genere)

              {{-- Request::get("my_checkbox") ? 'checked' : null --}}
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="{{ $genere->id }}"
                  id="{{ $genere->generi_name }}" name="generi[]"
                  {{ in_array($genere->id, (array) Request::get('generi')) ? 'checked' : null }}>
                <label class="form-check-label ml-2" for="{{ $genere->generi_name }}">
                  {{ $genere->generi_name }}
                </label>
              </div>
            @endforeach

          </div>
          <!-- filter -->
          {{-- <div class="mb-8 rounded-md p-4 shadow">
            <h3 class="mb-4 font-semibold">Formato</h3>

            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="Dvd">
              <label class="form-check-label ml-2" for="Dvd">
                Dvd
              </label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="VHS">
              <label class="form-check-label ml-2" for="VHS">
                VHS
              </label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="Pellicola">
              <label class="form-check-label ml-2" for="Pellicola">
                Pellicola
              </label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="Locandina">
              <label class="form-check-label ml-2" for="Locandina">
                Locandina
              </label>
            </div>

          </div> --}}
          <!-- PWS#chiusura filter -->
          <div class="mb-8 rounded-md p-4 shadow">
            <h3 class="mb-4 font-semibold">Tipo</h3>
            <div class="relative">
              <select name="master_type" id="master_type"
                class="bg-themeColor-500 w-full appearance-none rounded-xl py-2 px-2 text-sm">
                <option value="">Nessun tipo selezionato</option>
                {{-- @foreach ($master_types as $master_type)
                  <option value="{{ $master_type->master_type }}" {{ Request::get('master_type') == $master_type->master_type ? 'selected' : null }}>
                    {{ ucfirst($master_type->master_type); }}</option>
                @endforeach --}}
                  <option value="movie" {{ Request::get('master_type') == 'movie' ? 'selected' : null }}>
                    Movie</option>
                  <option value="movie-episode" {{ Request::get('master_type') == 'movie-episode' ? 'selected' : null }}>
                    Movie Episode</option>
                  <option value="serie" {{ Request::get('master_type') == 'serie' ? 'selected' : null }}>
                    Serie TV</option>
                  <option value="poster">
                    Poster</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
              </div>
            </div>
          </div>
          <!-- PWS#chiusura -->
          <!-- filter -->
          <div class="mb-8 rounded-md p-4 shadow">
            <h3 class="mb-4 font-semibold">Attori</h3>
            <div class="relative">
              <select name="attore" id="attore"
                class="bg-themeColor-500 w-full appearance-none rounded-xl py-2 px-2 text-sm">
                <option value="">Nessun attore selezionato</option>
                @foreach ($attori as $attore)
                  <option value="{{ $attore->id }}"
                    {{ Request::get('attore') == $attore->id ? 'selected' : null }}>{{ strlen($attore->attori_alias) > 0 ? $attore->attori_nome . ' ' . $attore->attori_cognome . ' (' . $attore->attori_alias . ')' : $attore->attori_nome . ' ' . $attore->attori_cognome }}
                  </option>
                @endforeach
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
              </div>
            </div>
          </div>
          <div class="mb-8 rounded-md p-4 shadow">
            <h3 class="mb-4 font-semibold">Registi</h3>
            <div class="relative">
              <select name="regista" id="regista"
                class="bg-themeColor-500 w-full appearance-none rounded-xl py-2 px-2 text-sm">
                <option value="">Nessun regista selezionato</option>
                @foreach ($registi as $regista)
                  <option value="{{ $regista->id }}"
                    {{ Request::get('regista') == $regista->id ? 'selected' : null }}>{{ strlen($regista->registi_alias) > 0 ? $regista->registi_nome . ' ' . $regista->registi_cognome . ' (' . $regista->registi_alias . ')' : $regista->registi_nome . ' ' . $regista->registi_cognome }}
                  </option>
                @endforeach
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
              </div>
            </div>
          </div>
          <!-- filter -->
          <div class="mb-8 rounded-md p-4 shadow">
            <h3 class="mb-4 font-semibold">Anno</h3>
            <div class="relative">
              <select name="year" id="year"
                class="bg-themeColor-500 w-full appearance-none rounded-xl py-2 px-2 text-sm">
                <option value="">Nessun anno selezionato</option>
                @for ($i = date('Y'); $i >= 1895; $i--)
                  <option value="{{ $i }}" {{ Request::get('year') == $i ? 'selected' : null }}>
                    {{ $i }}</option>
                @endfor
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
              </div>
            </div>
          </div>
          <!-- filter -->
          <div class="mb-8 rounded-md p-4 shadow">
            <h3 class="mb-4 font-semibold">Paese</h3>
            <div class="relative">
              <select name="country" id="country"
                class="bg-themeColor-500 w-full appearance-none rounded-xl py-2 px-2 text-sm">
                <option value="">Nessuna paese selezionato</option> <!-- PWS#video-poster -->
                @foreach ($countries as $country)
                  <option value="{{ $country->code }}"
                    {{ Request::get('country') == $country->code ? 'selected' : null }}>{{ $country->name }}
                  </option>
                @endforeach
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
              </div>
            </div>

          </div>
          <!-- filter -->
          <div class="mb-8 rounded-md p-4 shadow">
            <h3 class="mb-4 font-semibold">Lingue</h3>
            <div class="relative">
              <select name="language" id="language"
                class="bg-themeColor-500 w-full appearance-none rounded-xl py-2 px-2 text-sm">
                <option value="">Nessuna lingua selezionata</option> <!-- PWS#video-poster -->
                @foreach ($lingue as $lingua)
                  <option value="{{ $lingua->code }}"
                    {{ Request::get('language') == $lingua->code ? 'selected' : null }}>{{ $lingua->name }}
                  </option>
                @endforeach
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
              </div>
            </div>
            <input type="hidden" id="per_page" name="per_page"
              value="{{ Request::get('per_page') ? Request::get('per_page') : 15 }}">
          </div>
          <a href="/masters" class="no-underline"><div class="mb-8 p-2 rounded-md shadow text-center bg-themeColor-500">
            <span class="font-semibold text-white">{{ __('shop::app.products.remove-filter-link-title') }}</span><!-- PWS#finale -->
          </div></a><!-- PWS#18-2 -->
          <input type="submit" style="visibility: hidden;" /> <!-- PWS#chiusura -->
        </form>
      </div>

      <div class="list md:w-2/3 lg:w-3/4">

        <div
          class="sort-container mb-4 flex flex-col items-start gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-8 md:-mt-16 md:mb-8 md:px-4">

          <div class="results-counter w-32">
            {{ ($masters->currentPage() - 1) * $masters->perPage() + 1 }}-{{ ($masters->currentPage() - 1) * $masters->perPage() + $masters->perPage() }}
            di {{ $masters->total() }}
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
                  <svg class="w-4 bg-white fill-current before:h-4" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20">
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

        <div class="grid w-full grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
          @foreach ($masters as $item)
            <div class="card">

              @if (config('app.env') === 'localhost')
                <a href="/masters/{{ $item->master_id }}/{{ $item->url_key }}" rel="{{ $item->master_maintitle }}">
                  <img src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" />
                </a>
              @else
                @if ($item->path)
                  <a href="/masters/{{ $item->master_id }}/{{ $item->url_key }}"
                    rel="{{ $item->master_maintitle }}">
                    <img src=" {{ url('cache/medium/' . $item->path) }} " alt="{{ $item->master_maintitle }}">
                  </a>
                @else
                  <a href="/masters/{{ $item->master_id }}/{{ $item->url_key }}"
                    rel="{{ $item->master_maintitle }}">
                    <img src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" />
                  </a>
                @endif
              @endif

              <div class="title title--original">
                <a href="/masters/{{ $item->master_id }}/{{ $item->url_key }}" rel="{{ $item->master_maintitle }}">
                  {{ $item->master_maintitle }}
                </a>
              </div>

              <ul class="info">
                @if ($item->genres_name)
                  <li class="genres" data-value="{{ $item->genres_name }}">{{ $item->genres_name }}</li>
                @endif
                @if ($item->master_year)
                  <li class="year" data-value="{{ $item->master_year }}">{{ $item->master_year }}</li>
                @endif <!-- PWS#13-paese -->
                @if ($item->country)
                  <li li class="country" data-value="{{ $item->country }}">{{ $item->country }}</li>
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
          @endforeach
        </div>

      </div>
    </div>
  </div>
</div> <!-- PWS#chiusura -->
  {{ $masters->appends($filters)->links() }} <!-- PWS#finale -->

  {{--
  <div class="card">
      <img src=" {{ url('cache/medium/' . $item->path) }} " alt="{{ $item->master_othertitle }}">
      <div class="title title--original text-xl">
        <a href="/masters/{{$item->master_id}}/{{$item->url_key}}" rel="{{ $item->master_othertitle }}">
          {{ $item->master_maintitle }}
        </a>
      </div>

      <ul class="info">
        @if ($item->genres_name) <li class="genres" data-value="{{ $item->genres_name }}">{{ $item->genres_name }}</li>@endif
        @if ($item->master_year) <li class="year notranslate" data-value="{{ $item->master_year }}">{{ $item->master_year }}</li> @endif
        @if ($item->director_name) <li class="master_director notranslate">{{ $item->director_name }}</li> @endif
        @if ($item->lang_name) <li class="languages" data-value="{{ $item->lang_name }}">{{ $item->lang_name }}</li> @endif
      </ul>
  </div>
@endforeach
{{ $masters->links() }} --}}
