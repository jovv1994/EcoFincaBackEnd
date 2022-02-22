<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\User as UserResource;
use App\Mail\PasswordReset;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $user = JWTAuth::user();
        $userResource = new UserResource($user);
        return response()->json(compact('token', 'userResource'))
            ->withCookie(
                'token',
                $token,
                config('jwt.ttl'),
                '/',
                null,
                config('app.env') !== 'local',
                true,
                false,
                config('app.env') !== 'local' ? 'None' : 'Lax'
            );
    }

    public function register(Request $request)
    {
        //VALIDACION DE FORMULARIO
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'organization_type' => 'required|string|max:255',
            'description' => 'string|max:400',
            'role' => 'required'
        ]);

        //COINCIDIR CON LA BD
        $user = User::create([
            'name' => $request->get('name'),
            'lastname' => $request->get('lastname'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'organization_type' => $request->get('organization_type'),
            'description' => $request->get('description'),
            'role' => $request->get('role')
        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json(new UserResource($user, $token), 201)
            ->withCookie(
                'token',
                $token,
                config('jwt.ttl'),
                '/',
                null,
                config('app.env') !== 'local',
                true,
                false,
                config('app.env') !== 'local' ? 'None' : 'Lax'
            );
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['message' => 'user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['message' => 'token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['message' => 'token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => 'token_absent'], $e->getStatusCode());
        }

        // return cambiado por recurso
        return response()->json(new UserResource($user), 200);
    }

    public function logout(){
        try{
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                "status" => "success",
                "message" => "User successfully logged out."
            ], 200)
                ->withCookie('token', null,
                    config('jwt.ttl'),
                    '/',
                    null,
                    config('app.env') !== 'local',
                    true,
                    false,
                    config('app.env') !== 'local' ? 'None' : 'Lax'
                );
        }catch (JWTException $e){
            return response()->json(["message" => "No se pudo cerrar sesión"], 500);
        }
    }

    public function indexCollectionCenters()
    {
        return response()->json(new UserCollection(User::where('role', 'ROLE_COLLECTION_CENTER')->get()));
    }

    public function show(User $user)
    {
        return response()->json(new UserResource($user), 200);
    }

    public function confirmPasswordReset(Request $request)
    {
        $newPassword = Hash::make($request->get('password'));
        $user = User::find($request->id);
        $user->password = $newPassword;
        $user->save();
        return response()->json(new UserResource($user), 200);
    }

    public function sendPasswordResetEmail(Request $request)
    {
        Mail::to($request->email)->send(new PasswordReset());
    }

    public function showAll()
    {
        return new UserCollection(User::all());

    }
}
