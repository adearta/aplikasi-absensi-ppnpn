@extends('layouts.new_admin_main')
@section('content')

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        MANAJEMEN USER PPNPN KEJAKSAAN NEGERI BANGLI
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pegawai</th>
                    <th>Bidang Penempatan</th>
                    <th>E-mail</th>
                    <th>Pasword</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Nama Pegawai</th>
                    <th>Bidang Penempatan</th>
                    <th>E-mail</th>
                    <th>Pasword</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </tfoot>
            <tbody>
                @forelse ($pegawai as $pgw)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$pgw->namaPegawai}}</td>
                    <td>{{$pgw->bidangPenempatan}}</td>
                    @if($pgw->email != null)
                    <td>{{$pgw->email}}</td>
                    <td>{{$pgw->password}}</td>
                    <th>{{$pgw->role}}</th>
                    @else
                    <td>belum terdaftar</td>
                    <td>belum terdaftar</td>
                    <td>belum terdaftar</td>
                     @endif
                    <td>
                        <!-- <a href="#" class="btn btn-sm btn-outline-primary">Tambah</a> -->
                        <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="#" method="POST" class="d-inline">
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
    </div>
</div>
@endsection