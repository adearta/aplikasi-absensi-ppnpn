@extends('layouts.new_admin_main')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Edit Pegawai</h4>
        </div>
        <div class="card-body"> 
          <form action ="{{ route('admin.update', $pegawai->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
    <div class="mb-3">
  <label for="nik" class="form-label">NIK</label>
  <input type="text" id="nik" class="form-control @error('nik') is-invalid @enderror" placeholder="{{$pegawai->nik}}" value="{{$pegawai->nik}}" name="nik">
  @error('nik')
    <div class="alert alert-danger mt-2">
      {{ $message }}
    </div>
  @enderror
</div>
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
    <label for="birthDate" class="form-label">Tanggal Lahir</label>
    <input type="date" class="form-control @error('tanggalLahir') is-invalid @enderror" id="tanggalLahir" name="tanggalLahir" placeholder="{{$pegawai->tanggalLahir}}" value="{{$pegawai->tanggalLahir}}">
  </div>
<div class="mb-3">
  <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>
  <select class="form-select" id="jenisKelamin" name="jenisKelamin" aria-label="Default select example">
    <option selected>{{$pegawai->jenisKelamin}}</option>
    <option value="Laki-Laki" @selected(old('jenisKelamin') == 'Laki-Laki')>Laki-Laki</option>
    <option value="Perempuan" @selected(old('jenisKelamin') == 'Perempuan')>Perempuan</option>
  </select>
</div>
<div class="mb-3">
  <label for="usia" class="form-label">Usia</label>
  <input type="number" class="form-control @error('usia') is-invalid @enderror" id="usia" placeholder="{{$pegawai->usia}}" value="{{$pegawai->usia}}" name="usia">
  @error('usia')
    <div class="alert alert-danger mt-2">
      {{ $message }}
    </div>
  @enderror
</div>
<div class="mb-3">
  <label for="alamat" class="form-label">Alamat</label>
  <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" placeholder="{{$pegawai->alamat}}" value="{{$pegawai->alamat}}" name="alamat">
  @error('alamat')
    <div class="alert alert-danger mt-2">
      {{ $message }}
    </div>
  @enderror
</div>
<div class="mb-3">
  <label for="agama" class="form-label">Agama</label>
  <select class="form-select" id="agama" name="agama" aria-label="Default select example">
    <option selected>{{$pegawai->agama}}</option>
  <option value="islam" @selected(old('agama') == 'islam')>Islam</option>
  <option value="hindu" @selected(old('agama') == 'hindu')>Hindu</option>
  <option value="protestan" @selected(old('agama') == 'protestan')>Protestan</option>
  <option value="katolik" @selected(old('agama') == 'katolik')>Katolik</option>
  <option value="budha" @selected(old('agama') == 'budha')>Budha</option>
</select>
</div>
<div class="mb-3">
  <label for="statusPernikahan" class="form-label">Status Pernikahan</label>
  <select class="form-select" id="statusPernikahan" name="statusPernikahan" aria-label="Default select example">
  <option selected>{{$pegawai->statusPernikahan}}</option>
  <option value="Menikah" @selected(old('statusPernikahan') == 'Menikah')>Menikah</option>
  <option value="Lajang" @selected(old('statusPernikahan') == 'Lajang')>Lajang</option>
  <option value="Bercerai" @selected(old('statusPernikahan') == 'Bercerai')>Bercerai</option>
</select>
</div>
<div class="mb-3">
<label for="kewarganegaraan" class="form-label">Kewarganegaraan</label>
<select class="form-select" id="kewarganegaraan" name="kewarganegaraan" aria-label="Default select example">
  <option selected>{{$pegawai->kewarganegaraan}}</option>
  <option value="WNI" @selected(old('kewarganegaraan') == 'WNI')>WNI</option>
  <option value="WNA" @selected(old('kewarganegaraan') == 'WNA')>WNA</option>
</select>
</div>
<div class="mb-3">
<label for="bidangPenempatan" class="form-label">Bidang Penempatan</label>
<select class="form-select" id="bidangPenempatan" name="bidangPenempatan" aria-label="Default select example">
  <option selected>{{$pegawai->bidangPenempatan}}</option>
  <option value="PEMBINAAN" @selected(old('bidangPenempatan') == 'PEMBINAAN')>PEMBINAAN</option>
  <option value="PIDSUS" @selected(old('bidangPenempatan') == 'PIDSUS')>PIDSUS</option>
  <option value="PIDUM" @selected(old('bidangPenempatan') == 'PIDUM')>PIDUM</option>
  <option value="DATUN" @selected(old('bidangPenempatan') == 'DATUN')>DATUN</option>
  <option value="INTEL" @selected(old('bidangPenempatan') == 'INTEL')>INTEL</option>
  <option value="PAPB" @selected(old('bidangPenempatan') == 'PAPB')>PAPB</option>
</select>
</div>
<div class="mb-3">
  <label for="lamaBekerja" class="form-label">Lama Kerja</label>
  <input type="number" class="form-control @error('lamaBekerja') is-invalid @enderror" id="lamaBekerja" placeholder="{{$pegawai->lamaBekerja}}" value="{{$pegawai->lamaBekerja}}" name="lamaBekerja">
  @error('lamaBekerja')
    <div class="alert alert-danger mt-2">
      {{ $message }}
    </div>
  @enderror
</div>
<div class="mb-3">
  <label for="gaji" class="form-label">Gaji</label>
  <input type="number" class="form-control @error('gaji') is-invalid @enderror" id="gaji" placeholder="{{$pegawai->gaji}}" value="{{$pegawai->gaji}}" name="gaji">
  @error('gaji')
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