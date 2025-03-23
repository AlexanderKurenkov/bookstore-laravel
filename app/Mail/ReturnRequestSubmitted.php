<?php

namespace App\Mail;

use App\Models\OrderReturn;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReturnRequestSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The return request instance.
     *
     * @var \App\Models\OrderReturn
     */
    public $return;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\OrderReturn  $return
     * @return void
     */
    public function __construct(OrderReturn $return)
    {
        $this->return = $return;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Новая заявка на возврат #' . $this->return->id)
                    ->markdown('emails.returns.submitted');
    }
}

