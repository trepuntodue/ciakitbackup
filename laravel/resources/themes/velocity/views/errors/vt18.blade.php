<!-- PWS#13-vt18 -->

Questo film è vietato ai minori.

@php
    $user = Auth::user();
    if(Auth::check() && strtotime('18 years ago') < strtotime($user->date_of_birth) && $user->date_of_birth != '0000-00-00' && $user->date_of_birth != false){
        // Blocco visibile ad utenti loggati ma minorenni
@endphp
    Non puoi visualizzarlo.
@php
    } else if(Auth::check() && ($user->date_of_birth == '0000-00-00' || $user->date_of_birth == false)){
        // Blocco visibile ad utenti loggati ma che non hanno inserito la data di nascita.
@endphp
    Inserisci la tua data di nascita per vedere i film riservati agli adulti
@php
    } else if(!Auth::check()){
        // Blocco visibile ad utenti non loggati
@endphp
    Effettua l'accesso e inserisci la tua data di nascita per vedere i film riservati agli adulti.
@php
    }
@endphp

