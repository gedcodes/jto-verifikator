<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    //

    // public function Login(Request $request)
    // {

    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     if (!$this->guard()->attempt($credentials)) {
    //         return response()->json([

    //             'message' => 'The provided credentials are incorrect.'
    //         ], 500);
    //     }

    //     $token = $this->guard()->user()->createToken('auth-token')->plainTextToken;
    //     return response()->json([
    //         'user' => $this->guard()->user(),
    //         'access_token' => $token,
    //         'token_type' => 'Bearer',
    //     ], 200);
    //     // return response()->json([
    //     //     'access_token' => $token,
    //     //     'token_type' => 'Bearer',
    //     // ], 200);
    // }

    public function Login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('message.SIMPAN_GAGAL'), $validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');
        if (!$this->guard()->attempt($credentials)) {
            return response()->json(
                ['error' => 'Login Gagal'],
                401
            );
        }
        $token = JWTAuth::attempt($validator->validated());
        Log::info('APP_ENV', ['env' => env('APP_ENV')]);
        Log::info('Full ENV dump', $_ENV);

        $url = env('MIX_API_URL_JTO');
        $path = env('PATH_URL');

        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        Log::info('MIX_API_URL_JTO = ' . $url);

        $client = new Client();

        try {
            $response = $client->post($url . '/v2pb/login', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($data),
            ]);
            $responseData = $response->getBody()->getContents();
            Log::info('Response dari API eksternal:', ['response' => $responseData]);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse();
                $responseData = $errorResponse->getBody()->getContents();
                Log::error('API error response:', ['response' => $responseData]);
            } else {
                $responseData = $e->getMessage();
                Log::error('API request error:', ['message' => $responseData]);
            }
        }
    
        // Jangan pakai dd() untuk produksi atau debugging log, gunakan Log:
        Log::debug('Data hasil API:', ['responseData' => $responseData]);
    
        $jsonData = json_decode($responseData);
        $lokasi = storage_path('app/app_config.json');
        file_put_contents($lokasi, $responseData);

        $success['access_token'] = $token;
        $success['token_type'] = 'Bearer';
        $success['user'] = auth()->user();
        $success['urljto'] = $url;
        $success['pathUrl'] = $path;
        $success['tokenJTO'] = $jsonData->accessToken;

        return $this->sendResponse($success, __('message.LOGIN_BERHASIL'));
    }

    public function getUser(Request $request)
    {
        //return response()->json(auth()->guard('web'));
        //$url = env('MIX_API_URL_JTO');
        $query = DB::table('jt_lokasi_uppkb')->first();

        if (JWTAuth::check()) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil',
                'user' => auth()->user(),
                'uppkb' => $query
            ], 200);
            //return response()->json(auth()->user());
        }
    }


    public function logout(Request $request)
    {
        // $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        // if ($removeToken) {
        //     //return response JSON
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Logout Berhasil!',
        //     ]);
        // }
        if (auth()->check()) {
            //$user = $request->user();

            //$user->tokens()->delete();
            auth()->logout();
            //JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'success' => true,
                'message' => 'logged out successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Logout Gagal'
            ], 400);
        }
    }

    public function guard($guard = 'web')
    {
        return Auth::guard($guard);
    }
}
