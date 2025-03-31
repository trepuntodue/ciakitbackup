<!-- PWS#chiusura -->
@component('shop::emails.layouts.master')

    <div style="background-color:#c4c4c4;padding:20px;">
        <div style="text-align: center;">
            <a href="{{ config('app.url') }}">
                <img src="{{ core()->getCurrentChannel()->logo_url ?? asset('themes/velocity/assets/images/ciakit-logo-white-600.png') }}" style="width:200px;">
            </a>
        </div>

        <div  style="font-size:16px; color:#242424; font-weight:600; margin-top: 60px; margin-bottom: 15px">
            <b>La tua richiesta è stata inviata, una volta approvata troverai il film proposto sul nostro sito.</b><br>
            <b>Grazie per il contributo!</b><br>

        </div>
    </div>

@endcomponent
