@extends('layouts.new_admin_main')

@section('content')
<div class="container-fluid my-4">
    <h3 class="mb-4">Rekap Absensi Bulanan Pegawai</h3>

    <!-- Form Filter Bulan dan Tahun -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.presence.rekapBulanan') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Pilih Bulan</label>
                    <select name="bulan" class="form-select">
                        @php
                        $namaBulan = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                        ];
                        @endphp
                        @foreach($namaBulan as $key => $val)
                        <option value="{{ $key }}" {{ sprintf('%02d', $bulan) == $key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Pilih Tahun</label>
                    <select name="tahun" class="form-select">
                        @for($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end gap-2" style="margin-top: 32px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Tampilkan
                    </button>
                    <a href="{{ route('admin.presence.exportBulanan', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Ekspor Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Rekap Bulanan -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Data Absensi - {{ $namaBulan[sprintf('%02d', $bulan)] }} {{ $tahun }}
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <!-- <th>Tanggal</th> -->
                            <th>Nama Pegawai</th>
                            <th>Bidang Penempatan</th>
                            <th>Jumlah Hari Masuk</th>
                            <th>Jumlah Tidak Absen</th>
                            <!-- <th>Status</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pegawai as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <!-- <td>{{ $row->tanggal_absensi ? \Carbon\Carbon::parse($row->tanggal_absensi)->format('d/m/Y') : '-' }}</td> -->
                            <td>{{ $row->namaPegawai }}</td>
                            <td>{{ $row->bidangPenempatan }}</td>
                            <td>{{ $row->jumlah_masuk ?? '0' }}</td>
                            <td>{{ $row->jumlah_tidak_absen ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data absensi untuk bulan dan tahun ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection