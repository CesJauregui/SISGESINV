<?php

namespace App\Http\Controllers;

use App\Http\Requests\InscriptionRequest;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\GraduateResource;
use App\Http\Resources\DegreeProcessesResource;
use App\Models\Inscripciones\ObservationInscription;
use App\Models\DegreeProcess;
use App\Models\Inscripciones\ArchiveInscription;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InscriptionController extends Controller
{
    public function index()
    {
        return DegreeProcessesResource::collection(DegreeProcess::with('inscriptions', 'graduates')->paginate(10));
    }

    public function store(InscriptionRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $degreeProcess = DegreeProcess::create($request->validated());

            // Crear una nueva inscripción asociada al proceso de titulación
            $inscription = Inscription::create([
                'degree_processes_id' => $degreeProcess->id,
                'reception_date_faculty' => $request->reception_date_faculty,
                'approval_date_udi' => $request->approval_date_udi,
            ]);
            DB::table('inscription_user')->insert([
                'inscription_id' => $inscription->id,
                'user_id' => $request->user_id
            ]);

            ObservationInscription::create([
                'inscription_id' => $inscription->id,
                'user_id' => Auth::id(),
                'description' => $request->description,
            ]);

            $uploadedFiles = [];
            foreach ($request->file('archives') as $file) {
                // Subir archivo a Cloudflare R2
                $path = Storage::disk('r2')->put('uploads', $file);
                $uploadedFiles[] = $path;
                ArchiveInscription::create([
                    'inscription_id' => $inscription->id,
                    'user_id' => Auth::id(),
                    'archive' => $path
                ]);
            }

            // Crear los registros en la tabla pivot degree_user
            foreach ($request->user_ids as $userId) {
                DB::table('degree_user')->insert([
                    'degree_processes_id' => $degreeProcess->id,
                    'user_id' => $userId,
                ]);
            }
            DB::commit();

            return response()->json(['message' => 'Inscripción creada con éxito'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear la inscripción', $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(DegreeProcess $inscription)
    {
        if ($inscription->count() == 0) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontró el docente',
            ], Response::HTTP_NOT_FOUND);
        }
        $inscription->load(['graduates', 'inscriptions']);
        return new DegreeProcessesResource($inscription);
    }

    public function update(InscriptionRequest $request, Inscription $inscription): JsonResponse
    {
        $userIds = $request->user_ids;

        DB::beginTransaction();

        try {
            $process = $inscription->with('degree_processes')->find($inscription->id);
            $process->update($request->validated());
            $process->degree_processes->update($request->validated());

            if (!empty($userIds)) {
                $inscrip = DegreeProcess::findOrFail($inscription->id);

                $currentUserIds = $inscrip->graduates()->pluck('user_id')->toArray();

                $this->insertUsers($inscrip, $userIds, $currentUserIds);
                $this->updateUsers($inscrip, $userIds, $currentUserIds);
                $this->deleteUsers($inscrip, $userIds, $currentUserIds);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Se actualizó la inscripción correctamente',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Hubo un error al actualizar los registros: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(DegreeProcess $inscription): JsonResponse
    {
        $inscription->delete();

        return response()->json([
            'status' => true,
            'message' => 'Se eliminó la inscripción.'
        ], Response::HTTP_OK);
    }

    public function getTeachers(): JsonResponse
    {
        $teachers = User::where('role', User::DOCENTE)->get();

        if (!$teachers) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontró información'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'teachers' => TeacherResource::collection($teachers)
        ], Response::HTTP_OK);
    }

    public function getGraduatesStudents(): JsonResponse
    {
        $graduatesStudents = User::whereIn('role', [User::EGRESADO, User::ESTUDIANTE])->get();

        if (!$graduatesStudents) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontró información.'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => true,
            'graduates_students' => GraduateResource::collection($graduatesStudents)
        ], Response::HTTP_OK);
    }

    public function changeState(Request $request, Inscription $inscription): JsonResponse
    {
        $inscription->update(['status' => $request->status]);

        return response()->json([
            'status' => true,
            'message' => 'Se cambió el estado.'
        ], Response::HTTP_OK);
    }

    private function insertUsers($inscripcion, $newUserIds, $currentUserIds)
    {
        // Filtrar los IDs que no existen en la inscripción
        $userIdsToInsert = array_diff($newUserIds, $currentUserIds);

        // Insertar nuevos usuarios
        if (!empty($userIdsToInsert)) {
            $inscripcion->graduates()->attach($userIdsToInsert);
        }
    }

    private function updateUsers($inscripcion, $newUserIds, $currentUserIds)
    {
        // Encontrar IDs que necesitan ser actualizados
        $userIdsToUpdate = array_diff($newUserIds, $currentUserIds);

        // Actualizar los usuarios que existen en la inscripción
        if (!empty($userIdsToUpdate)) {
            $inscripcion->graduates()->sync($userIdsToUpdate, false);
        }
    }

    private function deleteUsers($inscripcion, $newUserIds, $currentUserIds)
    {
        // Encontrar IDs que deben ser eliminados
        $userIdsToDelete = array_diff($currentUserIds, $newUserIds);

        // Eliminar los usuarios que ya no deben estar en la inscripción
        if (!empty($userIdsToDelete)) {
            $inscripcion->graduates()->detach($userIdsToDelete);
        }
    }
}
