@extends('superAdmin.layout.adminLayout')
@section('title','Permissions')
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Permissions List</h1>
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
                    <div class="card-body">
                        <div class="tab-content">
                            <table id="example" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="10%">Sr.No</th>
                                        <th>Name</th>
                                        <th width="20%"><center>Status</center></th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Reports</td>
                                        <td class="text-center">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input permission-bt" type="checkbox" @if($permissions['reports']=='yes') checked @endif permission_name="reports">
                                            </div> 
                                        </td>
                                    </tr>
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

@stop

@section('scripts')

<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>

@stop