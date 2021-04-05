<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()){
            $response = [
                'status' => 'error',
                'msg' => 'Validator error',
                'errors' => $validate->errors(),
                'content' => null
            ];
            return response()->json($response, 200);
        } else{
            $credentials = request(['email', 'password']);
            if (Auth::attempt($credentials)) {
                $user = User::where('email', $request->email)->first();
                if (! \Hash::check($request->password, $user->password, [])) {
                    throw new \Exception('Error in Login');
                }
                $tokenResult = $user->createToken('token-auth')->plainTextToken;
                $respon = [
                    'status' => 'success',
                    'msg' => 'Login success',
                    'errors' => null,
                    'content' => [
                        'status_code' => 200,
                        'access_token' => $tokenResult,
                        'token_type' => 'Bearer',
                    ]
                ];
                return response()->json($respon, 200);
            } else {
                $respon = [
                    'status' => 'error',
                    'msg' => 'Unathorized',
                    'errors' => null,
                    'content' => null,
                ];
                return response()->json($respon, 401);
            }
        }
    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => ['required', Rule::unique('users')],
            'username' => ['required', Rule::unique('users')],
            'password' => 'required',
            'role_id' => 'required',
        ]);

        if ($validate->fails()){
            $response = [
                'status' => 'error',
                'msg' => 'Validator error',
                'errors' => $validate->errors(),
                'content' => null
            ];
            return response()->json($response, 200);
        }

        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id
        ]);
        $respon = [
            'status' => 'success',
            'msg' => 'Register success',
            'errors' => null,
            'content' => [
                'status_code' => 200,
                'username' => $user->username
            ]
        ];
        return response()->json($respon, 200);
    }

    public function logout() {
        auth()->user()->tokens()->delete();

        $respon = [
            'status' => 'success',
            'msg' => 'Logout success',
            'errors' => null,
            'content' => null,
        ];
        return response()->json($respon, 200);
    }

    public function show()
    {
        return new UserResource(auth()->user());
    }
}
