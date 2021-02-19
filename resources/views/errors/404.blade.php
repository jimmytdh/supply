@extends('layout.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>PO # Not Found</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-warning"> 404</h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! PO # Not Found.</h3>

                <p>
                    We could not find the page you were looking for.
                    Meanwhile, you may <a href=".{{ url('/') }}">return to dashboard</a> or try using the search form.
                </p>

                <form class="search-form" action="{{ route('search.po') }}" method="post">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="text" name="po_no" class="form-control" required placeholder="Search PO #">

                        <div class="input-group-append">
                            <button type="submit" name="submit" class="btn btn-warning"><i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.input-group -->
                </form>
            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->

    </section>
@endsection
