<?php

namespace App\Mail;

use App\Models\Admin\Cart\Order\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOrder extends Mailable
{
    use SerializesModels;

    public Order $contact;
    public function __construct(Order $contact)
    {
        $this->contact = $contact;
    }

    public function build(): SendOrder
    {
        return $this->view('emails.send_orders', ['contact' => $this->contact])
            ->from(config('mail.from.address'))
            ->subject('Заказ с сайта ' . config('app.site_short'));
    }
}
