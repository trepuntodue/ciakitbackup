<!--
<div class="relative mx-auto overflow-x-auto">
  <div class="flex hidden items-center justify-between bg-white pb-4 dark:bg-gray-900">
    <div>
      <button
        id="dropdownActionButton"
        data-dropdown-toggle="dropdownAction"
        class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700"
        type="button"
      >
        <span class="sr-only">Action button</span>
        Action
        <svg class="ml-2 h-3 w-3" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
      </button>
      <div id="dropdownAction" class="z-10 hidden w-44 divide-y divide-gray-100 rounded bg-white shadow">
        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownActionButton">
          <li>
            <a href="#" class="block py-2 px-4 hover:bg-gray-100">Reward</a>
          </li>
          <li>
            <a href="#" class="block py-2 px-4 hover:bg-gray-100">Promote</a>
          </li>
          <li>
            <a href="#" class="block py-2 px-4 hover:bg-gray-100">Activate account</a>
          </li>
        </ul>
        <div class="py-1">
          <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Delete User</a>
        </div>
      </div>
    </div>
    <label for="table-search" class="sr-only">Search</label>
    <div class="relative">
      <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
        </svg>
      </div>
      <input
        type="text"
        id="table-search-users"
        class="block w-80 rounded-lg border border-gray-300 bg-gray-50 p-2 pl-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
        placeholder="Search for users"
      />
    </div>
  </div>
</div> -->
<!-- PWS#mmm -->

<!-- PWS#13-release -->
<div class="w-full cursor-grab overflow-x-auto md:cursor-auto">

  <table class="w-full text-left text-sm">
    <thead class="bg-gray-100 text-xs text-black">
      <tr>
        <th scope="col" class="hidden p-4">
          <div class="flex items-center">
            <input id="checkbox-all-search" type="checkbox"
              class="h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600" />
            <label for="checkbox-all-search" class="sr-only">checkbox</label>
          </div>
        </th>
        <th scope="col" class="py-2 px-2 sm:px-6"></th>
        <th scope="col" class="py-2 px-2 sm:px-6">Titolo, anno, paese</th>
        <th scope="col" class="py-2 px-2 sm:px-6">Titolo originale</th>
        <th scope="col" class="py-2 px-2 sm:px-6">Casa di produzione</th>
        <th scope="col" class="py-2 px-2 sm:px-6">Casa di distribuzione</th>
        <th scope="col" class="py-2 px-2 sm:px-6">Supporto</th>
        <th scope="col" class="py-2 px-2 sm:px-6">N° prodotti</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($related_releases as $related_release)
        <tr class="border-b bg-white hover:bg-gray-50">
          <td class="hidden w-4 p-4">
            <div class="flex items-center">
              <input id="checkbox-table-search-1" type="checkbox"
                class="h-4 w-4 min-w-min border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500" />
              <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
            </div>
          </td>
          <th scope="row" class="min-w-[8rem] py-4 px-2 text-gray-900 sm:px-6">
            @if ($related_release->image_path)
              <img class="h-auto w-full max-w-[8rem]" src="{{ url('cache/large/' . $related_release->image_path) }}"
                type="{{ $related_release->image_type }}" alt="">
            @elseif(isset($images[0]))
              <img class="h-auto w-full max-w-[8rem]" src="{{ url('cache/large/' . $images[0]->path) }}"
                type="{{ $images[0]->type }}" alt="">
            @endif <!-- PWS#related-release-2 -->
          </th>
          <td class="py-4 px-2 sm:px-6">
            @if ($related_release->id && $related_release->url_key)
              <a class="hover:text-themeColor-500 hover:no-underline"
                href="{{ route('shop.cinema.release.list', ['id' => $related_release->id, 'slug' => $related_release->url_key]) }}">
                <div class="mb-2 text-lg font-semibold leading-none">{{ $related_release->original_title }}
                </div>
                <div>{{ $related_release->release_year }} | {{ $related_release->paese }}</div> <!-- PWS#18-2 -->
              </a>
            @else
              <div class="mb-2 text-lg font-semibold text-black">{{ $related_release->original_title }}</div>
              <div>{{ $related_release->release_year }} | {{ $related_release->paese }}</div> <!-- PWS#18-2 -->
            @endif

            @if (1 == 2)
              <!-- PWS#02-23 -->
              <!-- nascondo bottoni preferiti -->
              <div class="flex flex-row gap-2">
                <!-- SE È NEI PREFERITI ALLORA C'È LA CLASSE removeFromCollection, ALTRIMENTI addToCollection -->
                <a class="@if (in_array($related_release->id, $releases_favorite_array)) removeFromCollection @else addToCollection @endif favorite group relative inline-block"
                  href="#"
                  onclick="addToCollection(this, {{ $related_release->id }}, 'favorite', 'release'); return false;">
                  @if (!Auth::check())
                    <span
                      class="absolute -top-2 -right-3 z-10 hidden w-48 translate-x-full rounded-lg bg-gray-700 px-2 py-1 text-center text-sm text-white before:absolute before:top-1/2 before:right-[100%] before:-translate-y-1/2 before:border-8 before:border-y-transparent before:border-l-transparent before:border-r-gray-700 before:content-[''] group-hover:flex">accedi
                      per aggiungere il film ai preferiti</span>
                  @endif
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300.861 300.861"
                    class="hover:fill-themeColor-500 m-[2px] h-6 w-6 fill-gray-400"
                    style="enable-background: new 0 0 300.861 300.861" xml:space="preserve">
                    <path
                      d="M237.04 196.302h-7.547a.913.913 0 0 1-.453-.144v-7.856c0-5.607-4.193-10-9.547-10h-1.28c-5.515 0-10.173 4.579-10.173 10l-.003 8h-7.997c-5.421 0-10 4.658-10 10.173v1.28c0 5.354 4.393 9.547 10 9.547h7.856a.923.923 0 0 1 .144.453v7.547c0 5.421 4.658 10 10.173 10h1.28c5.354 0 9.547-4.393 9.547-10v-7.456c.078-.187.357-.466.544-.544h7.456c5.607 0 10-4.193 10-9.547v-1.28c0-5.514-4.579-10.173-10-10.173z" />
                    <path
                      d="M259.104 135.311c7.753-12.033 16.094-28.702 14.846-52.057-1.014-18.942-9.034-36.729-22.584-50.086-13.727-13.531-31.641-20.983-50.441-20.983-28.001 0-48.233 22.072-59.102 33.931-1.528 1.667-3.301 3.601-4.618 4.898-1.061-1.123-2.405-2.654-3.577-3.989-9.93-11.309-30.59-34.84-60.47-34.84-18.8 0-36.713 7.452-50.44 20.983C9.165 46.525 1.144 64.313.13 83.256c-1.01 18.899 3.82 35.341 15.662 53.309 9.393 14.255 33.99 42.081 59.814 67.668 31.556 31.266 52.266 47.118 61.554 47.118 2.176 0 5.263-.578 10.743-3.725 14.187 24.512 40.681 41.05 70.982 41.05 45.201 0 81.975-36.773 81.975-81.975.001-30.592-16.855-57.306-41.756-71.39zm-40.218 123.364c-28.659 0-51.975-23.315-51.975-51.975 0-28.659 23.315-51.975 51.975-51.975s51.975 23.316 51.975 51.975c0 28.66-23.316 51.975-51.975 51.975z" />
                  </svg>
                </a>

                <!-- SE È NELLA COLLEZIONE ALLORA C'È LA CLASSE removeFromCollection, ALTRIMENTI addToCollection -->
                <a class="@if (in_array($related_release->id, $releases_collection_array)) removeFromCollection @else addToCollection @endif collection group relative inline-block"
                  href="#"
                  onclick="addToCollection(this, {{ $related_release->id }}, 'collection', 'release'); return false;">
                  @if (!Auth::check())
                    <span
                      class="absolute -top-2 -right-3 z-10 hidden w-48 translate-x-full rounded-lg bg-gray-700 px-2 py-1 text-center text-sm text-white before:absolute before:top-1/2 before:right-[100%] before:-translate-y-1/2 before:border-8 before:border-y-transparent before:border-l-transparent before:border-r-gray-700 before:content-[''] group-hover:flex">accedi
                      per aggiungere il film alla collezione</span>
                  @endif
                  <svg width="28" height="28" xmlns="http://www.w3.org/2000/svg"
                    class="hover:fill-themeColor-500 fill-gray-400">
                    <path
                      d="M5.99 3a2 2 0 0 1 2 2v18a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1.99Zm7 0a2 2 0 0 1 2 2v18a2 2 0 0 1-2 2h-1.995a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1.995Zm9.08 3.543 3.86 15.483a2.002 2.002 0 0 1-1.457 2.425l-1.963.49a2 2 0 0 1-2.424-1.458L16.226 8a2 2 0 0 1 1.456-2.425l1.963-.489a2 2 0 0 1 2.425 1.457Z"
                      fill-rule="nonzero" />
                  </svg>
                </a>
              </div>
            @endif
          </td>
          <td class="py-4 px-2 sm:px-6">{{ $master->master_maintitle }}</td>
          <td class="py-4 px-2 sm:px-6">{{ $master->casaproduzione_nome }}</td>
          <td class="py-4 px-2 sm:px-6">{{ $related_release->release_distribution }}</td>
          <td class="py-4 px-2 sm:px-6">{{ $related_release->release_tipo }}</td>
          <td class="py-4 px-2 sm:px-6">{{ $related_release->products_counter }}</td> <!-- PWS#prod -->
        </tr>
      @endforeach
    </tbody>
  </table>

</div>
