@extends('layouts.new_admin_main')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>SET USER</h4>
        </div>
        <div class="card-body">
            <form action="{{route('usermanagement.store', $pegawai->pegawai_id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="namaPegawai" class="form-label">
                        <h3>{{$pegawai->namaPegawai}}</h3>
                    </label>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-Mail</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="masukkan alamat email" value="{{ old('email') }}" name="email">
                    @error('email')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="text" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="set password user" value="{{ old('password') }}" name="password">
                    @error('password')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Pilih Role</label>
                    <select class="form-select" id="role" name="role" aria-label="Default select example">
                        <option selected></option>
                        <option value="admin" @selected(old('role')=='admin' )>admin</option>
                        <option value="user" @selected(old('role')=='user' )>user</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-md btn-primary me-3">SIMPAN</button>
            </form>
        </div>
    </div>
</div>
@endsection