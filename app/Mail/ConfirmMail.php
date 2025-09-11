<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Invitado;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(Invitado $invitado)
    {
        $this->invitado = $invitado;
    }

    public function build()
    {
        return $this->subject('Boda: Nuevo Asistente Confirmado')
            ->view('emails.confirm');
    }
}
