@php
    $currentCustomer = auth()->guard('customer')->user();
@endphp

@extends('shop::customers.account.index')
@section('page_title')
    {{ __('shop::app.customer.account.product.create.page-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head mb-15">
        <span class="account-heading">{{ __('shop::app.customer.account.product.create.title') }}</span>
    </div>

    <meta name="_token" content="{{csrf_token()}}" />
    <form id="crea_product" method="post" action="{{ route('customer.product.store', ['id' => $release_id]) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
        <div class="account-table-content mb-2">
            @csrf
            <div class="name">
                <label>
                    {{ __('shop::app.customer.account.product.create.name') }}
                    <input
                        id="name"
                        class="control"
                        type="text"
                        name="name"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.name') }}&quot;">
                </label>
            </div>
            <div class="price">
                <label>
                    {{ __('shop::app.customer.account.product.create.price') }}
                    <input
                        id="price"
                        class="control"
                        type="number"
                        name="price"
                        step=".01"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.release.create.price') }}&quot;" min="1">
                </label>
            </div>

            <div class="button-group">
                <button class="theme-btn" type="submit">
                    {{ __('shop::app.customer.account.product.create.submit') }}
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script type="text/javascript">
    window.flashMessages = [];

    @if ($success = session('success'))
        window.flashMessages = [{'type': 'alert-success', 'message': "{{ $success }}" }];
    @elseif ($warning = session('warning'))
        window.flashMessages = [{'type': 'alert-warning', 'message': "{{ $warning }}" }];
    @elseif ($error = session('error'))
        window.flashMessages = [{'type': 'alert-error', 'message': "{{ $error }}" }];
    @elseif ($info = session('info'))
        window.flashMessages = [{'type': 'alert-info', 'message': "{{ $info }}" }];
    @endif

    window.serverErrors = [];

    @if (isset($errors))
        @if (count($errors))
            window.serverErrors = @json($errors->getMessages());
        @endif
    @endif
</script>
@endpush