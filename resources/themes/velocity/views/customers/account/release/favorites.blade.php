@extends('shop::customers.account.index')
<!-- PWS#finale -->
@section('page_title')
  {{ __('shop::app.customer.account.release.index.page-title') }}
@endsection

@section('page-detail-wrapper')
  @if ($releases->isEmpty())
    <a href="{{ route('customer.release.create') }}" class="theme-btn light unset address-button">
      {{ __('shop::app.customer.account.release.index.add') }}
    </a>
  @endif

  <div class="account-head mt-3">
    <span class="account-heading">
      {{ __('shop::app.customer.account.release.favorites.title') }}
    </span>

    @if (!$releases->isEmpty())
      <span class="account-action">
        <a href="{{ route('customer.release.create') }}" class="theme-btn light unset float-right">
          {{ __('shop::app.customer.account.release.index.add') }}
        </a>
      </span>
    @endif
  </div>

  {!! view_render_event('bagisto.shop.customers.account.release.list.before', ['releases' => $releases]) !!}

  <div class="">
    @if ($releases->isEmpty())
      <div>{{ __('shop::app.customer.account.release.index.empty') }}</div>
    @else
      {{-- <tabs><tab :selected="true"> --}}
      <div class="sale-section">
        <div class="section-content">
          <div class="table">
            <div class="table-responsive">
              <table id="releases">
                <thead>
                  <tr>
                    <th>Titolo originale</th>
                    {{--<th>Altri titoli</th>--}}
                    <th>Anno di edizione</th>
                    <th>Casa di distribuzione</th>
                    <th>Paese di distribuzione</th>
                    <th>Tipo</th>
                    <th>Lingua</th>
                    <th>Creato il</th>
                    <th>Stato</th>
                    <th></th>
                  </tr>
                </thead>

                @php
                  //dd($releases);
                @endphp

                <tbody>
                  @foreach ($releases as $item)
                    @if($item->release_status != 1)
                      <tr id="{{ $item->release_id }}" style="background-color:#eee;">
                    @else
                      <tr id="{{ $item->release_id }}">
                    @endif
                      <td>
                        @if($item->release_status == 1)
                          <a class="card-link" href="/releases/{{ $item->release_id }}/{{ $item->url_key }}">
                        @endif

                        @if($item->path)
                            <img class="aspect-square h-28 w-28" src="{{ url('cache/large/' . $item->path) }}" alt="">
                        @elseif($item->master_path)
                            <img class="aspect-square h-28 w-28" src="{{ url('cache/large/' . $item->master_path) }}" alt="">
                        @endif

                        @if($item->release_status == 1)
                          <div class="block px-0 text-lg font-bold text-themeColor-500">{{ $item->original_title }}</div>
                        @else
                          <div class="mb-2 text-lg font-semibold text-black">{{ $item->original_title }}</div>
                        @endif

                        @if($item->release_status == 1)
                          </a>
                        @endif
                      </td>

                      {{--<td>
                        {{ $item->other_title }}
                      </td> --}}

                      <td>
                        {{ $item->release_year }}
                      </td>

                      <td>
                        {{ $item->release_distribution }}
                      </td>

                      <td>
                        {{ core()->country_name($item->country) }}
                      </td>

                      <td>
                        {{ $item->releasetype }}
                      </td>

                      <td>
                        {{ $item->lingua }}
                        <!-- PWS#230101 -->
                      </td>
                      <td>
                        @php
                          $creatoil = strtotime($item->created_at);
                          $creatoil = date("d-m-Y H:i", $creatoil);
                        @endphp
                        {{ $creatoil }}
                      </td>
                      <td>
                        @if($item->release_status == 1)
                          {{ __('shop::app.customer.account.release.view.status-approved') }}
                        @elseif($item->release_status == -1)
                          {{ __('shop::app.customer.account.release.view.status-pending') }}
                        @else
                          {{ __('shop::app.customer.account.release.view.status-refused') }}
                        @endif
                      </td>
                      <td>
                        <span style="cursor:pointer;" class="removeFromCollection" onclick="if(confirm('Sei sicuro di voler rimuovere questo elemento dai preferiti?')) { 
                addToCollection(this, {{ $item->release_id }}, 'favorite', 'release'); 
                document.getElementById('{{ $item->release_id }}').remove(); 
              } 
              return false;"></span>
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
<?php /*
  <div class="my-5 overflow-hidden rounded-lg border border-gray-200 bg-gray-50 shadow-md">

    <div class="px-6 py-4 font-medium text-gray-900">
      seleziona tutti
    </div>

    <div>
      @foreach ($releases as $item)
        <div
          class="gap-3 divide-y divide-gray-100 border-t border-gray-100 bg-white px-6 py-4 font-normal text-gray-900 hover:bg-gray-50">

          <div class="flex w-full justify-between">
            <div class="flex items-center gap-6">
              <div>
                @if (config('app.env') === 'localhost')
                  <img src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" width="100" height="150" />
                @else
                  {{-- @if ($item->path)
                    <img src=" {{ url('cache/medium/' . $item->path) }} " alt="{{ $item->master_maintitle }}">
                  @else --}}
                  <img src="/storage/img/ciakit-placeholder.jpg" alt="placeholder" width="100" height="150" />
                  {{-- @endif --}}
                @endif
              </div>
              <div>
                <div class="flex flex-col">
                  <div class="flex flex-wrap items-center gap-2">
                    @if ($item->url_key && $item->release_status == 1)
                      <a class="card-link" href="/releases/{{ $item->release_id }}/{{ $item->url_key }}">
                        <h3 class="text-themeColor-500 text-lg font-bold"> {{ $item->original_title }}</h3>
                      </a>
                      <!-- PWS#8-link-master -->
                    @endif

                    <span class="text-xs text-gray-500">({{ $item->other_title }})</span>
                  </div>
                  @php
                    //dd($releases);
                  @endphp
                  <ul class="info">
                    <li>genere</li>

                    {{-- @if ($item->genres_name)
                        <li class="genres" data-value="{{ $item->genres_name }}">{{ $item->genres_name }}</li>
                      @endif --}}

                    @if ($item->release_year)
                      <li class="year" data-value="{{ $item->release_year }}">{{ $item->release_year }}</li>
                    @endif

                    @if ($item->language)
                      <li li class="languages" data-value="{{ $item->language }}">{{ $item->language }}
                      </li>
                    @endif

                  </ul>
                  <ul class="info master_director-wrapper">
                    <li>director</li>
                    {{-- @if ($item->director_name)
                        <li class="master_director">{{ $item->director_name }}</li>
                      @endif --}}
                    <li>sceneggiature, musiche, casa di produzione, lingua, tipo</li>
                  </ul>
                </div>
              </div>
              <div>

              </div>
            </div>
            <div class="flex gap-4">
              <!-- rimossi bottoni di modifica e cancellazione -->
            </div>
          </div>

        </div>
      @endforeach
    </div>
  </div>
*/ ?>
  {!! view_render_event('bagisto.shop.customers.account.release.list.after', ['releases' => $releases]) !!}
@endsection

@if ($releases->isEmpty())
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

<style>
  .removeFromCollection::after{
    display: inline-block;
    content: "\00d7";
    font-size: 20px;
    color: #e35151;
    font-weight: 700;
  }
</style>

@push('scripts')
  <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script>
    // global app configuration object
    var config = {
      routes: {
        addToCollection: "{{ route('customer.addToCollection') }}"
      },
      params: {
        user_id: @php
          if (Auth::check()) {
              echo Auth::id();
          } else {
              echo -1;
          }
        @endphp,
        table: 'release',
      }
    };
  </script>
  <meta name="_token" content="{{ csrf_token() }}" />
  <script type="text/javascript" src="{{ asset('themes/velocity/assets/js/pw_frontend.js') }}"></script>
@endpush