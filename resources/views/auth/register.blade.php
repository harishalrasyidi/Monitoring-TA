@extends('adminlte.layouts.auth')

@section('content')
<body class="hold-transition register-page">
    <div class="register-box">
      <div class="register-logo">
        <!-- <a href="{{ route('home') }}"><b>{{ config('app.name', 'Laravel') }}</b> 1.0</a> -->
      </div>

      <div class="card">
        <div class="card-body register-card-body">
          <p class="login-box-msg">
          <img src="{{ asset('assets/dist/img/polban.png') }}" alt="Polban Logo" style="width: 300px; height: auto;"/>
          </p>

          <form action="{{ route('register') }}" method="post">
            @csrf
            <div class="input-group mb-3">
              <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nama" name="name" value="{{ old('name') }}">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
              @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="input-group mb-3">
              <input type="text" class="form-control @error('nomor_induk') is-invalid @enderror" name="nomor_induk" placeholder="NIM/NIP" name="nomor_induk" value="{{ old('nomor_induk') }}">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-key"></span>
                </div>
              </div>
              @error('nomor_induk')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="input-group mb-3">
                <select type="text" class="form-control @error('role') is-invalid @enderror" name="role" placeholder="Role" name="role" value="{{ old('role') }}" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="1">Koordinator</option>
                    <option value="2">Dosen Pembimbing</option>
                    <option value="3">Mahasiswa</option>
                </select>
              @error('role')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="input-group mb-3">
              <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" name="email" value="{{ old('email') }}">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
              @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
              @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" name="password_confirmation" placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-8">
                <div class="icheck-primary">
                  <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                  <label for="agreeTerms">
                   I agree to the <a href="#">terms</a>
                  </label>
                </div>
              </div>
              <!-- /.col -->
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Register') }}</button>
              </div>
              <!-- /.col -->
            </div>
          </form>

          <!-- <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-primary">
              <i class="fab fa-facebook mr-2"></i>
              Sign up using Facebook
            </a>
            <a href="#" class="btn btn-block btn-danger">
              <i class="fab fa-google-plus mr-2"></i>
              Sign up using Google+
            </a>
          </div> -->
          @if (Route::has('login'))
          <a href="{{ route('login') }}" class="text-center">I already have a membership</a>
          @endif
        </div>
        <!-- /.form-box -->
      </div><!-- /.card -->
    </div>
    <!-- /.register-box -->
@endsection
