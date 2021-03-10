<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use Auth;

class AuthController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $input=$request->all();
        $loginType = filter_var($input['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $login = [
            $loginType => $input['email'],
            'password' => $input['password']
        ];
        $credentials = $login;
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        $user=[
            'user_id'=>Auth::user()->user_id,
            'name'=>Auth::user()->name,
            'location'=>Auth::user()->location,
            'nik'=>Auth::user()->nik, 
            'email'=>Auth::user()->email, 
            'gender'=>Auth::user()->gender,
            'whatsup_number'=>Auth::user()->whatsup_number,
            'agree_wa_notification'=>Auth::user()->agree_wa_notification,
            'last_active'=>Auth::user()->last_active,
            'roles'=>Auth::user()->getRoleNames(),
        ];
        return response()->json([
            'code'=>200,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user'=>$user
        ]);
    }
    public function signup(Request $request)
    {
        //
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'code'=>200,
            'message' => 'Successfully logged out'
        ]);
    }
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    
}
