<!-- PWS#prod -->
@extends('shop::customers.account.index')

@section('page_title')
  {{ __('shop::app.customer.account.product.index.page-title') }}
@endsection

@section('page-detail-wrapper')
  @if ($products->isEmpty())
    <a href="{{ route('customer.product.create') }}" class="theme-btn light unset address-button">
      {{ __('shop::app.customer.account.product.index.add') }}
    </a>
  @endif

  <div class="account-head mt-3">
    <span class="account-heading">
      {{ __('shop::app.customer.account.product.index.title') }}
    </span>

    @if (!$products->isEmpty())
      <span class="account-action">
        <a href="{{ route('customer.product.create') }}" class="theme-btn light unset float-right">
          {{ __('shop::app.customer.account.product.index.add') }}
        </a>
      </span>
    @endif
  </div>

  {!! view_render_event('bagisto.shop.customers.account.product.list.before', ['products' => $products]) !!}

  <div class="account-table-content">
    @if ($products->isEmpty())
      <div>{{ __('shop::app.customer.account.product.index.empty') }}</div>
    @else
      {{-- <tabs><tab :selected="true"> --}}
      <div class="sale-section">
        <div class="section-content">
          <div class="table">
            <div class="table-responsive">
              <table>
                <thead>
                  <tr>
                    <th>Nome prodotto</th>
                    <th>Prezzo</th>
                  </tr>
                </thead>

                <tbody>
                  @foreach ($products as $item)
                    <tr>
                        <td data-value="{{ __('shop::app.customer.account.product.view.original_title') }}">
                            <a href="/{{ $item->sku }}">{{ $item->name }}</a>
                        </td>
                        <td data-value="">
                            {{ number_format( $item->price, 2, ',', '.' ) }} €
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>


  {!! view_render_event('bagisto.shop.customers.account.product.list.after', ['products' => $products]) !!}
@endsection

@if ($products->isEmpty())
  <style>
    a#add-address-button {
      position: absolute;
      margin-top: 92px;
    }

    .address-button {
      position: absolute;
      z-index: 1 !important;
      margin-top: 110px !important;
    }
  </style>
@endif