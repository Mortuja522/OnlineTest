<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //

    public function register(Request $request){

        // $request->validate([

        //     'name'=>'required',
        //     'email'=>'required|email||',
        //     'password'=>'required',
        //     'confirm_password'=>'required|same:password'

        // ]);
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'confirm_password'=>'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->
            json([
                'message'=>'validation fails',
                'errors'=>$validator->errors()
            ],422);
        }  

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        return response()->
        json([
            'message'=>'Registation successfull',
            'date'=>$user
        ],200);

    }


    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            
            'email'=>'required|email',
            'password'=>'required'
        
        ]);

        if ($validator->fails()) {
            return response()->
            json([
                'message'=>'validation fails',
                'errors'=>$validator->errors()
            ],422);
        }


        $user =User::Where('email',$request->email)->first();

        if( $user){

            if(Hash::check($request->password,$user->password))
            {
                $token=$user->createToken('auth-token')->plainTextToken;

                return response()->
                json([
                    'message'=>'login Successful',
                    'token'=>$token,
                    'data'=>$user
                    
                ],200);
                        

            }
            else{
                return response()->
            json([
                'message'=>'Incorrect credentials',
                
            ],400);
                    
            }

        }
        else{
            return response()->
            json([
                'message'=>'Incorrect credentials',
                
            ],400);


        }


    }

    public function user(Request $request){


        return response()->
            json([
                'message'=>'User Successfully Fetched',
                'data'=>$request->user()
                
            ],200);

    }

    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();

        return response()->
        json([
            'message'=>'User Successfully logout',
            
        ],200);
    }


}
