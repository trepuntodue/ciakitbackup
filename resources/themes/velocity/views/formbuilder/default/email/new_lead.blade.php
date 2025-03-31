@component('shop::emails.layouts.master')

    <div>
        <div style="text-align: center;">
            <a href="{{ config('app.url') }}">
                @include ('shop::emails.layouts.logo')
            </a>
        </div>

        <div style="margin-top: 40px;">
            {!! nl2br($bodyContent) !!}
        </div>
    </div>

@endcomponent