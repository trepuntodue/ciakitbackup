@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.cinema.release.index.page-title') }}
@endsection

@section('account-content')
    <div class="account-layout">
        <div class="account-head">
            <span class="back-icon">
                <a href="{{ route('customer.profile.index') }}">
                    <i class="icon icon-menu-back"></i>
                </a>
            </span>

            <span class="account-heading">{{ __('shop::app.cinema.release.index.title') }}</span>

            {{-- @if (!$releases->isEmpty())
                <span class="account-action">
                    <a href="{{ route('cinema.release.create') }}">{{ __('shop::app.cinema.release.index.add') }}</a>
                </span>
            @else
                <span></span>
            @endif --}}

            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('bagisto.shop.cinema.release.list.before', ['releases' => $releases]) !!}

        <div class="account-table-content">
            @if ($releases)
                <div>{{ __('shop::app.cinema.release.index.empty') }}</div>

                <br/>

                {{-- <a href="{{ route('customer.release.create') }}">{{ __('shop::app.cinema.master.index.add') }}</a> --}}
            @else

                <div class="release-holder">
                    @foreach ($releases as $release)
                        <div class="address-card">
                            <div class="details">
                                <span class="bold">{{ auth()->guard('customer')->user()->name }}</span>

                                <ul class="address-card-list">
                                    <li class="mt-5">
                                        {{ $release->original_title }}
                                    </li>

                                    <li class="mt-5">
                                        {{ $release->other_title }}
                                    </li>

                                    <li class="mt-5">
                                        {{ $release->release_year }}
                                    </li>

                                    <li class="mt-5">
                                        {{ $release->country }}
                                    </li>

                                    <li class="mt-5">
                                        {{ $release->release_distribution }}
                                    </li>

                                    <li class="mt-5">
                                        {{ $release->releasetype }}
                                    </li>

                                    <li class="mt-5">
                                        {{ $release->language }}
                                    </li>
                                    <li class="mt-5">
                                        {{ $release->release_status }}
                                    </li>
                                    <li class="mt-5">
                                        {{ $release->created_at }}
                                    </li>


                                </ul>

                                <div class="control-links mt-20">
                                    <span>
                                        <a href="{{ route('customer.release.edit', $release->id) }}">
                                            {{ __('shop::app.cinema.release.index.edit') }}
                                        </a>
                                    </span>

                                    <span>
                                        <a href="javascript:void(0);" onclick="deleteRelease('{{ __('shop::app.cinema.release.index.confirm-delete') }}', '{{ $release->id }}')">
                                            {{ __('shop::app.cinema.release.index.delete') }}
                                        </a>

                                        <form id="deleteAddresReleaseForm{{ $release->id }}" action="{{ route('release.delete', $release->id) }}" method="post">
                                            @method('delete')

                                            @csrf
                                        </form>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {!! view_render_event('bagisto.shop.cinema.release.list.after', ['releases' => $releases]) !!}
    </div>
@endsection

@push('scripts')
    <script>
        function deleteRelease(message, releaseId) {
            if (! confirm(message)) {
                return;
            }

            $(`#deleteReleaseForm${releaseId}`).submit();
        }
    </script>
@endpush
