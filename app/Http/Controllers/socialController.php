<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;


class socialController extends Controller
{
    public function redirectToFacebook(){
        return Socialite::driver('facebook')->stateless()->redirect();
        
    }
    public function redirectToGoogle(){
        return Socialite::driver('google')->stateless()->redirect();
    }
    public function handelFacebookCallback(){
        Log::info('Callback function accessed');
        
        $user=Socialite::driver('facebook')->stateless()->user();
        $nameParts = explode(' ', $user->name);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
        $authUser=User::where('social_id',$user->id)->first();
        if(!$authUser){
            $authUser=User::create([
                'fristName'=>$firstName,
                'lastName'=>$lastName,
                'email'=>$user->email,
                'social_type'=>'facebook',
                'social_id'=>$user->id,
                'password'=>Hash::make($user->name.'@'. $user->id),
                
            ]);
            
        }
        Auth::login($authUser);
        $token = $authUser->createToken('MyApp')->accessToken;
        
        return response()->json([
            'user' => $authUser,
            'token' => $token,
        ],200);
        // return response()->json(['data'=>$findUser],200);
    }    
    public function handelGoogleCallback(){
        
        $user=Socialite::driver('google')->stateless()->user();
        $nameParts = explode(' ', $user->name);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
        $authUser=User::where('social_id',$user->id)->first();
        if(!$authUser){
            $authUser=User::create([
                'firstName'=>$firstName,
                'lastName'=>$lastName,
                'email'=>$user->email,
                'social_type'=>'google',
                'social_id'=>$user->id,
                'password'=>Hash::make($user->id .'@'. $user->name),
                'phoneNumber'=>$user->id,
                
            ]);
    
        }
        Auth::login($authUser);
        $token = $authUser->createToken('MyApp')->accessToken;
        
        return response()->json([
            'token' => $token,
            'user' => $authUser,
        ],200);
        // return response()->json(['data'=>$findUser],200);
    }    
}
