<!-- PWS#release-list -->
<!-- PWS#02-23 -->
{{-- release grid/list --}}
<div class="full-content-wrapper bglight mx-2 py-4 sm:px-4">
  <div class="container">
    <h2 class="text-themeColor-500 relative z-10 inline-block text-2xl font-bold">Le versioni dei nostri utenti</h2> <!-- PWS#Chiusura -->
    <div class="my-8 flex flex-col-reverse gap-8 md:flex-row">
      <!-- filter col -->
      <div class="flex flex-col gap-6 filter md:w-1/3 lg:w-1/4">
        <form action="
        " id="siderbar_filter" method="GET">
          <!-- PWS#230101 -->
          @csrf
          <!-- filter -->
          <div class="mb-8 rounded-md p-4 shadow">
            <h3 class="mb-4 font-semibold">Formato</h3>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="video" id="video" name="tipo[]"
                {{ in_array('video', (array) Request::get('tipo')) ? 'checked' : null }}>
              <label class="form-check-label ml-2" for="video">
                Video
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="poster" id="poster" name="tipo[]"
                {{ in_array('poster', (array) Request::get('tipo')) ? 'checked' : null }}>
              <label class="form-check-label ml-2" for="poster">
                Poster
              </label>
            </div>
          </div>
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
            <h3 class="mb-4 font-semibold">Lingue</h3>
            <div class="relative">
              <select name="language" id="language"
                class="bg-themeColor-500 w-full appearance-none rounded-xl py-2 px-2 text-sm">
                <option value="">Nessuna lingua selezionata</option>
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

          </div>
          <input type="hidden" id="per_page" name="per_page"
            value="{{ Request::get('per_page') ? Request::get('per_page') : 15 }}">
          <a href="/releases" class="no-underline"><div class="mb-8 p-2 rounded-md shadow text-center bg-themeColor-500">
            <span class="font-semibold text-white">{{ __('shop::app.products.remove-filter-link-title') }}</span> <!-- PWS#finale -->
          </div></a><!-- PWS#18-2 -->
          <input type="submit" style="visibility: hidden;" /> <!-- PWS#chiusura -->
        </form>
      </div>
      @include ('shop::cinema.releases.release-box')
      <!-- PWS#video-poster -->
    </div>
  </div>
</div> <!-- PWS#chiusura -->
  {{ $releases->links() }}

  @if (1 == 2)
    @foreach ($releases as $item)
      <div class="card">
        <img src=" {{ url('cache/medium/' . $item->path) }} " alt="{{ $item->original_title }}">
        {{-- <img src="/storage/img/ciakit-placeholder.jpg" alt="placeholder"> --}}
        <div class="title title--original text-xl">
          <a href="/releases/{{ $item->id }}/{{ $item->url_key }}" rel="{{ $item->original_title }}">
            {{ $item->original_title }}
          </a>
        </div>

        <ul class="info">
          @if ($item->genres_name)
            <li class="genres" data-value="{{ $item->genres_name }}">{{ $item->genres_name }}</li>
          @endif
          @if ($item->release_year)
            <li class="year" data-value="{{ $item->release_year }}">{{ $item->release_year }}</li>
          @endif
          @if ($item->director_name)
            <li class="master_director">{{ $item->director_name }}</li>
          @endif
          @if ($item->lang_name)
            <li class="languages" data-value="{{ $item->lang_name }}">{{ $item->lang_name }}</li>
          @endif
        </ul>

        {{-- <ul>
      <li class="title title--original" data-value="{{ $item->original_title }}">
        <a href="/releases/{{$item->id}}/{{$item->url_key}}" rel="{{ $item->original_title }}">
          <span>Titolo originale</span>
          {{ $item->original_title }}
        </a>
      </li>

      <li class="year" data-value="{{ $item->release_year }}">
        <span>Anno di edizione</span>
        {{ $item->release_year }}
      </li>

      <li class="distribution" data-value="{{ $item->master_studios }}">
        <span>Casa di distribuzione</span>
        {{ $item->master_studios }}
      </li>

      <li class="country" data-value="{{ core()->country_name($item->country) }}">
        <span>Paese di distribuzione</span>
        {{ core()->country_name($item->country) }}
      </li>

      <li class="type" data-value="{{ $item->releasetype }}">
        <span>Tipo</span>
        {{ $item->releasetype }}
      </li>

      <li class="language" data-value="{{ $item->lang_name }}">
        <span>Lingua</span>
        {{ $item->lang_name }}
      </li>
      <li class="created" data-value="{{ $item->created_at }}">
        <span>Creato il</span>
        {{ $item->created_at }}
      </li>
      <li class="status" data-value="{{ $item->master_status }}">
        <span>Stato</span>
        {{ $item->release_status }}
      </li>
    </ul> --}}

      </div>
    @endforeach
    {{ $releases->appends($filters)->links() }} <!-- PWS#finale -->
  @endif
