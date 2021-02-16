@extends('layout.app')
@section('title','Mange Suppliers')

@section('css')
    <link rel="stylesheet" href="{{ url('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <style>
        span.edit {
            cursor: pointer;
            border-bottom: 1px dashed #b92727;
        }
    </style>
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Manage Suppliers</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Add Supplier</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" id="supplierForm">
                            <input type="hidden" name="_method" id="method">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Company</label>
                                    <input type="text" autocomplete="off" class="form-control" name="company" placeholder="Company Name" required>
                                </div>
                                <div class="form-group">
                                    <label>Contact Person</label>
                                    <input type="text" autocomplete="off" class="form-control" name="name" placeholder="Contact Person" required>
                                </div>
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" autocomplete="off" class="form-control" name="contact" placeholder="Contact Number" required>
                                </div>
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="text" class="form-control" name="email" placeholder="Enter email">
                                </div>
                                <div class="form-group">
                                    <label>TIN</label>
                                    <input type="text" class="form-control" name="tin" placeholder="Enter TIN">
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea name="address" id="address" rows="3" placeholder="Enter Address" class="form-control" style="resize: none;"></textarea>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer save-footer">
                                <button type="submit" name="submit" value="POST" class="btn btn-primary btn-block">
                                    <i class="fas fa-check"></i> Submit
                                </button>
                            </div>
                            <div class="card-footer edit-footer" style="display: none;">
                                <button type="submit" name="submit" value="PATCH" class="btn btn-success btn-block">
                                    <i class="fas fa-check"></i> Update
                                </button>
                                <button type="submit" name="submit" value="DELETE" onclick="deleteSupplier()" class="btn btn-danger btn-block">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-9 col-sm-12">
                    <div class="card card-success">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-bordered table-hover">
                                    <thead class="bg-gradient-blue">
                                        <tr>
                                            <th>ID #</th>
                                            <th>Company</th>
                                            <th>Contact Person</th>
                                            <th>Contact Number</th>
                                            <th>Email</th>
                                            <th>TIN</th>
                                            <th>Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection

@section('js')
    <script src="{{ url('/') }}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ url('/') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('supplier') }}",
                columns: [
                    { data: 'id', name: 'id'},
                    { data: 'company', name: 'company'},
                    { data: 'name', name: 'name'},
                    { data: 'contact', name: 'contact'},
                    { data: 'email', name: 'email'},
                    { data: 'tin', name: 'tin'},
                    { data: 'address', name: 'address'},
                ],
            });

        })
        var id = "0";
        var url = "{{ route('add.supplier') }}";
        var enterDisabled = true;

        $("button[name=submit]").click(function(){
            method = $(this).val();
        });

        $(window).keydown(function(event){
            if(enterDisabled && event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

        $("#supplierForm").submit(function(e){
            e.preventDefault();
            showLoader();
            var formData = new FormData(this);
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    var oTable = $('#dataTable').dataTable();
                    oTable.fnDraw(false);
                    resetForm();
                    if(data !== 'duplicate'){
                        notify('success',data);
                    }else{
                        notify('error','Duplicate entry! Please use different company name.')
                    }
                    setTimeout(function(){
                        hideLoader();
                        console.log(data);
                    },500);
                },
                error: function(data){
                    console.log(data);
                }
            });
        });

        function editSupplier(supplier_id)
        {
            id = supplier_id;
            url = "{{ route('supplier') }}/"+id;
            $("#method").val("PUT");
            $(".save-footer").css('display','none');
            $(".edit-footer").css('display','block');
            $(".card-title").html('Update Supplier');
            $.ajax({
                type: "GET",
                url: "{{ route('supplier') }}/"+id,
                success: (data) => {
                    putData(data);
                }
            })
        }

        function resetForm()
        {
            $("#supplierForm").trigger('reset');
            $(".save-footer").css('display','block');
            $(".edit-footer").css('display','none');
            $("input:text:visible:first").focus();
            $("#method").val("POST");
            $(".card-title").html('Add Supplier');
            url = "{{ route('supplier') }}";
        }

        function deleteSupplier()
        {
            hideLoader();
            if(confirm("Are you sure you want to delete this supplier?")){
                $("#method").val("DELETE");
            }
        }

        function putData(data)
        {
            $("input[name='company']").val(data.company);
            $("input[name='name']").val(data.name);
            $("input[name='contact']").val(data.contact);
            $("input[name='email']").val(data.email);
            $("input[name='tin']").val(data.tin);
            $("#address").val(data.address);
        }
    </script>
@endsection
