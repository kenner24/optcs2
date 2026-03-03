@extends('superAdmin.layout.adminLayout')
@section('title','Activity List')
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Subscription Logs</h1>
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
                                    <th>Created</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Sam subscribed to Starter Plan $9</td>
                                    <td>1 day ago</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Mark subscribed to Pro Plan $50</td>
                                    <td>1 day ago</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>John not able to complete Pro Plus $100</td>
                                    <td>1 day ago</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Sorus subscribed to Pro  Enterprise $1200</td>
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
