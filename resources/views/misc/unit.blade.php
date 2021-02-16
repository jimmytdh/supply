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
                    <h1 class="m-0 text-dark">Manage Unit of Measure</h1>
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
                            <h3 class="card-title">Add Unit of Measure</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" id="unitForm">
                            <input type="hidden" name="_method" id="method">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Unit Code</label>
                                    <input type="text" autocomplete="off" class="form-control" name="code" placeholder="Unit Code" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" autocomplete="off" class="form-control" name="description" placeholder="Description" required>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer save-footer">
                                <button type="submit" name="submit" value="POST" class="btn btn-primary btn-block">
                                    <i class="fas fa-check"></i> Save
                                </button>
                            </div>
                            <div class="card-footer edit-footer" style="display: none;">
                                <button type="submit" name="submit" value="PATCH" class="btn btn-success btn-block">
                                    <i class="fas fa-check"></i> Update
                                </button>
                                <button type="submit" name="submit" value="DELETE" onclick="deleteFunc()" class="btn btn-danger btn-block">
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
                                        <th>Unit Code</th>
                                        <th>Description</th>
                                        <th>Date Added</th>
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
                ajax: "{{ url('/misc/unit') }}",
                columns: [
                    { data: 'id', name: 'id'},
                    { data: 'code', name: 'code'},
                    { data: 'description', name: 'description'},
                    { data: 'created_at', name: 'created_at'},
                ],
            });

        })
        var id = "0";
        var url = "{{ url('/misc/unit') }}";
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

        $("#unitForm").submit(function(e){
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
                        notify('error','Duplicate entry! Please use different unit code.')
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

        function editFunc(unitID)
        {
            id = unitID;
            url = "{{ url('misc/unit') }}/"+id;
            $("#method").val("PUT");
            $(".save-footer").css('display','none');
            $(".edit-footer").css('display','block');
            $(".card-title").html('Update Unit of Measure');
            $.ajax({
                type: "GET",
                url: url,
                success: (data) => {
                    putData(data);
                }
            })
        }

        function resetForm()
        {
            $("#unitForm").trigger('reset');
            $(".save-footer").css('display','block');
            $(".edit-footer").css('display','none');
            $("input:text:visible:first").focus();
            $("#method").val("POST");
            $(".card-title").html('Add Unit of Measure');
            url = "{{ url('/misc/unit') }}";
        }

        function deleteFunc()
        {
            hideLoader();
            if(confirm("Are you sure you want to delete this unit of measure?")){
                $("#method").val("DELETE");
            }
        }

        function putData(data)
        {
            $("input[name='code']").val(data.code);
            $("input[name='description']").val(data.description);
        }
    </script>
@endsection
