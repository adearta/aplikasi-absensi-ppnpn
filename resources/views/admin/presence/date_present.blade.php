@extends('layouts.new_admin_main')

@section('content')
<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Data Absensi Pegawai - Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h3>
        <a href="{{ url('/admin/kalender') }}" class="btn btn-secondary">Kembali ke Kalender</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="col-md-4 d-flex align-items-end gap-2" style="margin-top: 32px;">
                <a type="button" class="btn btn-success" href="{{ route('admin.excel') }}"> <i class="fas fa-file-excel"></i> Ekspor Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pegawai</th>
                            <th>Bidang Penempatan</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensis as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->namaPegawai }}</td>
                            <td>{{ $row->bidangPenempatan }}</td>
                            <td>{{ $row->jam_masuk ?? '-' }}</td>
                            <td>{{ $row->jam_pulang ?? '-' }}</td>
                            <td>
                                @if($row->jam_masuk)
                                <span class="badge bg-success">Absen</span>
                                @else
                                <span class="badge bg-danger">data absensi belum tersedia</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <th>1.</th>
                            <th>B</th>
                            <th>Bidang Penempatan</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- <div class="row card-footer">
                <div class="col-1 d-flex justify-content-center">
                    <a type="button" class="btn btn-success" href="{{ route('admin.excel') }}">Export data ke Excel</a>
                </div>
            </div> -->
        </div>
    </div>
</div>
@endsection