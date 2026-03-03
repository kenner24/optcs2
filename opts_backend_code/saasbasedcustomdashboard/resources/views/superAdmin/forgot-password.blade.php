@section('title','Login')
@include('superAdmin.include.header')
<!DOCTYPE html>
<html lang="en">

<body class="hold-transition login-page">
    <div class="login-box">
        <!--             <div class="login-logo">
                <b>Admin</b>
            </div> -->
        <!-- /.login-logo -->
        <div class="card login_card">
            <div class="card-body login-card-body">
                <!-- <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p> -->
                <p class="login-box-msg">Forgot Password</p>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form method="post" action="{{ url('forgot-password') }}" id="forgotPassword">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <label for="email" class="error"></label>
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Send Reset Password Link</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <p class="mt-3 mb-1" style="text-align: center;">
                    <a style="text-decoration: none; font-size: 16px;" href="{{ url('/login') }}">Login</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</body>

</html>

@include('superAdmin.include.scripts')

<script type="text/javascript">
    $('#forgotPassword').validate({
        ignore: [],
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 200
            },
        },
        messages: {
            email: {
                required: 'Please enter your email'
            },
        }
    });
</script>