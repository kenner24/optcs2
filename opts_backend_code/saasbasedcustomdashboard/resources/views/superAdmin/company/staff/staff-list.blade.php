@extends('superAdmin.layout.adminLayout')
@section('title', 'Staff List')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $companyDetails->company_name }} Staff List</h1>
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
                            Staff Members
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <table class="table table-bordered staff-data-table">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Action</th>
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

    <div class="modal fade" id="viewStaffDetails" tabindex="-1" role="dialog" aria-labelledby="viewStaffDetailsTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewStaffDetailsTitle">Staff Details</h5>
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
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {
            const id = "{{ @request()->get('id') }}";
            const table = $('.staff-data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('staff.list.ajax') }}?id=" + id,
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

        });

        $('#viewStaffDetails').on('show.bs.modal', function(event) {
            const URL = "{{ route('admin.staff.view') }}";
            const button = $(event.relatedTarget);
            const staffid = button.data('staffid');

            const data = new URLSearchParams({
                id: staffid
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

        function activateDeactivateStaffAccount(id, status) {
            const data = {
                id: id,
                status: status
            };
            fetch("{{ route('admin.staff-change-status') }}", {
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
                    $('.staff-data-table').DataTable().ajax.reload();
                })
                .catch((error) => {
                    toastr.error(error?.message);
                    $('.staff-data-table').DataTable().ajax.reload();
                });
        }

        function deleteStaffAccount(id) {
            const data = {
                id: id,
            };
            fetch("{{ route('admin.staff.delete-account') }}", {
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
                    $('.staff-data-table').DataTable().ajax.reload();
                })
                .catch((error) => {
                    toastr.error(error?.message);
                });
        }
    </script>
@endpush
