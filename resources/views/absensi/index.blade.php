@extends('layouts.main')
@section('content')
<div>
    <div class="card">
        <div class="card">
            @if (session('pegawai'))
            <h3 class="text-center mb-4">
                SELAMAT DATANG USER {{ session('pegawai')->namaPegawai }}
            </h3>
            @else
            <h3 class="text-center mb-4">SAMPAI JUMPA ESOK HARI</h3>
            @endif
            {{-- 1. TAMBAHKAN DI SINI: ALERT ERROR / WARNING --}}
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Peringatan!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- 2. TAMBAHKAN DI SINI: ALERT SUCCESS --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="card">
                <div class="row">
                    <div class="col-6 d-flex justify-content-center">
                        <!-- <div class="container mt-4 "> -->
                        <div class="alert alert-secondary d-flex justify-content-center" role="alert">
                            Waktu Saat Ini: <span id="live-time" class="fw-bold ms-1 me-1"> </span><span>WITA</span>
                            <!-- </div> -->
                        </div>
                    </div>
                    <!-- <div class="col d-flex justify-content-center"> -->
                    @if ($sudahAbsen)
                    <div class="col-12 d-flex justify-content-center">
                        <div class="alert alert-success" role="alert">
                            Anda sudah melakukan absensi hari ini.
                        </div>
                    </div>
                    @else
                    <div class="col-6 d-flex justify-content-center">
                        <form action="{{ route('absensi.store') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success" id="Absen" value="Absen">Absen Masuk</button>
                        </form>
                    </div>
                    @endif
                </div>
                <!-- <div class="col-6 d-flex justify-content-center"> -->
                @if ($sudahAbsen && !$sudahPulang)
                <div class="col-6 d-flex justify-content-center">
                    <form action="{{ route('absensi.pulang') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Absen Keluar</button>
                    </form>
                </div>
                @elseif ($sudahPulang)
                <div class="col-6 d-flex justify-content-center">
                    <div class="alert alert-success" role="alert">
                        Anda sudah melakukan Absensi Pulang hari ini.
                    </div>
                </div>
                @else
                <!-- <div class="col-6 d-flex justify-content-center"> -->
                <form action="{{ route('absensi.pulang') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Absen Keluar</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
@endsection