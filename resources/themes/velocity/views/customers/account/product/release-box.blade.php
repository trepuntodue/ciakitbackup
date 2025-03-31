<!-- PWS#prod -->
@if(!count($releases))
    <p class="create-product-empty">{{ __('shop::app.common.nessun_risultato') }}</p>
    <p class="create-product-description">{{ __('shop::app.customer.account.product.create.add_releases') }}</p>
    <div class="mt-4 flex gap-8">              
        <a class="btn group relative release buy" href="{{ route('shop.cinema.releases.list') }}">
            <span class='btn-action'>Sfoglia edizioni</span>
        </a>
        
        <a class="btn btn--black group relative release buy" href="{{ route('customer.release.create') }}"> <!-- PWS#prod -->
            <span class='btn-action'>Proponi nuova edizione</span>
        </a>
    </div>
@else
<p class="create-product-description">{{ __('shop::app.customer.account.product.create.create_description') }}</p>
<div class="list grid grid-cols-1 gap-3 sm:grid-cols-2 md:w-2/3 md:grid-cols-3 lg:w-3/4">
    @foreach ($releases as $item)
        <div class="card">
            <a href="/customer/account/products/create/{{ $item->id }}" rel="{{ $item->original_title }}">
                @if($item->path && config('app.env') !== 'localhost')
                    <img src=" {{ url('cache/medium/' . $item->path) }} " alt="{{ $item->original_title }}">
                @else
                    <img src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" />
                @endif
            </a>
            <div class="title title--original">
                <a href="/customer/account/products/create/{{ $item->id }}" rel="{{ $item->original_title }}">
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
                @if ($item->country)
                <li li class="country" data-value="{{ core()->country_name($item->country) }}">{{ core()->country_name($item->country) }}</li>
                @endif
                @if ($item->releasetype_name)
                <li li class="releasetype" data-value="{{ $item->releasetype_name }}">{{ $item->releasetype_name }}</li>
                @endif
            </ul>
        </div>
    @endforeach
    </div>
    {{  $releases->links();  }}
@endif