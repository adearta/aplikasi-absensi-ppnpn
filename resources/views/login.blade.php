<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
            
        <!-- menampilakn logo kejaksaaan     -->
        <div class="card p-4 shadow" style="width: 400px;">
            
            <div class="text-center mb-4">
                <img src="{{ asset('assets/image.png') }}" alt="Bootstrap" width="100" height="100">
            </div>
            <h3 class="text-center mb-4">Absensi PPNPN</h3>
            <h3 class="text-center mb-4">Kejaksaan Negeri Bangli</h3>
            <img src="{{asset('images/bangli.jpg')}}" alt="bootstrap">
            <form action="{{ route('loginauth') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" required value="{{ old('email') }}">
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</body>

</html>