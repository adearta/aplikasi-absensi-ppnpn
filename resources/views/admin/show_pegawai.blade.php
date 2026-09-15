@extends('layouts.new_admin_main')
@section('content')

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        DAFTAR PEGAWAI PPNPN KEJAKSAAN NEGERI BANGLI
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pegawai</th>
                    <th>Bidang Penempatan</th>
                    <th>Usia</th>
                    <th>Lama Bekerja</th>
                    <th>Gaji</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Nama Pegawai</th>
                    <th>Bidang Penempatan</th>
                    <th>Usia</th>
                    <th>Lama Bekerja</th>
                    <th>Gaji</th>
                    <th>Aksi</th>
                </tr>
            </tfoot>
            <tbody>
                @forelse ($pegawais as $pegawai)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$pegawai->namaPegawai}}</td>
                    <td>{{$pegawai->bidangPenempatan}}</td>
                    <td>{{$pegawai->usia}} Tahun</td>
                    <td>{{$pegawai->lamaBekerja}} Tahun</td>
                    <td>Rp. {{ number_format($pegawai->gaji, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('admin.edit', $pegawai->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.destroy', $pegawai->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <div class="alert alert-danger">
                    Data Pegawai belum Tersedia.
                </div>
                @endforelse
            </tbody>
        </table>
        {{ $pegawais->links() }}
    </div>
    <div class="card-footer">
        <a type="button" class="btn btn-primary" href="{{ route('admin.create') }}">Tambah Pegawai</a>
    </div>
</div>
@endsection