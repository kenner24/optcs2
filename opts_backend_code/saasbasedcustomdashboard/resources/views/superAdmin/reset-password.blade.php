@section('title','Login')
@include('superAdmin.include.header')
<!DOCTYPE html>
<html lang="en">

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card login_card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Reset Password</p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="post" action="{{ url('reset-password') }}" id="resetPassword">
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" id="password" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-key"></span>
                            </div>
                        </div>
                    </div>
                    <label for="password" class="error"></label>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-key"></span>
                            </div>
                        </div>
                    </div>
                    <label for="password_confirmation" class="error"></label>
                    <input type="hidden" name="pass_token" value="{{ $token }}">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Change password</button>
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
    $('#resetPassword').validate({
        ignore: [],
        rules: {
            password: {
                required: true,
                maxlength: 200
            },
            confirm_password: {
                required: true,
                equalTo: '#password',
                maxlength: 200
            },
        },
        messages: {
            password: {
                required: 'Please enter password'
            },
        }
    });
</script>