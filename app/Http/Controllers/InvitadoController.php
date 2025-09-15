<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use App\Models\Invitado;
use Illuminate\Support\Facades\Notification;
use App\Notifications\InvitadoConfirmadoNotification;


class InvitadoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:30',
            'bebida_cena' => 'nullable|string|max:255',
            'bebida_fiesta' => 'nullable|string|max:255',
            'invitados' => 'nullable|array|max:5',
            'invitados.*' => 'nullable|string|max:255',
            'ninos' => 'nullable|array|max:5',
            'ninos.*' => 'boolean',
            'bebidas_cena_acompanantes' => 'nullable|array|max:5',
            'bebidas_cena_acompanantes.*' => 'nullable|string|max:255',
            'bebidas_fiesta_acompanantes' => 'nullable|array|max:5',
            'bebidas_fiesta_acompanantes.*' => 'nullable|string|max:255',
            'restricciones' => 'nullable|string|max:1000',
        ]);

        // Combina invitados y ninos en un solo array asociativo
        $invitados_adicionales = [];
        $invitados = $validated['invitados'] ?? [];
        $ninos = $validated['ninos'] ?? [];
        $bebidasCenaAcompanantes = $validated['bebidas_cena_acompanantes'] ?? [];
        $bebidasFiestaAcompanantes = $validated['bebidas_fiesta_acompanantes'] ?? [];
        foreach ($invitados as $i => $nombre) {
            $invitados_adicionales[] = [
                'nombre' => $nombre,
                'nino' => isset($ninos[$i]) ? (bool)$ninos[$i] : false,
                'bebida_cena' => $bebidasCenaAcompanantes[$i] ?? null,
                'bebida_fiesta' => $bebidasFiestaAcompanantes[$i] ?? null
            ];
        }

        $invitado = Invitado::create([
            'nombre' => $validated['nombre'],
            'telefono' => $validated['telefono'],
            'bebida_cena' => $validated['bebida_cena'] ?? null,
            'bebida_fiesta' => $validated['bebida_fiesta'] ?? null,
            'invitados_adicionales' => $invitados_adicionales,
            'restricciones' => $validated['restricciones'] ?? null,
        ]);


    // Enviar email personalizado (a un correo fijo de organización) usando blade
    Mail::send('emails.confirmacion', ['invitado' => $invitado], function ($message) {
        $message->to('gc91.enrique@gmail.com')
            ->subject('Nueva confirmación de asistencia');
    });

        return response()->json(['success' => true, 'invitado' => $invitado]);
    }


}