<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    //
    public function index()
    {
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
    public function setUser(String $id)
    {
        $pegawai = DB::table('pegawais')
            ->leftJoin('users', 'pegawais.id', '=', 'users.pegawai_id')
            ->where('pegawais.id', $id)
            ->select(
                'pegawais.id as pegawai_id',
                'pegawais.namaPegawai',
                'pegawais.bidangPenempatan',
                'users.id as user_id',
                'users.email',
                'users.password',
                'users.role'
            )
            ->first();
        return view('user.edituser', [
            'user' => Pegawai::findOrFail($id)
        ], compact('pegawai'));
    }
    public function storeUser(Request $request, string $id)
    {
        $validate = $request->validate(
            [
                'email' => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    Password::min(8)
                        ->mixedCase() // Huruf besar & kecil
                        ->numbers()   // Angka
                        ->symbols(),  // Simbol
                ],
                'role' => 'required|in:admin,user'
            ],
            [
                'email.required'     => "e-mail harus sesuai format, contoh: xxxx@gmail.com",
                'password.required'   => "password harus terdiri dari minimal 8 karakter gabungan huruf besar,kecil,angka,simbol",
                'role.required'      => "role hanya boleh admin atau user"
            ]
        );
        $validate['password'] = Hash::make($request->password);

        User::updateOrCreate(
            ['pegawai_id' => $id], // Cari user berdasarkan pegawai_id
            [
                'email'    => $validate['email'],
                'password' => $validate['password'],
                'role'     => $validate['role']
            ]
        );
        return redirect()->route('usermanagement.index')->with('success', 'Data user berhasil di update');
    }
    public function destroyUser(string $id)
    {
        // Cari data user berdasarkan pegawai_id atau id
        $user = User::where('pegawai_id', $id)->first();

        if ($user) {
            $user->delete();
            return redirect()->route('usermanagement.index')->with('success', 'Data user berhasil dihapus.');
        }

        return redirect()->route('usermanagement.index')->with('error', 'Data user tidak ditemukan.');
    }
}
