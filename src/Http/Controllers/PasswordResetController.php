<?php

namespace CarterParker\JWTAuth\Http\Controllers;

use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PasswordResetController
{
    use ValidatesRequests;

    /**
     * Illuminate password broker instance.
     */
    protected $passwordBroker;

    /**
     * PasswordResetController constructor.
     */
    public function __construct(Container $container)
    {
        $this->passwordBroker = $container->make('auth.password.broker');
    }

    /**
     * Send the reset password email to the user.
     */
    public function sendResetEmail(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => ['required', 'email']
        ]);

        $credentials = $request->only('email');

        if ($user = $this->passwordBroker->getUser($credentials)) {
            $this->passwordBroker->sendResetLink($user->toArray());
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Reset the users password.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $data = $this->validate($request, [
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'min:8', 'same:password_confirmation'],
            'password_confirmation' => ['required']
        ]);

        $response = $this->passwordBroker->reset($data, static function (Model $user, $password) {
            $user->update(['password' => $password]);
        });

        switch ($response) {
            case PasswordBroker::INVALID_USER:
            case PasswordBroker::INVALID_TOKEN:
                return new JsonResponse([
                    'message' => 'The given token or email address is invalid'
                ], Response::HTTP_UNAUTHORIZED);
            case PasswordBroker::PASSWORD_RESET:
                return new JsonResponse([], Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Verify that the password reset is valid.
     */
    public function verifyPasswordReset(Request $request): JsonResponse
    {
        $data = $this->validate($request, [
            'email' => ['required', 'email'],
            'token' => ['required', 'string']
        ]);

        $user = $this->passwordBroker->getUser($data);

        if ($user && $this->passwordBroker->tokenExists($user, $data['token'])) {
            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse([
            'message' => 'Invalid email address or token'
        ], Response::HTTP_UNAUTHORIZED);
    }
}
