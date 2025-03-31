<?php
/* PWS#chiusura */

namespace PSW\Cinema\Shop\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class NewMasterFromCustomerApproved extends Mailable
{
    use Queueable, SerializesModels;
    public $id, $slug, $titolo, $to_email;

    public function __construct($id, $slug, $titolo, $to_email){
      $this->id = $id;
      $this->slug = $slug;
      $this->titolo = $titolo;
      $this->to_email = $to_email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
      return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
                  ->to($this->to_email)
                  ->subject('Il film che hai proposto è stato approvato.')
                  ->view('emails.customer.master-approved')
                  ->with([
                    'id' => $this->id,
                    'slug' => $this->slug,
                    'titolo' => $this->titolo
                  ],
                );;

    }
}
