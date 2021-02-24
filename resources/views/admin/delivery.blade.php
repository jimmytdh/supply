@extends('layout.app')
@section('title','Deliveries')
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
                    <h1 class="m-0 text-dark">Deliveries</h1>
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
                    <form action="{{ route('search.po') }}" method="post">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="input-group">
                                {{ csrf_field() }}
                                <input type="text" class="form-control" name="po_no" placeholder="Enter PO #" required>
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
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-striped table-sm table-hover">
                            <thead>
                            <tr>
                                <th>PO No.</th>
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

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('delivery') }}",
                columns: [
                    { data: 'po_no', name: 'po_no'},
                    { data: 'item', name: 'item'},
                    { data: 'unit', name: 'unit'},
                    { data: 'qty', name: 'qty'},
                    { data: 'date_delivered', name: 'date_delivered'},
                    { data: 'remarks', name: 'remarks'},
                ],
                drawCallback: function (settings) {

                },
                columnDefs: [
                    { className: 'text-center' , targets: [2,3,4]},
                    { className: 'text-right' , targets: []},
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
