@extends('layouts.new_admin_main')
@section('content')


<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        DAFTAR ABSENSI PEGAWAI PPNPN KEJAKSAAN NEGERI BANGLI HARI INI
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 d-flex align-items-end gap-2" style="margin-top: 32px;">
                <a type="button" class="btn btn-primary" href="{{ route('admin.index') }}">Kalender</a>
                    <a type="button" class="btn btn-success" href="{{ route('admin.excel') }}"> <i class="fas fa-file-excel"></i> Ekspor Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pegawai</th>
                            <th>Bidang Penempatan</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pegawai</th>
                            <th>Bidang Penempatan</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($presensi as $presen)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$presen->namaPegawai}}</td>
                            <td>{{$presen->bidangPenempatan}}</td>
                            <td>{{$presen->jam_masuk}}</td>
                            <td>{{$presen->jam_keluar}}</td>
                        </tr>
                        @empty
                        <div class="alert alert-danger">
                            Data Pegawai belum Tersedia.
                        </div>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endsection