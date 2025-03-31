<?php
/* PWS#chiusura */

namespace PSW\Cinema\Shop\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class NewMasterFromCustomer extends Mailable
{
    use Queueable, SerializesModels;
    public $attachs;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function __construct(public $data, $attachs = array()){
      $this->attachs = $attachs;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
      return is_array($this->data) ? $this->toAdmin() : $this->toCustomer();

    }

    private function toCustomer(){
      return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
                  ->to(Auth::user()->email)
                  ->subject('La tua richiesta di aggiunta Film è stata ricevuta.')
                  ->view('shop::emails.customer.new-master-cust');
    }

    private function toAdmin(){
      $message = $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
                  ->to('master@ciakit.com')
                  ->subject('Nuovo inserimento Master da utente')
                  ->view('shop::emails.customer.new-master')->with(['data' => $this->data]);
      foreach($this->attachs as $key => $imagePath){
        $message->attach($imagePath);
      }
      return $message;
    }
}
