@extends('superAdmin.layout.adminLayout')
@section('title', 'Company Reports')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $companyDetails->company_name }} Reports</h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills nav-fill" id="company-reports-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="sales-tab" data-bs-toggle="tab"
                                        data-bs-target="#sales" type="button" role="tab" aria-controls="sales"
                                        aria-selected="true">Sales</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="marketing-tab" data-bs-toggle="tab"
                                        data-bs-target="#marketing" type="button" role="tab" aria-controls="marketing"
                                        aria-selected="true">Marketing</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="financial-tab" data-bs-toggle="tab"
                                        data-bs-target="#financial" type="button" role="tab" aria-controls="financial"
                                        aria-selected="true">Financial</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="operations-tab" data-bs-toggle="tab"
                                        data-bs-target="#operations" type="button" role="tab"
                                        aria-controls="operations" aria-selected="true">Operations</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="sales" role="tabpanel"
                                    aria-labelledby="sales-tab">
                                    @include('superAdmin.company.reports.sales-report')
                                </div>
                                <div class="tab-pane fade" id="marketing" role="tabpanel" aria-labelledby="marketing-tab">
                                    @include('superAdmin.company.reports.marketing-report')
                                </div>
                                <div class="tab-pane fade" id="financial" role="tabpanel" aria-labelledby="financial-tab">
                                    @include('superAdmin.company.reports.financial-report')
                                </div>
                                <div class="tab-pane fade" id="operations" role="tabpanel" aria-labelledby="operations-tab">
                                    @include('superAdmin.company.reports.operations-report')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@stop

@push('scripts')
    <script>
        const companyId = "{{ @request()->get('id') }}";
        const appUrl = "{{ @config('app.url') }}";
    </script>
    <script type="text/javascript" src="{{ asset('/admin/js/reports/company-reports.js') }}"></script>
@endpush
