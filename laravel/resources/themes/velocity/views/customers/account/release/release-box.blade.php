<!-- PWS#video-poster -->

{{-- <div class="list grid grid-cols-1 gap-3 sm:grid-cols-2 md:w-2/3 md:grid-cols-3 lg:w-3/4">
    @if(!count($releases))
        {{ __('shop::app.common.nessun_risultato') }}
    @else
        @foreach ($releases as $item)
            <div class="card">
                @if($item->path)
                <a href="/releases/{{ $item->id }}/{{ $item->url_key }}" rel="{{ $item->original_title }}" target="_blank">
                    <img src=" {{ url('cache/medium/' . $item->path) }} " alt="{{ $item->original_title }}"> <!-- PWS#mmm -->
                </a>
                @endif
                <div class="title title--original">
                    <a href="/releases/{{ $item->id }}/{{ $item->url_key }}" rel="{{ $item->original_title }}" target="_blank">
                    {{ $item->original_title }}
                    </a>
                </div>
                <ul class="info">
                    @if ($item->genres_name)
                    <li class="genres" data-value="{{ $item->genres_name }}">{{ $item->genres_name }}</li>
                    @endif
                    @if ($item->release_year)
                    <li class="year" data-value="{{ $item->release_year }}">{{ $item->release_year }}</li>
                    @endif <!-- PWS#13-paese -->
                    @if ($item->country)
                    <li li class="country" data-value="{{ core()->country_name($item->country) }}">{{ core()->country_name($item->country) }}</li>
                    @endif
                </ul>
                @if( !$item->collection_id )
                    <a href="#" class="addToCollection" onclick="addToCollection(this, {{ $item->id }}); return false;">
                        {{ __('shop::app.common.add_to_collection'); }}
                    </a>
                @else
                    <p>{{ __('shop::app.common.already_in_collection'); }}</p>
                @endif
            </div>
        @endforeach
        {{  $releases->links();  }}
    @endif
</div> --}}

@if(!count($releases))
        {{ __('shop::app.common.nessun_risultato') }}
@else
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-100 text-xs text-black">
            <tr>
                <th scope="col" class="py-2 px-2 sm:px-6"></th>
                <th scope="col" class="py-2 px-2 sm:px-6">Titolo</th>
                <th scope="col" class="py-2 px-2 sm:px-6">Titolo originale</th>
                <th scope="col" class="py-2 px-2 sm:px-6">Casa di produzione</th>
                <th scope="col" class="py-2 px-2 sm:px-6">Casa di distribuzione</th>
                <th scope="col" class="py-2 px-2 sm:px-6">Supporto</th>
            </tr>
        </thead>
        <tbody>
        @foreach($releases as $item)
        <tr class="border-b bg-white hover:bg-gray-50">
            <td scope="row" class="min-w-[120px] py-4 px-2 text-gray-900 sm:px-6">
                @if($item->path)
                    <img class="aspect-square h-28 w-28" src="{{ url('cache/large/' . $item->path) }}" alt="">
                @elseif($item->master_path)
                    <img class="aspect-square h-28 w-28" src="{{ url('cache/large/' . $item->master_path) }}" alt="">
                @endif
            </td>
            <td class="py-4 px-2 sm:px-6">
                @if($item->id && $item->url_key)
                    <a href="{{ route('shop.cinema.release.list', ['id' => $item->id, 'slug' => $item->url_key]) }}">
                        <div class="mb-2 text-lg font-semibold text-black">{{ $item->original_title }}</div>
                    </a>
                @else
                    <div class="mb-2 text-lg font-semibold text-black">{{ $item->original_title }}</div>
                @endif

                @if( !$item->collection_id )
                    <a href="#" class="addToCollection btn" onclick="addToCollection(this, {{ $item->id }}); return false;">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 416.723 416.723" xml:space="preserve"
                                class="h-6 w-6 flex-shrink-0">
                                <path
                                d="M88.57 64.705H16.002C7.164 64.705 0 71.871 0 80.705v255.312c0 8.832 7.164 16 16.002 16H88.57c8.834 0 16-7.168 16-16V80.705c0-8.834-7.166-16-16-16zM72.084 261.92H32.486v-24.381h39.598v24.381zm0-43.24H32.486V107.097h39.598V218.68zM212.57 64.705h-72.564c-8.838 0-16 7.166-16 16v255.312c0 8.832 7.162 16 16 16h72.564c8.838 0 16-7.168 16-16V80.705c0-8.834-7.162-16-16-16zM196.086 261.92H156.49v-24.381h39.596v24.381zm0-43.24H156.49V107.097h39.596V218.68zM416.15 313.047 350.072 76.61c-2.285-8.185-10.775-12.964-18.959-10.679l-67.205 18.782c-8.188 2.288-12.969 10.778-10.678 18.959l66.082 236.438c2.282 8.185 10.772 12.963 18.954 10.679l67.207-18.779c8.187-2.291 12.966-10.781 10.677-18.963zm-97.125-89.998-28.882-103.338 36.675-10.25 28.879 103.338-36.672 10.25zm11.19 40.035-6.307-22.574 36.674-10.25 6.307 22.578-36.674 10.246z" />
                            </svg>
                            &nbsp;{{ __('shop::app.common.add_to_collection'); }} <!-- PWS#chisura -->
                        </div>
                    </a>
                @else
                    <p class="btn btn--black">{{ __('shop::app.common.already_in_collection'); }}</p>
                @endif
            </td>
            <td class="py-4 px-2 sm:px-6">{{ $item->master_maintitle }}</td>
            <td class="py-4 px-2 sm:px-6">{{ $item->casaproduzione_nome }}</td>
            <td class="py-4 px-2 sm:px-6">{{ $item->release_distribution }}</td>
            <td class="py-4 px-2 sm:px-6">{{ $item->release_tipo }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{  $releases->links();  }}
@endif
