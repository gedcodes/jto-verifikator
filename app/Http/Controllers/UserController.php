<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    //

    public function __invoke(Request $request)
    {
        return response()->json($this->guard());
        // if (JWTAuth::check()) {
        //     return response()->json(JWTAuth::user());
        // }
        //$user = JWTAuth::check();
        //if (JWTAuth::check()) {
        //return $this->sendError("user not found", [], 403);
        //return response()->json(JWTAuth::user());
        //}
        // if (auth()->check()) {
        //     return response()->json(auth()->user());
        // }
        // try {
        //     $user = JWTAuth::parseToken()->authenticate();
        //     if (!$user) {
        //         return $this->sendError("user not found", [], 403);
        //     }
        // } catch (JWTException $e) {
        //     return $this->sendError('err', [], 500);
        // }

        //return $this->sendResponse(auth()->user(), "user data retrieved", 200);
    }
}
