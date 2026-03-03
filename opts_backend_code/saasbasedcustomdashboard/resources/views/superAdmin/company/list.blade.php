@extends('superAdmin.layout.adminLayout')
@section('title', 'Company List')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Company List</h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!--  <div class="col-md-2">
                                                                                                                                                </div> -->
                <!-- /.col -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header p-2">
                            Companies
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <table class="table table-bordered company-data-table">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Account Verification</th>
                                            <th>Total Emp.</th>
                                            <th width="200px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
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

    <div class="modal fade" id="viewCompanyDetails" tabindex="-1" role="dialog" aria-labelledby="viewCompanyDetailsTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCompanyDetailsTitle">Company Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script type="text/javascript">
        $(function() {
            const table = $('.company-data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.company.list.ajax') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'verified',
                        name: 'verified'
                    },
                    {
                        data: 'total_employees',
                        name: 'total_employees'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

        });


        $('#viewCompanyDetails').on('show.bs.modal', function(event) {
            const URL = "{{ route('admin.company-detail.view') }}";
            const button = $(event.relatedTarget);
            const companyId = button.data('companyid');

            const data = new URLSearchParams({
                id: companyId
            });

            fetch(`${URL}?${data}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                })
                .then(response => response.json())
                .then(response => {
                    if (response?.success) {
                        //toastr.success(response?.message);
                        $(this).find('.modal-body').html('');
                        $(this).find('.modal-body').html(response?.data?.view);
                    }

                    if (!response?.success) {
                        toastr.error(response?.message);
                    }
                })
                .catch((error) => {
                    toastr.error(error?.message);
                });

        });

        $('#viewCompanyDetails').on('hidden.bs.modal', function(e) {
            $(this).find('.modal-body').html('');
            $(this).find('.modal-body').html(
                '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>'
            );
        });

        function activateDeactivateAccount(id, status) {
            const data = {
                id: id,
                status: status
            };
            fetch("{{ route('admin.company-change-status') }}", {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(response => {
                    toastr.success(response?.message);
                    $('.company-data-table').DataTable().ajax.reload();
                })
                .catch((error) => {
                    toastr.error(error?.message);
                });
        }

        function deleteCompanyAccount(id) {
            const data = {
                id: id,
            };
            fetch("{{ route('admin.company.delete-account') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(response => {
                    toastr.success(response?.message);
                    $('.company-data-table').DataTable().ajax.reload();
                })
                .catch((error) => {
                    toastr.error(error?.message);
                });
        }
    </script>
@endpush
