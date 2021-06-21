@extends('layout.app')
@section('title','Inspection and Acceptance')
@section('css')
    <link rel="stylesheet" href="{{ url('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('/plugins/daterangepicker/daterangepicker.css') }}" />

@endsection
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Inspection and Acceptance</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <form action="{{ url('/inspection/search') }}" method="post">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="input-group">
                                {{ csrf_field() }}
                                <input type="text" class="form-control" value="{{ $po_no }}" name="po_no" placeholder="Enter PO #" required>
                                <span class="input-group-append">
                                    <button type="submit" class="btn btn-info">
                                        <i class="fa fa-search"></i> Search PO #
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @if(count($data) > 0)
                    <div class="pt-3 pl-3 pr-3" style="background: #dadcde">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-building"></i></span>
                                        <?php
                                            $supplier = \App\Http\Controllers\SupplierController::getSupplierInfo($po->supplier_id);
                                        ?>
                                        @if(!$supplier)
                                        <div class="info-box-content">
                                            <span class="info-box-text">Supplier</span>
                                            <span class="info-box-number">--None--</span>
                                        </div>
                                        @else
                                            <div class="info-box-content">
                                                <span class="info-box-text">Supplier</span>
                                                <span class="info-box-number">{{$supplier->company}}<br>
                                                    <font class="text-success">{{$supplier->name}}, {{$supplier->contact}}</font>
                                                </span>
                                            </div>
                                        @endif
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <!-- /.col -->
                                <div class="col-12 col-sm-6 col-md-6">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user"></i></span>
                                        <?php $endUser = \App\Http\Controllers\EndUserController::getEndUserInfo($po->end_user); ?>
                                        @if($endUser)
                                        <div class="info-box-content">
                                            <span class="info-box-text">End-User</span>
                                            <span class="info-box-number">{{ $endUser->fname." ".$endUser->lname }}<br>
                                                <font class="text-success">{{$endUser->description}}</font>
                                            </span>
                                        </div>
                                        @else
                                            <div class="info-box-content">
                                                <span class="info-box-text">End User</span>
                                                <span class="info-box-number">--None--</span>
                                            </div>
                                        @endif
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-striped table-sm table-hover">
                            <thead style="background: #dadcde">
                            <tr>
                                <th>Delivery No.</th>
                                <th>Item</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Date Delivered</th>
                                <th>Inspector(s)</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $del)
                                <tr>
                                    <td>{{ \App\Http\Controllers\DeliveryController::generateDeliveryNo($del) }}</td>
                                    <td>{{ $del->name }}</td>
                                    <td>{{ $del->unit }}</td>
                                    <td>{{ $del->qty }}</td>
                                    <td>{{ date('M d, Y',strtotime($del->date_delivered)) }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i> Please input valid PO #.
                        </div>
                    @endif
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection

@section('js')
    @include('modal.modal_PO')
    <script src="{{ url('/') }}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ url('/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ url('/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(document).ready(function () {


        });
    </script>
@endsection
