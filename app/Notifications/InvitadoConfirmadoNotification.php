<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvitadoConfirmadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $invitado;

    public function __construct($invitado)
    {
        $this->invitado = $invitado;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $msg = (new MailMessage)
            ->subject('Nueva confirmación de asistencia')
            ->greeting('¡Nueva confirmación recibida!')
            ->line('Nombre: ' . $this->invitado->nombre)
            ->line('Teléfono: ' . $this->invitado->telefono);

        if (!empty($this->invitado->invitados_adicionales)) {
            $msg->line('Acompañantes:');
            foreach ($this->invitado->invitados_adicionales as $a) {
                $msg->line('- ' . ($a['nombre'] ?? '-') . ' ' . (isset($a['nino']) && $a['nino'] ? '(Niño/a)' : ''));
            }
        }
        if ($this->invitado->restricciones) {
            $msg->line('Restricciones alimenticias: ' . $this->invitado->restricciones);
        }
        $msg->line('---');
        $msg->line('No responder a este correo.');
        return $msg;
    }
}
