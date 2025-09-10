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
            'restricciones' => 'nullable|string|max:1000',
        ]);

        $invitado = Invitado::create([
            'nombre' => $validated['nombre'],
            'telefono' => $validated['telefono'],
            'invitados_adicionales' => $validated['invitados'] ?? [],
            'restricciones' => $validated['restricciones'] ?? null,
        ]);

        return response()->json(['success' => true, 'invitado' => $invitado]);
    }
}