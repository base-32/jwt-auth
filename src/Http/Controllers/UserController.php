<?php

namespace CarterParker\JWTAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController
{
    /**
     * Get the currently logged in user with the attributes.
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function find(Request $request): JsonResponse
    {
        return new JsonResponse([
            'data' => $request->user()->only(
                config('jwt-auth.current_user.attributes')
            )
        ]);
    }
}
