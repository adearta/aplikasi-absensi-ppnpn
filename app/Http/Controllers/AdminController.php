<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use App\Models\Absensi;
use illuminate\View\View;
use illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private $pegawai;
    private $presensi;
    /**
     * Display a listing of the resource.
     */
    public function construct()
    {
        $this->presensi = Absensi::tablename('absensis');
        $this->pegawai = pegawai::tablename('pegawais');
    }
    public function index()
    {
        //menampilkan index dashboard admin
        return view('admin.admin_index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function showListPegawai()
    {

        //menampilkan daftar pegawai
        $pegawais = pegawai::latest()->paginate(10);
        return view('admin.show_pegawai', compact('pegawais'));
    }
    public function create()
    {
        //
        return view('admin.add_pegawai');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //menambahkan pegawai baru ke database
        $request->validate(
            [
                'nik' => 'required|unique:pegawais,nik',
                'namaPegawai' => 'required',
                'tanggalLahir' => 'required|date',
                'usia' => 'required|integer',
                'jenisKelamin' => 'required',
                'alamat' => 'required',
                'agama' => 'required',
                'statusPernikahan' => 'required',
                'kewarganegaraan' => 'required',
                'bidangPenempatan' => 'required',
                'lamaBekerja' => 'required',
                'gaji' => 'required',
            ],
            [
                'nik.required' => 'NIK harus diisi.',
                'nik.unique' => 'NIK sudah digunakan oleh pegawai lain.',
                'namaPegawai.required' => 'Nama pegawai harus diisi.',
                'tanggalLahir.required' => 'Tanggal lahir harus diisi.',
                'tanggalLahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
                'usia.required' => 'Usia harus diisi.',
                'usia.integer' => 'Usia harus berupa angka.',
                'jenisKelamin.required' => 'Jenis kelamin harus dipilih.',
                'alamat.required' => 'Alamat harus diisi.',
                'agama.required' => 'Agama harus dipilih.',
                'statusPernikahan.required' => 'Status pernikahan harus dipilih.',
                'kewarganegaraan.required' => 'Kewarganegaraan harus dipilih.',
                'bidangPenempatan.required' => 'Bidang penempatan harus diisi.',
                'lamaBekerja.required' => 'Lama bekerja harus diisi.',
                'gaji.required' => 'Gaji harus diisi.',
            ]
        );

        //create new pegawai
        pegawai::create([
            'nik' => $request->nik,
            'namaPegawai' => $request->namaPegawai,
            'tanggalLahir' => $request->tanggalLahir,
            'usia' => $request->usia,
            'jenisKelamin' => $request->jenisKelamin,
            'alamat' => $request->alamat,
            'agama' => $request->agama,
            'statusPernikahan' => $request->statusPernikahan,
            'kewarganegaraan' => $request->kewarganegaraan,
            'bidangPenempatan' => $request->bidangPenempatan,
            'lamaBekerja' => $request->lamaBekerja,
            'gaji' => $request->gaji,
        ]);
        return redirect()->route('pegawai_present')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return view('admin.edit_pegawai', [
            'pegawai' => pegawai::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate(
            [
                'nik' => 'required|unique:pegawais,nik,' . $id,
                'namaPegawai' => 'required',
                'tanggalLahir' => 'required|date',
                'usia' => 'required|integer',
                'jenisKelamin' => 'required',
                'alamat' => 'required',
                'agama' => 'required',
                'statusPernikahan' => 'required',
                'kewarganegaraan' => 'required',
                'bidangPenempatan' => 'required',
                'lamaBekerja' => 'required',
                'gaji' => 'required',
            ],
            [
                'nik.required' => 'NIK harus diisi.',
                'nik.unique' => 'NIK sudah digunakan oleh pegawai lain.',
                'namaPegawai.required' => 'Nama pegawai harus diisi.',
                'tanggalLahir.required' => 'Tanggal lahir harus diisi.',
                'tanggalLahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
                'usia.required' => 'Usia harus diisi.',
                'usia.integer' => 'Usia harus berupa angka.',
                'jenisKelamin.required' => 'Jenis kelamin harus dipilih.',
                'alamat.required' => 'Alamat harus diisi.',
                'agama.required' => 'Agama harus dipilih.',
                'statusPernikahan.required' => 'Status pernikahan harus dipilih.',
                'kewarganegaraan.required' => 'Kewarganegaraan harus dipilih.',
                'bidangPenempatan.required' => 'Bidang penempatan harus diisi.',
                'lamaBekerja.required' => 'Lama bekerja harus diisi.',
                'gaji.required' => 'Gaji harus diisi.',
            ]
        );
        Pegawai::where('id', $id)->update([
            'nik' => $request->nik,
            'namaPegawai' => $request->namaPegawai,
            'tanggalLahir' => $request->tanggalLahir,
            'usia' => $request->usia,
            'jenisKelamin' => $request->jenisKelamin,
            'alamat' => $request->alamat,
            'agama' => $request->agama,
            'statusPernikahan' => $request->statusPernikahan,
            'kewarganegaraan' => $request->kewarganegaraan,
            'bidangPenempatan' => $request->bidangPenempatan,
            'lamaBekerja' => $request->lamaBekerja,
            'gaji' => $request->gaji,
        ]);
        return redirect()->route('pegawai_present')->with('success', 'Data pegawai berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $id = pegawai::findOrFail($id);
        $id->delete();
        return redirect()->route('pegawai_present')->with('success', 'Data pegawai berhasil dihapus!');
    }

    public function showAbsensiPegawaiToday()
    {
        // menampilkan pegawai yang absen hari ini
        $presensi = DB::table('pegawais')
            ->leftJoin('absensis', function ($join) {
                $join->on('pegawais.id', '=', 'absensis.pegawai_id')
                    ->whereDate('absensis.tanggal', now()->toDateString());
            })
            ->select(
                'pegawais.namaPegawai',
                'pegawais.bidangPenempatan',
                'absensis.jam_masuk',
                'absensis.jam_keluar',
                'absensis.status'
            )
            ->get();
        return view('admin.presence.present', compact('presensi'));
    }
}
