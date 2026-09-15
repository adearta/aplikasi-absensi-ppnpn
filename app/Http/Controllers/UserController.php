<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    public function index (){
        $pegawai = DB::table('pegawais')
        ->leftJoin('users', 'pegawais.id', '=', 'users.pegawai_id')
        ->select(
            'pegawais.id as pegawai_id',
            'pegawais.namaPegawai',
            'pegawais.bidangPenempatan',
            'users.id as user_id',
            'users.email',
            'users.password',
            'users.role'
        )
        ->get();
        return view('user.showuser', compact('pegawai'));
    }
    public function addUser(){
        $pegawai = DB::table('pegawais')
        ->select('namaPegawai')
        ->get();
        return view('user.adduser', compact('pegawai'));
    }
}
