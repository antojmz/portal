<?php

namespace App\Models;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Log;

class Correo extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->data['asunto'])
            ->view('auth.emails.reinicioClave')
            ->with([
                'titulo' => $this->data['titulo'],
                'contenido' => $this->data['contenido'],
            ]);
    }
}