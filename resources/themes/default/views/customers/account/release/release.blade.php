@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.release.view.page-title') }}
@endsection

@section('account-content')
    <div class="account-layout">
        <div class="account-head">
            <span class="account-heading">Release</span>
            <div class="horizontal-rule"></div>
        </div>

        <div class="account-items-list">
            @if (count($release))
                @foreach ($release as $release)
                    <div class="account-item-card mt-15 mb-15">
                        <div class="media-info">
                            <?php $image = productimage()->getGalleryImages($release->product); ?>
                            <img class="media" src="{{ $image[0]['small_image_url'] }}" alt="" />

                            <div class="info mt-20">
                                <div class="product-name">{{$release->product->name}}</div>

                                <div>
                                    @for($i=0;$i<$release->rating;$i++)
                                        <span class="icon star-icon"></span>
                                    @endfor
                                </div>

                                <div v-pre>
                                    {{ $release->comment }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="horizontal-rule mb-10 mt-10"></div>
                @endforeach
            @else
                <div class="empty">
                    {{ __('customer::app.release.empty') }}
                </div>
            @endif
        </div>
    </div>
@endsection