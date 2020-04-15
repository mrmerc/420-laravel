<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;
use Widmogrod\Monad\Either\{ Either, Left, Right };

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => [
                'redirectToProvider',
                'handleProviderCallback'
            ]
        ]);
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        // No need, because of SPA redirect
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response|void
     */
    public function handleProviderCallback(Request $request)
    {
        $user = Socialite::driver('google')->stateless()->userFromToken($request->access_token);

        $userData = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'avatar' => $user->getAvatar(),
        ];

        $result = $this->login($userData);

        if ($result instanceof Left) {
            abort(500, 'Database error');
        }

        //$this->respondWithToken($result->extract());
        return response()->json([
            'access_token' => $result->extract()
        ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param array $userData
     * @return Either
     */
    public function login(array $userData): Either
    {
        try {
            $user = User::where('email', $userData['email'])->first();

            if (!$user) {
                $user = new User;

                $userNameResult = $this->getUniqueUsername($userData['email']);

                if ($userNameResult instanceof Left) {
                    return $userNameResult;
                }

                $user->username = $userNameResult->extract();
                $user->email = $userData['email'];
                $user->password = '83JvTqXaLLQRPkTf';
                $user->name = $userData['name'];
                $user->avatar = $userData['avatar'];
                $user->provider = 'google';
                $user->provider_id = $userData['id'];

                $user->save();
            }

            $token = auth()->login($user);
            return Right::of($token);
        } catch (\Throwable $e) {
            return Left::of($e);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token)
    {
        return response()->json([
            'access_token' => $token
        ]);
    }

    /**
     * Get unique username
     * 
     * @param string $email
     * 
     * @return string nickname
     */
    private function getUniqueUsername(string $email) {
        $usernameFromEmail = explode('@', $email)[0];

        try {
            $foundNicknamesCounter = User::where(
                'username',
                'like',
                '%' . $usernameFromEmail . '%'
            )->count();

            $nicknameWithId = $usernameFromEmail . '#' . $foundNicknamesCounter;

            return Right::of($nicknameWithId);
        } catch (\Throwable $e) {
            return Left::of($e);
        }
    }
}
