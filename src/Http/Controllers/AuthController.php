<?php

namespace CarterParker\JWTAuth\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use CarterParker\Http\Requests\LoginRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController
{
    use ValidatesRequests;

    /**
     * Get a authenticated JWT token for the application.
     * 
     * @param LoginRequest $request
     * 
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return new JsonResponse([
                'message' => 'Invalid email address or password'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        try {
            auth()->logout();
        } catch (TokenInvalidException $e) {
            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
