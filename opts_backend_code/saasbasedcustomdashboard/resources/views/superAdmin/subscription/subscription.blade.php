@extends('superAdmin.layout.adminLayout')
@section('title','Activity List')
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Subscription</h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Plan Name</th>
                                    <th>Plan Price</th>
                                    <th>Plan Type</th>
                                    <th>Subscribed By</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Starter</td>
                                    <td>$9</td>
                                    <td>Monthly</td>
                                    <td>Sam</td>
                                    <td><span class="badge badge-success">Paid</span></td>
                                    <td>1 day ago</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Pro</td>
                                    <td>$50</td>
                                    <td>Monthly</td>
                                    <td>Mark</td>
                                    <td><span class="badge badge-success">Paid</span></td>
                                    <td>1 day ago</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Pro Plus</td>
                                    <td>$100</td>
                                    <td>Yearly</td>
                                    <td>John</td>
                                    <td><span class="badge badge-warning">Due</span></td>
                                    <td>1 day ago</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Enterprise</td>
                                    <td>$1200</td>
                                    <td>Yearly</td>
                                    <td>Sorus</td>
                                    <td><span class="badge badge-success">Paid</span></td>
                                    <td>1 day ago</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
