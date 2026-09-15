@extends('layouts.main')
@section('content')
<div class="card mb-4">
    <div class="card-header">
        @if ($pegawai)
        <h3 class="text-center mb-4">DAFTAR ABSENSI {{ $pegawai->first()->namaPegawai }}</h3>
        @else
        <h3 class="text-center mb-4">DATA PEGAWAI KOSONG</h3>
        @endif
        <i class="fas fa-table me-1"></i>

    </div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>lokasi Absen</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Tanggal</th>
                    <th>status</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>No.</th>
                    <th>lokasi Absen</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Tanggal</th>
                    <th>status</th>
                </tr>
            </tfoot>
            <tbody>
                @forelse ($absensi as $absen)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>kantor</td>
                    <td>{{$absen->jam_masuk}}</td>
                    <td>{{$absen->jam_keluar}}</td>
                    <td>{{$absen->tanggal}}</td>
                    <td>{{$absen->status}}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Data absensi belum tersedia.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection