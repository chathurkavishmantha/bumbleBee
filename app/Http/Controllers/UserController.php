<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first()], 400);
        }

        //Create JWT token
        try {
            if (! $token = JWTAuth::attempt($credentials,['exp' => Carbon::now()->addYears(1)->timestamp])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], 401);
        }

        $data = null;
        $user = Auth::user();
    
        return response()->json([
            'success' => true,
            'token' => $token,
            'role'=> $user->roles->first()->name,
        ],200);

    }

    public function register(Request $request)
    {
        $credentials = $request->only('email', 'password','full_name','date_of_birth');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50',
            'full_name' => 'required',
            'date_of_birth' => 'required|date|date_format:Y/m/d|before:today',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first()], 400);
        }

        DB::beginTransaction();

        $id = 'CU'.date('md').mt_rand(10000,99999);

        $customer_data = [
            'customer_id' => $id,
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
        ];

        $customer = Customer::create($customer_data);

        $user = User::create([
            'user_id' => $id,
            'name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if( !$customer || !$user )
        {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Something happended. Please try again later.',
            ], 400);

        } else {
            // Else commit the queries
            DB::commit();

            $user->assignRole('customer');
            $user->syncRoles('customer');

            return response()->json([
                'success' => true,
                'message' => 'Registration Successfully',
            ], 200);

        }
    }

    public function logout(Request $request)
    {
        auth()->logout();

        return response()->json([
            "success" => true,
            "message" => "User logged out"
        ]);
    }
}
