<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>AMSystem - Login</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body class="text-dark">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block text-center">
                                <div class="bg-login-image mt-3" 
                                    style="background-image: url('{{ asset('admin_assets/img/2.png') }}'); 
                                            background-size: 50%; 
                                            background-repeat: no-repeat; 
                                            background-position: center; 
                                            height: 300px;">
                                </div>
                                <h5>Mother Shepherd Academy of Valenzuela</h5>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if (session('status'))
                                        <div class="alert alert-success" style="font-size: 12px;">
                                            {{ session('status') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                    <form action="{{ route('login.action') }}" method="POST" class="user">
                                        @csrf
                                        <div class="form-group">
                                            <input name="email" type="email" class="form-control form-control-user rounded"
                                                id="exampleInputEmail" aria-describedby="emailHelp" value="{{ old('email') }}"
                                                placeholder="Enter Email Address...">
                                            @error('email')
                                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input name="password" type="password" class="form-control form-control-user rounded"
                                                id="loginPassword" placeholder="Password">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" onclick="togglePasswordVisibility('loginPassword')">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            @error('password')
                                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                            @enderror
                                            @if (session('error'))
                                            <span class="text-danger" style="font-size: 13px;">{{ session('error') }}</span>
                                            @endif
                                            @if(session('cooldown'))
                                                <div class="alert alert-warning mt-2 text-center" style="font-size: 13px;">
                                                    Too many login attempts. Please wait <span id="cooldown-timer">{{ session('cooldown') }}</span> seconds.
                                                </div>
                                                <script>
                                                    let timeLeft = {{ session('cooldown') }};
                                                    const timerElement = document.getElementById('cooldown-timer');
                                                    const loginBtn = document.getElementById('loginBtn');

                                                    if (loginBtn) {
                                                        loginBtn.disabled = true;
                                                    }

                                                    const countdown = setInterval(() => {
                                                        timeLeft--;
                                                        if (timeLeft <= 0) {
                                                            clearInterval(countdown);
                                                            if (loginBtn) {
                                                                loginBtn.disabled = false;
                                                            }
                                                            location.reload();
                                                        } else {
                                                            timerElement.textContent = timeLeft;
                                                        }
                                                    }, 1000);
                                                </script>
                                            @endif
                                        </div>
                                        <div class="d-flex align-item-center justify-content-between my-4">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember Me</label>
                                            </div>
                                            <a href="{{ route('auth.forgot-password') }}">Forgot password?</a>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block btn-user rounded" id="loginBtn" {{ session('cooldown') ? 'disabled' : '' }}>Login</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('codes-js/login.js') }}"></script>
</body>

</html>