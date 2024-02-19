<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
       
        $validated = Validator::make($request->all(),[
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        
        if($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }
        $code = mt_rand(1111111,9999999);
        
        $new_user=User::create([
            'code'=>time() . '-' .$code,
            'fname'=>$request->input('fname'),
            'lname'=>$request->input('lname'),
            'email'=>$request->input('email'),
            'password'=>bcrypt($request->input('password')),
        ]);
        
        
        if($new_user){
            
            $token = $new_user->createToken('authuser')->accessToken;
            // dd($token);
            return response()->json(['token'=>$token],200);
        }
        

        // if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])){
        //     $user=Auth::user();
        //     $token = $user->createToken('authuser')->accessToken;
        //     return response()->json(['token'=>$token],200);
        // }
        
    }

    public function login(Request $request){

        // dd($request->all()); jh
        $validated = Validator::make($request->all(),[
            "email" => "required",
            "password" => "required"
        ]);
        if($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }
        if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])){
            $user=Auth::user();
            $token = $user->createToken('authuser')->accessToken;
            return response()->json(['token'=>$token],200);
        }else {
            dd('Error');
        }
        
    }
}
