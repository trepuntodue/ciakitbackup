@extends('shop::layouts.master')

@section('page_title')
  {{ $page->page_title }}
@endsection

@section('head')
  @isset($page->meta_title)
    <meta name="title" content="{{ $page->meta_title }}" />
  @endisset

  @isset($page->meta_description)
    <meta name="description" content="{{ $page->meta_description }}" />
  @endisset

  @isset($page->meta_keywords)
    <meta name="keywords" content="{{ $page->meta_keywords }}" />
  @endisset
@endsection

@section('content-wrapper')
  <div class="p-2 cms-page-container">
    <h1>{{ $page->page_title }}</h1>
    {!! DbView::make($page)->field('html_content')->render() !!}
  </div>
@endsection
