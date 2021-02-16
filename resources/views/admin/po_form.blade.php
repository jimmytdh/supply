@extends('layout.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-editable/css/bootstrap-editable.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-editable/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ url('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <style>
        .editable, span {
            cursor: pointer;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Purchase Order Form</h1>
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
                    <a href="{{ route('po') }}" class="btn btn-warning">
                        <i class="fa fa-arrow-circle-left"></i> Back to List
                    </a>
                    <button class="btn btn-primary btn-add">
                        <i class="fa fa-plus-circle"></i> Add Item
                    </button>
                    <button class="btn btn-danger deletePO">
                        <i class="fa fa-minus-circle"></i> Delete Purchase Order
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td>Supplier</td>
                                <td><span id="supplier_id" data-type="select" data-value="{{ $supplier->id }}" data-title="Supplier">{{ $supplier->company }}</span></td>
                                <td>P.O. No.</td>
                                <td><span class="edit" data-placement="left" id="po_no" data-title="P.O. No.">{{ $po->po_no }}</span></td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td><span id="address">{{ $supplier->address }}</span></td>
                                <td>Date:</td>
                                <td><span class="date" data-placement="left" id="po_date" data-value="{{ date('M d, Y',strtotime($po->po_date)) }}" data-type="date" data-title="Date">{{ date('M d, Y',strtotime($po->po_date)) }}</span></td>
                            </tr>
                            <tr>
                                <td>TIN:</td>
                                <td><span id="tin">{{ $supplier->tin }}</span></td>
                                <td>Mode of Procurement:</td>
                                <td><span class="edit" id="procurement_mode" data-title="Mode of Procurement">{{ $po->procurement_mode }}</span></td>
                            </tr>
                            <tr>
                                <td>Tel No./Fax:</td>
                                <td><span id="contact">{{ $supplier->contact }}</span></td>
                                <td>PR # and BAC #<br><small class="text-muted"><em>(if applicable)</em></small></td>
                                <td><span class="edit" id="bac_no" data-title="PR # and BAC # (if applicable)">{{ $po->bac_no }}</span></td>
                            </tr>
                        </table>
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td>Delivery Term:</td>
                                <td><span class="edit" data-type="textarea" id="delivery_term" data-title="Delivery Term">{{ $po->delivery_term }}</span></td>
                                <td>Date of Delivery:</td>
                                <td><span class="date" id="delivery_date" data-value="{{ date('M d, Y',strtotime($po->delivery_date)) }}" data-type="date" data-title="Delivery Date">{{ ($po->delivery_date) ? date('M d, Y',strtotime($po->delivery_date)): null }}</span></td>
                                <td>Payment Term:</td>
                                <td><span class="edit" id="payment_term" data-title="Payment Term">{{ $po->payment_term }}</span></td>
                            </tr>
                        </table>

                        <table id="dataTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item No.</th>
                                    <th>Unit</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Unit Cost</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-right">
                        <h4>Total Amount: <span class="text-success" id="total_amount" data-title="Total Amount">{{ number_format($po->total_amount) }}</span></h4>
                    </div>
                    <h4>
                        Fund Source: <span class="edit text-success" id="fund_source" data-title="Fund Source">{{ $po->fund_source }}</span>
                    </h4>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection

@section('js')
    <script src="{{ asset('/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-editable/js/bootstrap-editable.js') }}"></script>
    <script src="{{ url('/') }}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('items.po', $po->id) }}",
                columns: [
                    { data: 'item_no', name: 'item_no'},
                    { data: 'unit', name: 'unit'},
                    { data: 'description', name: 'description'},
                    { data: 'qty', name: 'qty'},
                    { data: 'unit_cost', name: 'unit_cost'},
                    { data: 'amount', name: 'amount'},
                    { data: 'action', name: 'action'},
                ],
                paging: false,
                searching: false,
                ordering: false,
                drawCallback: function (settings) {
                    makeEditable();
                },
                columnDefs: [
                    { className: 'text-center' , targets: [0,1,3,6]},
                    { className: 'text-right' , targets: [4,5]},
                ]
            });

            $('#dataTable tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            } );

        });

        var url = "{{ route('update.po') }}";
        var id = "{{ $po->id }}";
        $('.edit').editable({
            url: url,
            pk: id,
            type: 'text',
            success: function(data) {
                console.log(data);
            }
        });

        $(".deletePO").click(function (){
            if(confirm("Are you sure you want to delete this Purchase Order?"))
            {
                showLoader();
                $.ajax({
                    url: "{{ route('delete.po') }}",
                    data: { id: "{{ $po->id }}" },
                    type: "post",
                    success: function (data){
                        window.local.href("{{ url('/po') }}");
                    }
                })
            }
        });

        function makeEditable(){
            $('.editItem').editable({
                url: "{{ route('update.item') }}",
                type: 'text',
                success: function(data) {
                    updateDataTable();
                }
            });
            $('.editPurchaseItem').editable({
                url: "{{ route('update.purchaseItem') }}",
                type: 'text',
                success: function(data) {
                    updateDataTable();
                }
            });
            $('.selectPurchaseItem').editable({
                url: "{{ route('update.purchaseItem') }}",
                source: [
                    @foreach($units as $u)
                        {value: "{{ $u->code }}", text: "{{ $u->code }}"},
                    @endforeach
                ],
                success: function(data) {
                    updateDataTable();
                }
            });

            $(".deleteItem").click(function(){
                var id = $(this).data('id');
                if(confirm("Are you sure you want to remove this item?"))
                {
                    $.ajax({
                        url: "{{ route('delete.item') }}",
                        data: { id: id },
                        type: "post",
                        success: function (data){
                            var oTable = $('#dataTable').dataTable();
                            oTable.fnDraw(false);
                            console.log(data);
                        }
                    });
                }
            });
        }

        $(".btn-add").click(function (){
            var url = "{{ route('default.item',$po->id) }}";
            $.ajax({
                type: 'GET',
                url: url,
                success: function(data){
                    var oTable = $('#dataTable').dataTable();
                    oTable.fnDraw(false);
                }
            })
        });



        function updateDataTable()
        {
            var oTable = $('#dataTable').dataTable();
            oTable.fnDraw(false);

            $.ajax({
                type: 'GET',
                url: "{{ route('total.purchaseItem',$po->id) }}",
                success: function(data){
                    $("#total_amount").html(data);
                }
            });
        }


        $('#supplier_id').editable({
            url: url,
            pk: id,
            source: [
                @foreach($suppliers as $sup)
                {value: "{{ $sup->id }}", text: "{{ $sup->company }}"},
                @endforeach
            ],
            success: function(data) {
                $("#address").html(data.address);
                $("#tin").html(data.tin);
                $("#contact").html(data.contact);
            }
        });

        $('.date').editable({
            url: url,
            pk: id,
            format: 'yyyy-mm-dd',
            viewformat: 'M dd, yyyy',
            datepicker: {
                weekStart: 0
            }
        });
    </script>
@endsection
