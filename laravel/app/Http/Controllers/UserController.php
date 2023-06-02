<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function login() {
        return view('login');
    }

    public function loginpost(Request $req) {
        Log::debug("Login start", $req->only('email', 'password'));
        // 유효성 체크
        Log::debug("Validator start");
        $validate = Validator::make($req->only('email', 'password'), [
            'email' => 'required|email|max:30'
            ,'password' => 'required|max:30|min:3'
        ]);
        Log::debug("Validator end");
        if($validate->fails()){
            Log::debug("Validator Fails end");

            return redirect()->back()->withErrors($validate);
        }

        // 유저 정보 습득
        $user = DB::select('select id, email, password from users where email = ?', [
            $req->email
        ]);
        
        // if(!$user || !Hash::check($req->password, $user[0]->password)){
            if(!$user || $req->password !== $user[0]->password){
                return redirect()->back()->withErrors(['아이디와 비밀번호를 확인해주세요.']);
        }
        Log::debug('select user', [$user[0]]); // 배열로 닫아야 에러 안 남

        // 유저 인증 작업
        Auth::loginUsingId($user[0]->id);
        if(!Auth::check()){
            session($user[0]->id);
            Log::debug('select fail');
            return redirect()->back()->withErrors('인증처리 에러');
        }else{
            Log::debug('유저인증 ok');
            return redirect('/');
        }

        // return redirect()->back();
    }
}
