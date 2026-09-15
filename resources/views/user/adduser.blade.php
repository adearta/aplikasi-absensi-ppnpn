@extends('layouts.new_admin_main')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Tambah Pegawai</h4>
        </div>
        <div class="card-body"> 
          <form action ="#" method="POST" enctype="multipart/form-data">
            @csrf
    <div class="mb-3">
  <label for="namaPegawai" class="form-label">Nama Pegawai</label>
  <input type="text" id="namaPegawai" class="form-control @error('namaPegawai') is-invalid @enderror" placeholder="{{$pegawai->namaPegawai}}" value="{{$pegawai->namaPegawai}}" name="namaPegawai">
  @error('namaPegawai')
    <div class="alert alert-danger mt-2">
      {{ $message }}
    </div>
  @enderror
</div>
<div class="mb-3">
  <label for="alamat" class="form-label">E-Mail</label>
  <input type="email" class="form-control @error('alamat') is-invalid @enderror" id="email" placeholder="masukkan alamat" value="{{ old('alamat') }}" name="email">
  @error('alamat')
    <div class="alert alert-danger mt-2">
      {{ $message }}
    </div>
  @enderror
</div>
<div class="mb-3">
  <label for="alamat" class="form-label">Password</label>
  <input type="text" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="set password user" value="{{ old('user') }}" name="user">
  @error('alamat')
    <div class="alert alert-danger mt-2">
      {{ $message }}
    </div>
  @enderror
</div>
<button type="submit" class="btn btn-md btn-primary me-3">SIMPAN</button>
</form>
        </div>
    </div>
</div>
    @endsection