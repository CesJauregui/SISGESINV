<?php

namespace App\Http\Controllers\Inscriptions;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Inscripciones\TaskInscription;
use App\Models\Inscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Inscription $inscription): JsonResponse
    {
        $task = TaskInscription::with('user:id,name,surnames')->where([
            'inscription_id' => $inscription->id
        ])->orderByDesc('created_at')->get();

        if ($task->count() == 0) {
            return response()->json([
                'status' => false,
                'message' => 'No hay tareas para esta inscripción.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'tareas' => TaskResource::collection($task)
        ], Response::HTTP_OK);
    }

    public function store(Request $request, Inscription $inscription): JsonResponse
    {
        $request->validate([
            'description' => 'required|string|max:255',
        ]);

        TaskInscription::create([
            'inscription_id' => $inscription->id,
            'user_id' => Auth::id(),
            'description' => $request->description
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Tarea creada con éxito.'
        ], Response::HTTP_CREATED);
    }

    public function show(Inscription $inscription, TaskInscription $task): JsonResponse
    {
        $tasks = $task->with('user:id,name,surnames')->where([
            'inscription_id' => $inscription
        ])->get();

        if (is_null($tasks)) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontró la tarea',
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => true,
            'tareas' => $task
        ]);
    }

    public function update(Request $request, Inscription $inscription, TaskInscription $task): JsonResponse
    {
        $request->validate([
            'description' => 'sometimes|string|max:255',
        ]);

        $taskSelected = $task->where('inscription_id', $inscription->id)->where('id', $task->id)->get();

        if ($taskSelected->count() == 0) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontró esta tarea.'
            ], Response::HTTP_NOT_FOUND);
        };

        $task->update([
            'description' => $request->description
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Se actualizó la tarea.'
        ], Response::HTTP_OK);
    }

    public function destroy(Inscription $inscription, TaskInscription $task): JsonResponse
    {
        $taskSelected = $task->where('inscription_id', $inscription->id)->where('id', $task->id)->where('user_id', 3)->get();

        if ($taskSelected->count() == 0) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontró esta tarea.'
            ], Response::HTTP_NOT_FOUND);
        };

        $task->delete();

        return response()->json([
            'status' => true,
            'message' => 'Se eliminó la tarea.'
        ], Response::HTTP_OK);
    }
}
