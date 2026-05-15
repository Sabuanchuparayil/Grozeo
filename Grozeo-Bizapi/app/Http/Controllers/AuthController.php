<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\SuccessWithData;
use App\Http\Requests\Auth\LoginRequest;
class AuthController extends Controller
{

   public function __construct()
   {
      // $this->middleware('auth:api', ['except' => ['login']]);
   }

   public function login(LoginRequest $request)
   {
       $credentials = $request->validated();

       if (! $token = auth()->attempt($credentials)) {
           return response()->json(['error' => 'Unauthorized'], 401);
       }

       return $this->respondWithToken($token);
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
    * Log the user out (Invalidate the token).
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function logout()
   {
       auth()->logout();
       $data='Successfully logged out';
       return new SuccessWithData($data);
      // return response()->json(['message' => 'Successfully logged out']);
   }

   /**
    * Refresh a token.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function refresh(Request $request)
   {
       return $this->respondWithToken($request->token);
   }

   /**
    * Get the token array structure.
    *
    * @param  string $token
    *
    * @return \Illuminate\Http\JsonResponse
    */
   protected function respondWithToken($token)
   {
       return new SuccessWithData([
        'access_token' => $token,
        'token_type' => 'bearer',
        //'expires_in' => auth()->factory()->getTTL() * 60
        //'expires_in' => 1
       ]);

   }
}
