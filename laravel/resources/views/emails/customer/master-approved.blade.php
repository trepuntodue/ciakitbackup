<!-- PWS#chiusura -->
@component('shop::emails.layouts.master')

    <div style="background-color:#c4c4c4;padding:20px;">
        <div style="text-align: center;">
            <a href="{{ config('app.url') }}">
                <img src="{{ core()->getCurrentChannel()->logo_url ?? asset('themes/velocity/assets/images/ciakit-logo-white-600.png') }}" style="width:200px;">
            </a>
        </div>

        <div  style="font-size:16px; color:#242424; font-weight:600; margin-top: 60px; margin-bottom: 15px">
            Ciao,

        </div>

        <div>
            <b>il film che hai proposto è stato approvato!  </b><br>
            <b>Clicca qui <a href='{{ url("/") }}/masters/{{$id}}/{{$slug}}'>{{$titolo}}</a> se vuoi vederne i dettagli.</b>
        </div>
    </div>

@endcomponent
