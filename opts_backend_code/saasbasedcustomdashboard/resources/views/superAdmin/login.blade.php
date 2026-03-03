@section('title','Login')
@include('superAdmin.include.header')
<!DOCTYPE html>
<html lang="en">

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- <div class="login-logo">
                <b>Admin</b>
            </div> -->
        <!-- /.login-logo -->
        <div class="card login_card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in</p>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{ url('/login') }}" method="post" id="adminLogin">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <label for="email" class="error"></label>
                    @csrf()
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <label for="password" class="error"></label>
                    <div class="row">
                        <div class="col-6">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember_me">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-6" style="text-align:right;">
                            <a href="{{ url('/forgot-password') }}">I forgot my password</a>
                        </div>
                        <!-- /.col -->
                    </div>
                    <div class="row mt-3">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <div class="col-4">
                        </div>
                        <div class="col-4">
                            <!-- <a href="{{ url('/register') }}" class="btn btn-primary btn-block">Register</a> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>

@include('superAdmin.include.scripts')

<script type="text/javascript">
    $('#adminLogin').validate({
        ignore: [],
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 200
            },
            password: {
                required: true
            }
        },
        messages: {
            email: {
                required: 'Please enter your email'
            },
            password: {
                required: 'Please enter the password'
            }
        }
    });
</script>