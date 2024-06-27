<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\SendInvitation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(User::paginate(10), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): JsonResponse
    {
        $password = Str::random(16, false);
        $user = User::create($request->validated() + ['password' => $password]);

        if (in_array($request->role, array(User::UDI, User::DOCENTE))) {
            Mail::to($user->email)->send(new SendInvitation($user->name, $password));
        }

        return response()->json([
            'status' => true,
            'message' => 'Se creó un nuevo usuario.'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        if (is_null($user)) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontró el docente',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['status' => true, 'user' => $user->load('user')], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());

        return response()->json(['status' => true, 'message' => 'Se actualizó el usuario correctamente.'], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->update(['estado' => 'Deshabilitado']);

        return response()->json(['status' => true, 'message' => 'Se deshabilitó el usuario correctamente.'], Response::HTTP_OK);
    }
}
