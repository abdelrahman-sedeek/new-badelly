<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function sendEmail(){
        $user = User::where('email', Auth::user()->email)->first();
        if($user)
        {
            $resetCode=Str::random('7');
            $user->reset_code=$resetCode;
            $user->reset_code_user = false; 
            $user->save();
            Mail::to($user->email)->send(new ResetPasswordMail($resetCode));
            return response()->json([
                    'message' => 'Reset code sent successfully.',
                ], 200);
            }
            return response()->json([
                'message' => 'User not found.',
            ], 404);
    }
    public function resetEmail(Request $request){
        $user = User::where('email', Auth::user()->email)->first();
        if($user &&$user->reset_code==$request->resetCode && !$user->reset_code_user)
            {
                $user->password = Hash::make($request->new_password);
                $user->reset_code = null;
                $user->reset_code_user = true;
                $user->save();
                return response()->json([
                    'message' => 'Password reset successful.',
                ], 200);
            }
        
    else
        return response()->json([    'message' => 'Invalid reset code or code already used.',], 400);


    }

}
