@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.release.index.page-title') }}
@endsection
@section('page-detail-wrapper')
 @if(!$releases)
    <div>nessun risultato</div>
 @else
 @foreach($releases as $reelea)
    <div>TITOLO ORIGINALE: {{$reelea->original_title}}</div>
    <div>ALTRI TITOLI: {{$reelea->other_title}}</div>
    <div>ANNO DI EDIZIONE: {{$reelea->release_year}}</div>
    <div>PAESE DI EDIZIONE: {{ core()->country_name($reelea->country) }}</div>
    <div>CASA DI DISTRIBUZIONE: {{$reelea->release_distribution}}</div> 
    <div>TIPO: {{$reelea->releasetype}}</div>
    <div>LANGUAGE: {{$reelea->language}}</div>
 @endforeach
 @endif
 @endsection