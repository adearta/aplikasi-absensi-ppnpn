<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class ProfileController extends Controller
{
    public function storeAbsen(Request $request)
    {

        $pegawaiID = Auth::user()->pegawai_id; // Ambil ID pegawai dari user yang sedang login
        $today = now();

        //cek apakah hari ini hari libur atau tidak
        // Cek weekend
        $isWeekend = $today->isWeekend();
        //cek libur nasional
        $tahun = $today->year;
        $liburList = cache()->remember("libur_nasional_{$tahun}", 86400, function () use ($tahun) {
            $response = Http::get("https://dayoffapi.vercel.app/api?year={$tahun}");
            return $response->ok() ? $response->json() : [];
        });

        $isHariLiburNasional = collect($liburList)->contains('tanggal', $today->format('Y-m-d'));
        $isLibur = $isWeekend || $isHariLiburNasional;

        if ($isLibur) {
                return redirect()->route('absensi.index')->with('error', 'Hari Libur');
        } else {
            $pegawai = Auth::user()->pegawai;
            if ($pegawaiID) {
                // Simpan data absen ke database
                $absen = new \App\Models\Absensi();
                $absen->namaPegawai = $pegawai ? $pegawai->namaPegawai : null; // Ambil nama pegawai dari user yang sedang login
                $absen->pegawai_id = $pegawaiID;
                $absen->status = 'Absen';
                $absen->jam_masuk = now('Asia/Makassar')->format('H:i:s'); // Set waktu absen masuk
                $absen->tanggal = now('Asia/Makassar'); // Gunakan tanggal saat ini
                $absen->save();

                // Mengambil data pegawai langsung lewat relasi
                $pegawai = $absen->fresh()->pegawai;

                return redirect()->route('absensi.index')
                    ->with('pegawai', $pegawai)
                    ->with('success', 'Anda sudah melakukan absensi hari ini.');
            } else {
                return redirect()->route('absensi.index')->with('error', 'Pegawai tidak ditemukan.');
            }
        }
    }
}
