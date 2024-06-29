<?php

namespace App\Http\Controllers\Inscriptions;

use App\Http\Controllers\Controller;
use App\Http\Resources\ObservationResource;
use App\Models\Inscripciones\ObservationInscription;
use App\Models\Inscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ObservationController extends Controller
{
    public function index(Inscription $inscription): JsonResponse
    {
        $observations = ObservationInscription::with('user:id,name,surnames')->where([
            'inscription_id' => $inscription->id
        ])->orderByDesc('created_at')->get();

        if ($observations->count() == 0) {
            return response()->json([
                'status' => false,
                'message' => 'No hay tareas para esta inscripción.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'tareas' => ObservationResource::collection($observations)
        ], Response::HTTP_OK);
    }

    public function store(Request $request, Inscription $inscription): JsonResponse
    {
        $request->validate([
            'description' => 'required|string|max:255',
        ]);

        ObservationInscription::create([
            'inscription_id' => $inscription->id,
            'user_id' => Auth::id(),
            'description' => $request->description
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Observación creada con éxito.'
        ], Response::HTTP_CREATED);
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, Inscription $inscription, ObservationInscription $observation): JsonResponse
    {
        $request->validate([
            'description' => 'sometimes|string|max:255',
        ]);

        $observationSelected = $observation->where('inscription_id', $inscription->id)->where('id', $observation->id)->get();

        if ($observationSelected->count() == 0) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontró esta observación.'
            ], Response::HTTP_NOT_FOUND);
        };

        $observation->update([
            'description' => $request->description
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Se actualizó la observación.'
        ], Response::HTTP_OK);
    }

    public function destroy(Inscription $inscription, ObservationInscription $observation): JsonResponse
    {
        $observationSelected = $observation->where('inscription_id', $inscription->id)->where('id', $observation->id)->get();

        if ($observationSelected->count() == 0) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontró esta tarea.'
            ], Response::HTTP_NOT_FOUND);
        };

        $observation->delete();

        return response()->json([
            'status' => true,
            'message' => 'Se eliminó la observación.'
        ], Response::HTTP_OK);
    }
}
