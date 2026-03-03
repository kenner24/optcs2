@extends('superAdmin.layout.adminLayout')
@section('title', 'Edit Company details')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit {{ ucfirst($company->company_name) }} Details</h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!-- <div class="card-header p-2"></div> -->

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('admin.company.edit-account') }}" method="POST" enctype="multipart/form-data">
                                @csrf()
                                <input type="hidden" name="id" value="{{ $id }}">
                                <div class="form-group row">
                                    <label for="userEmail" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="text" readonly class="form-control-plaintext" name="email"
                                            id="userEmail" value="{{ $company->email }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="ownerName" class="col-sm-2 col-form-label required">Owner Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="ownerName" name="name"
                                            value="{{ $company->name }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="companyName" class="col-sm-2 col-form-label required">Company Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="companyName" name="company_name"
                                            value="{{ $company->company_name }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="companyImage" class="col-sm-2 col-form-label">Image</label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" id="companyImage" name="image">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="workEmail" class="col-sm-2 col-form-label required">Work Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" name="work_email" id="workEmail"
                                            value="{{ $company->work_email }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="totalEmp" class="col-sm-2 col-form-label required">Total Emp.</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="total_employees" id="totalEmp"
                                            value="{{ $company->total_employees }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="domainSec" class="col-sm-2 col-form-label required">Domain Sector</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="domain_sector" id="domainSec"
                                            value="{{ $company->domain_sector }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="username" class="col-sm-2 col-form-label required">Username</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="username" id="username"
                                            value="{{ $company->username }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <a class="btn btn-secondary" href="{{ route('admin.company.view') }}">Back</a>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection
@push('scripts')
@endpush
