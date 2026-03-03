@extends('superAdmin.layout.adminLayout')
@section('title', 'Profile')
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profile</h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                </div>
                <!-- /.col -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header p-2">
                            Profile
                        </div>
                        <div class="card-body">
                            @error('image')
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ $message }}</li>
                                    </ul>
                                </div>
                            @enderror
                            @error('name')
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ $message }}</li>
                                    </ul>
                                </div>
                            @enderror
                            <div class="tab-content">
                                <div class="tab-pane active" id="settings">
                                    <form class="form-horizontal" id="edit_profile_form" method="post"
                                        action="{{ url('/admin/profile-update') }}" enctype="multipart/form-data">
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" placeholder="Name" name="name"
                                                    value="{{ @$admin['name'] }}">
                                            </div>
                                        </div>
                                        @csrf()
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 form-label">Image</label>
                                            <div class="col-sm-10">
                                                <input type="file" class=" form-control" name="image" id="exampleInputFile">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit" class="btn btn-danger">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                </div>
                <!-- /.col -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header p-2">
                            Change Password
                        </div>
                        <div class="card-body">
                            @error('old_password')
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ $message }}</li>
                                    </ul>
                                </div>
                            @enderror
                            @error('password')
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ $message }}</li>
                                    </ul>
                                </div>
                            @enderror
                            @error('password_confirmation')
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ $message }}</li>
                                    </ul>
                                </div>
                            @enderror
                            <div class="tab-content">
                                <div class="tab-pane active" id="settings">
                                    <form class="form-horizontal" id="change_pass_form" method="post"
                                        action="{{ url('/admin/password-update') }}">
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Old Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" name="old_password"
                                                    placeholder="Old Password">
                                            </div>
                                        </div>
                                        @csrf()
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">New Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" name="password"
                                                    id="user_new_pass" placeholder="New Password">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Confirm Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" name="password_confirmation"
                                                    placeholder="Confirm Password">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit" class="btn btn-danger">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@stop

@section('scripts')

    <script type="text/javascript">
        $('#change_pass_form').validate({
            ignore: [],
            rules: {
                old_password: {
                    required: true
                },
                password: {
                    required: true
                },
                password_confirmation: {
                    required: true,
                    equalTo: '#user_new_pass'
                }
            },
            messages: {
                old_password: {
                    required: 'Please enter the old password'
                },
                password: {
                    required: 'Please enter the new password'
                },
                password_confirmation: {
                    required: 'Please enter the new password again'
                }
            }
        });
    </script>

    <script type="text/javascript">
        $('#edit_profile_form').validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                    maxlength: 200,
                    lettersonly: true
                },
            },
            messages: {
                name: {
                    required: 'Please enter the Name'
                },
            }
        });
    </script>

@stop
