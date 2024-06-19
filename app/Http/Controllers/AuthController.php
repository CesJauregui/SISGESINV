<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Mail\RecuperarContrasena;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'estado' => 'Habilitado'])) {
            return response()->json([
                'token' => $request->user()->createToken('API TOKEN')->plainTextToken,
                'message' => 'Success'
            ], Response::HTTP_OK);
        }
        return response()->json(['status' => false, 'message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    public function logout(User $user): JsonResponse
    {
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully',
        ], Response::HTTP_OK);
    }

    public function sendResetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);
        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();
        DB::table('password_reset_tokens')->insert(['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]);
        Mail::to($request->email)->send(new RecuperarContrasena($token));

        return response()->json([
            'status' => true,
            'message' => 'Se envió el correo para recuperar la contraseña'
        ], Response::HTTP_OK);
    }

    public function resetPassword(Request $request, $token): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_reset_tokens')->where(['email' => $request->email, 'token' => $token])->first();

        if (!$updatePassword) {
            return response()->json(['message' => 'Token inválido']);
        }

        User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        return response()->json(['status' => true, 'message' => 'Tu contraseña se ha cambiado correctamente'], Response::HTTP_OK);
    }
}
