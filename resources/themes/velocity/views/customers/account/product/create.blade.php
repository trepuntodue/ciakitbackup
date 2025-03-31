<!-- PWS#prod -->
@php
    $currentCustomer = auth()->guard('customer')->user();
@endphp

@extends('shop::customers.account.index')
@section('page_title')
    {{ __('shop::app.customer.account.product.create.page_title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head mb-15">
        <p class="account-heading">{{ __('shop::app.customer.account.product.create.title_choose') }}</p>
    </div>

    @include ('shop::customers.account.product.release-box')

@endsection