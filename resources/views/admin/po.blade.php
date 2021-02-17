@extends('layout.app')
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
                    <h1 class="m-0 text-dark">Purchase Order</h1>
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
                    <a href="{{ route('create.po') }}" class="btn btn-success">
                        <i class="fa fa-folder-plus"></i> Add Purchase Order
                    </a>
                    <button data-target="#print" data-toggle="modal" class="btn btn-warning">
                        <i class="fa fa-print"></i> Print Report
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>PO No.</th>
                                <th>PR # and BAC # <small class="text-muted">(if applicable)</small> </th>
                                <th>Mode of Procurement</th>
                                <th>Supplier</th>
                                <th>No. of Items</th>
                                <th>Amount</th>
                                <th>Fund Source</th>
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
                ajax: "{{ route('po') }}",
                columns: [
                    { data: 'po_no', name: 'po_no'},
                    { data: 'bac_no', name: 'bac_no'},
                    { data: 'procurement_mode', name: 'procurement_mode'},
                    { data: 'supplier', name: 'supplier'},
                    { data: 'no_items', name: 'no_items'},
                    { data: 'total_amount', name: 'total_amount'},
                    { data: 'fund_source', name: 'fund_source'},
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

            $("#dateRangeForm").submit(function (e){
                e.preventDefault();
                showLoader();
                $("#print").modal('hide');
                var formData = new FormData(this);
                var url = $(this).attr("action");
                console.log(url);
                $.ajax({
                    url: url,
                    data: formData,
                    type: "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data){
                        var win = window.open("{{ url("/po/report") }}",'Print Report',"width=600,height=800");
                        setTimeout(function (){
                            if (win) {
                                //Browser has allowed it to be opened
                                win.focus();
                            } else {
                                //Browser has blocked it
                                alert('Please allow popups for this website');
                            }
                            hideLoader();
                        },500);
                    }
                })
            })
        });
    </script>
@endsection
