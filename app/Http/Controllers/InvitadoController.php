<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitado;

class InvitadoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:30',
            'invitados' => 'nullable|array|max:5',
            'invitados.*' => 'nullable|string|max:255',
            'ninos' => 'nullable|array|max:5',
            'ninos.*' => 'boolean',
            'restricciones' => 'nullable|string|max:1000',
        ]);

        // Combina invitados y ninos en un solo array asociativo
        $invitados_adicionales = [];
        $invitados = $validated['invitados'] ?? [];
        $ninos = $validated['ninos'] ?? [];
        foreach ($invitados as $i => $nombre) {
            $invitados_adicionales[] = [
                'nombre' => $nombre,
                'nino' => isset($ninos[$i]) ? (bool)$ninos[$i] : false
            ];
        }

        $invitado = Invitado::create([
            'nombre' => $validated['nombre'],
            'telefono' => $validated['telefono'],
            'invitados_adicionales' => $invitados_adicionales,
            'restricciones' => $validated['restricciones'] ?? null,
        ]);

        return response()->json(['success' => true, 'invitado' => $invitado]);
    }
}