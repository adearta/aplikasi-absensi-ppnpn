<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresencesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $pegawaiID = Auth::user()->pegawai_id;
        $absensi = \App\Models\Absensi::where('pegawai_id', $pegawaiID)->get();
        $pegawai = \App\Models\Pegawai::where('id', $pegawaiID)->get();
        return view('pages.presence.index_present', compact('absensi', 'pegawai'));
        //cukup menampilkan data absensi pegawai yang sedang login
        // return view('pages.presence.index_present', compact('absensi'));
    }

    public function showPegawai(string $id) {}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource
     */
    public function cekAbsensi()
    {
        //
        $pegawaiID = Auth::user()->pegawai_id;
        
        // Cek apakah pegawai sudah absen hari ini
        $sudahAbsen = \App\Models\Absensi::where('pegawai_id', $pegawaiID)
            ->whereDate('tanggal', now()->toDateString())
            ->exists();
        //cek apakah pegwai sudah absen pulang
        $sudahPulang = \App\Models\Absensi::where('pegawai_id', $pegawaiID)
            ->whereDate('tanggal', now()->toDateString())
            ->whereNotNull('jam_keluar')
            ->exists();

        return view('absensi.index', compact('sudahAbsen', 'sudahPulang'));
        // return view('absensi.index');
    }
    public function absenPulang(Request $request)
    {
        $pegawaiID = Auth::user()->pegawai_id; // Ambil ID pegawai dari user yang sedang login
        $pegawai = Auth::user()->pegawai;
        if ($pegawaiID) {
            // Cari data absen hari ini untuk pegawai yang sedang login
            $absen = \App\Models\Absensi::where('pegawai_id', $pegawaiID)
                ->whereDate('tanggal', now()->toDateString())
                ->first();

            if ($absen) {
                if ($absen->jam_masuk === null) {
                    return redirect()->route('absensi.index')->with('error', 'Anda belum melakukan absensi masuk hari ini.');
                } else {              
                    //cek apakah sudah 8 jam
                    $tanggal = \Carbon\Carbon::parse($absen->tanggal)->format('Y-m-d');
                    $waktuMasuk = \Carbon\Carbon::parse($tanggal . '' . $absen->jam_masuk, 'Asia/Makassar');
                    $waktuBolehPulang = $waktuMasuk->copy()->addHours(8);
                    $waktuSekarang = now('Asia/Makassar');
                    if ($waktuSekarang->lt($waktuBolehPulang)) {
                        return redirect()->back()->with('error', 'Anda Belum Mencapai 8 Jam Kerja. Anda Baru Bisa Pulang Pukul ' . $waktuBolehPulang->format('H:i') . ' WITA');
                    } else {
                        //update data jam keluar
                        $absen->jam_keluar = now('Asia/Makassar')->format('H:i:s'); // Set waktu absen pulang
                        $absen->save();

                        return redirect()->route('absensi.index')
                            ->with('success', 'Anda sudah melakukan absensi pulang hari ini.');
                    }
                }
            } else {
                return redirect()->route('absensi.index')->with('error', 'Anda belum melakukan absensi masuk hari ini.');
            }
        } else {
            return redirect()->route('absensi.index')->with('error', 'Pegawai tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
