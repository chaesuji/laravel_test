<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
// use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiUserController extends Controller
{
    public function userget($email){
        $user = DB::select('select name, email from users where email = ?', [$email]);
        $result = [];
        if($user){
            $result['date'] = $user[0];
            $result['success'] = 'O';
        }else{
            $result['success'] = 'X';
        }
        return $result; // json 리턴 : laravel이 자동으로 리턴해줌
    }

    public function userpost(Request $req){
        $user = DB::insert('insert into users (name, email, password) values (?,?,?)', [$req->name, $req->email, Hash::make($req->password)]);
        $result = [];
        if($user){
            $result['success'] = 'O';
            $result['name'] = $req->name;
            $result['email'] = $req->email;
        }else{
            $result['success'] = "X";
        }
        return $result;
    }

    public function userput(Request $req, $email){
        $user = DB::update('update users set name=? where email=?', [$req->name, $email]);
        $result = [];
        if($user){
            $result['success'] = 'O';
            $result['name'] = $req->name;
            $result['email'] = $email;
        }else{
            $result['success'] = 'X';
        }
        return $result;
    }

    public function userdelete($email){
        $date = Carbon::now();
        $user = DB::update('update users set deleted_at=?, delete_flg=? where email=?', [$date,'1',$email]);
        $result = [];
        if($user){
            $result['success'] = 'O';
            $result['email'] = $email;
            $result['deleted_at'] = $date;
        }else{
            $result['success'] = 'X';
        }
        return $result;
    }
}

// app/lang/en 폴더 복사 
// app/config/app.php
// locale, fallback_locale, faker_locale => ko

// LOG_CHANNEL=daily