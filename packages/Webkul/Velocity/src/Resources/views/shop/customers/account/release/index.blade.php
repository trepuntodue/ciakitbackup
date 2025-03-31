@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.release.index.page-title') }}
@endsection

@section('page-detail-wrapper')

    @if (!$releases->isEmpty())
        <a href="{{ route('customer.release.create') }}" class="theme-btn light unset address-button">
            {{ __('shop::app.customer.account.release.index.add') }}
        </a>
    @endif

    <div class="account-head mt-3">
        <span class="account-heading">
            {{ __('shop::app.customer.account.release.index.title') }}
        </span>

        @if (! $releases->isEmpty())
            <span class="account-action">
                <a href="{{ route('customer.release.create') }}" class="theme-btn light unset float-right">
                    {{ __('shop::app.customer.account.release.index.add') }}
                </a>
            </span>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.customers.account.release.list.before', ['releases' => $releases]) !!}

        <div class="account-table-content">
            @if ($releases->isEmpty())
                <div>{{ __('shop::app.customer.account.release.index.empty') }}</div>
            @else
            
                <div class="row address-holder no-padding">
                    @foreach ($releases as $release)
                        <div class="col-lg-4 col-md-6 col-xs-12">
                            <div class="card m-1">
                                <div class="card-body">
                                    <h5 class="card-title fw6">{{ $release->original_title   }}</h5>

                                    <ul type="none">
                                        <li>{{ $release->other_title }}</li>
                                        <li>{{ $release->release_year }}</li>
                                        <li>{{ $release->release_distribution }}</li>
                                        <li>{{ $release->language }}</li>
                                        <li>{{ $release->release_status }}</li>
                                        <li>{{ $release->release_featured }}</li>
                                        <li>{{ core()->country_name($release->country) }}</li>
                                        <li>{{ $release->releasetype }}</li>
                                    </ul>

                                    <a class="card-link" href="{{ route('customer.release.edit', $release->id) }}">
                                        {{ __('shop::app.customer.account.release.index.edit') }}
                                    </a>

                                    <a class="card-link" href="javascript:void(0);" onclick="deleteRelease('{{ __('shop::app.customer.account.release.index.confirm-delete') }}', '{{ $release->id }}')">
                                        {{ __('shop::app.customer.account.release.index.delete') }}
                                    </a>

                                    <form id="deleteReleaseForm{{ $release->id }}" action="{{ route('release.delete', $release->id) }}" method="post">
                                        @method('delete')

                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    {!! view_render_event('bagisto.shop.customers.account.release.list.after', ['releases' => $releases]) !!}
@endsection


@if ($release->isEmpty())
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
