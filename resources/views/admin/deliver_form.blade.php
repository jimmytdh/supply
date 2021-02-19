@extends('layout.app')
@section('title',$po->po_no)
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
                    <h1 class="m-0 text-dark">{{ $po->po_no }} <small class="text-primary">(Delivery Report)</small></h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-building"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Supplier</span>
                    <span class="info-box-number">{{ $supplier->company }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Contact Person</span>
                    <span class="info-box-number">{{ $supplier->name }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-phone-square"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Contact Number</span>
                    <span class="info-box-number">{{ $supplier->contact }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-map-pin"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Address</span>
                    <span class="info-box-number">{{ $supplier->address }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h4 class="text-success font-weight-bold">Item Status</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="itemTable" class="table table-bordered table-hover table-sm table-success">
                            <thead>
                            <tr>
                                <th>Item</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            <div class="card card-default">
                <div class="card-header">
                    <h4 class="text-success font-weight-bold">Actual Delivery</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="deliveryTable" class="table table-bordered table-hover table-sm table-danger">
                            <thead>
                            <tr>
                                <th>Item</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Date Delivered</th>
                                <th>Remarks</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
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

            var table = $('#deliveryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('delivery') }}",
                columns: [
                    { data: 'item', name: 'item'},
                    { data: 'unit', name: 'unit'},
                    { data: 'qty', name: 'qty'},
                    { data: 'date_delivered', name: 'date_delivered'},
                    { data: 'remarks', name: 'remarks'},
                ],
                drawCallback: function (settings) {

                },
                columnDefs: [
                    { className: 'text-center' , targets: [4]},
                    { className: 'text-right' , targets: [5]},
                ]
            });

            //Date range picker
            $('#dateRange').daterangepicker({
                startDate: "{{ \Carbon\Carbon::now()->startOfMonth()->format('m/d/Y') }}",
                endDate: "{{ \Carbon\Carbon::now()->endOfMonth()->format('m/d/Y') }}",
            });
        });
    </script>
@endsection
