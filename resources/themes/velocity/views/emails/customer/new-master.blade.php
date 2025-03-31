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
            <b> è stata inserita una nuova proposta di aggiunta Master nel sistema, fai click sul link qui sotto per esaminarla:  </b><br>
            <b> <a href='{{ url("/") }}/admin/cinema/masters/create?{{ http_build_query($data); }}'>Vedi Master proposto</a> </b>
        </div>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            {{ __('shop::app.mail.customer.new.thanks') }}
        </p>
    </div>

@endcomponent
