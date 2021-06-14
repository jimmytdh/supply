@extends('layout.app')
@section('title',$po->po_no)
@section('css')
    <link rel="stylesheet" href="{{ url('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('/plugins/daterangepicker/daterangepicker.css') }}" />
    <style>
        tr { cursor: pointer; }
    </style>
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

    <div class="content">
        <div class="container-fluid">
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
        </div>
    </div>
    <!-- Main content -->
    <div class="content">

        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h4 class="text-success font-weight-bold">Item Status</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="itemTable" class="table table-hover table-striped table-sm border">
                            <thead>
                                <tr>
                                    <th>Item No.</th>
                                    <th>Item Name</th>
                                    <th class="text-center">Unit</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">For Delivery</th>
                                    <th class="text-right">Unit Cost</th>
                                    <th class="pl-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $i)
                                <?php
                                    $code = \App\Http\Controllers\DeliveryController::unDeliveredItems($i->po_id,$i->item_id,$i->qty);
                                    $status = 'Not yet delivered';
                                    $class = 'danger';
                                    $icon = 'minus-circle';
                                    if($code == 0){
                                        $status = 'Complete Delivery';
                                        $class = 'success';
                                        $icon = 'check-circle';
                                    }else if($code > 0 && $code != $i->qty){
                                        $status = 'Partial Delivery';
                                        $class = 'info';
                                        $icon = 'exclamation-circle';
                                    }
                                ?>
                                <tr class="items" data-title="{{ $i->name }}" data-id="{{ $i->id }}">
                                    <th>{{ $i->item_no }}</th>
                                    <th>{{ $i->name }}</th>
                                    <td class="text-center">{{ $i->unit }}</td>
                                    <td class="text-center">{{ $i->qty }}</td>
                                    <td class="text-center">{{ $code }}</td>
                                    <td class="text-right">{{ number_format($i->unit_cost,2) }}</td>
                                    <td class="pl-4 text-{{ $class }}">
                                        <i class="fa fa-{{ $icon }}"></i>
                                        {!! $status !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
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
                        <table id="deliveryTable" class="table table-hover table-sm table-danger">
                            <thead class="bg-red">
                            <tr>
                                <th>Delivery No.</th>
                                <th>Item</th>
                                <th class="text-center">Unit</th>
                                <th class="text-center">Qty</th>
                                <th>Date Delivered</th>
                                <th>Remarks</th>
                                <th>Remove</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($deliveries as $del)
                            <tr>
                                <td>{{ \App\Http\Controllers\DeliveryController::generateDeliveryNo($del) }}</td>
                                <td>{{ $del->name }}</td>
                                <td class="text-center">{{ $del->unit }}</td>
                                <td class="text-center">{{ $del->qty }}</td>
                                <td>{{ date('M d, Y',strtotime($del->date_delivered)) }}</td>
                                <td>{!! nl2br($del->remarks) !!}</td>
                                <td>
                                    <a href="{{ url('/delivery/delete/'.$del->id) }}" class="deleteDelivery text-danger" onclick="return confirm('Are you sure you want to remove this record?')">
                                        <i class="fa fa-trash-alt"></i> Remove
                                    </a>
                                </td>
                            </tr>
                            @endforeach

                            @if(count($deliveries)==0)
                            <tr>
                                <td colspan="7" class="text-center p-3"> No delivery history</td>
                            </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection

@section('js')
    @include('modal.modal_itemDelivery')
    <script src="{{ url('/') }}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ url('/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ url('/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(document).ready(function () {

            {{--var table = $('#deliveryTable').DataTable({--}}
            {{--    processing: true,--}}
            {{--    serverSide: true,--}}
            {{--    ajax: "{{ route('delivery') }}",--}}
            {{--    columns: [--}}
            {{--        { data: 'item', name: 'item'},--}}
            {{--        { data: 'unit', name: 'unit'},--}}
            {{--        { data: 'qty', name: 'qty'},--}}
            {{--        { data: 'date_delivered', name: 'date_delivered'},--}}
            {{--        { data: 'remarks', name: 'remarks'},--}}
            {{--    ],--}}
            {{--    drawCallback: function (settings) {--}}

            {{--    },--}}
            {{--    columnDefs: [--}}
            {{--        { className: 'text-center' , targets: [4]},--}}
            {{--        { className: 'text-right' , targets: [5]},--}}
            {{--    ]--}}
            {{--});--}}

            //Date range picker
            $('#dateRange').daterangepicker({
                startDate: "{{ \Carbon\Carbon::now()->startOfMonth()->format('m/d/Y') }}",
                endDate: "{{ \Carbon\Carbon::now()->endOfMonth()->format('m/d/Y') }}",
            });
        });
    </script>

    <script>
        $('.items').click(function (){
            $("#modalDelivery").modal('show');
            $("#modalDelivery .modal-title").html($(this).data('title'));
            var id = $(this).data('id');
            var url = "{{ url('/delivery/item/') }}/" + id;
            setTimeout(function (){
                $(".load_content").load(url);
            },500);
        });

        $('#modalDelivery').on('hidden.bs.modal', function () {
            $(".load_content").load("{{ url('/load') }}");
        });

    </script>
@endsection
