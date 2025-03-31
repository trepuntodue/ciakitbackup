<!-- PWS#18-2 -->
@php
  if (empty($images) && isset($master_images)) {
      $images = $master_images;
  }
@endphp

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
        <th scope="col" class="py-2 px-2 sm:px-6">Titolo, anno, paese, formato</th>
        @if ($releases->releasetype != config('constants.release.tipo.poster'))
          <!-- PWS#04-23 -->
          <th scope="col" class="py-2 px-2 sm:px-6">Condizioni video</th>
          <th scope="col" class="py-2 px-2 sm:px-6">Condizioni copertina</th>
        @else
          <th scope="col" class="py-2 px-2 sm:px-6">Condizione prodotto</th>
        @endif
        <th scope="col" class="py-2 px-2 sm:px-6">Prezzo</th>
        <th scope="col" class="py-2 px-2 sm:px-6">Nickname</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($products as $product)
        <tr class="border-b bg-white hover:bg-gray-50">
          <td class="hidden w-4 p-4">
            <div class="flex items-center">
              <input id="checkbox-table-search-1" type="checkbox"
                class="h-4 w-4 min-w-min border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500" />
              <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
            </div>
          </td>
          <th scope="row" class="min-w-[8rem] py-4 px-2 text-gray-900 sm:px-6">
            @if ($product->image_path)
              <img class="h-auto w-full max-w-[8rem]" src="{{ url('cache/large/' . $product->image_path) }}"
                alt="">
            @elseif(isset($images[0]))
              <img class="h-auto w-full max-w-[8rem]" src="{{ url('cache/large/' . $images[0]->path) }}" alt="">
            @endif <!-- PWS#related-release-2 -->
          </th>
          <td class="py-4 px-2 sm:px-6">
            @if ($product->url_key)
              <a href="/{{ $product->url_key }}">
                <div class="mb-2 text-lg font-semibold text-black">{{ $product->product_name }}</div>
              </a>
            @else
              <div class="mb-2 text-lg font-semibold text-black">{{ $product->product_name }}</div>
            @endif
          </td>
          @if ($releases->releasetype != config('constants.release.tipo.poster'))
            <td class="py-4 px-2 sm:px-6">-</td>
            <td class="py-4 px-2 sm:px-6">-</td>
          @else
            <td class="py-4 px-2 sm:px-6">-</td>
          @endif
          <td class="py-4 px-2 sm:px-6">@php echo number_format($product->price, 2, ',', '.'); @endphp€</td>
          <td class="py-4 px-2 sm:px-6">{{ $product->owner_fname }} {{ $product->owner_lname }}</td> <!-- PWS#prod -->
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="mx-auto px-8 py-1 text-center text-xs sm:px-16">The seller assumes all responsability for this listing. The
  seller is
  responsible for the
  sale
  of the items and
  for maneging any issues arising out of or in connection with the contract for sale between the seller and the
  buyer</div>
