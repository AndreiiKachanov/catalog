<?php

namespace App\Mail;

use App\Models\Admin\Cart\Order\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOrder extends Mailable
{
    use SerializesModels;

    public $contact;
    public function __construct(Order $contact)
    {
        $this->contact = $contact;
    }

    public function build()
    {
        return $this->view('emails.send_orders2', ['contact' => $this->contact])
            ->from(config('mail.from.address'))
            ->subject("Заказ с сайта " . config('app.site_short'));
    }
}
