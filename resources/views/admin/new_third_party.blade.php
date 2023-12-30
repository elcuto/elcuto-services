@extends('admin.includes.master')

@section('page_styles')

@stop

@section('contentone')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
                <small>Control panel</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Integrate Third Party Company</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">New Company</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form method="POST" id="new_thirdparty" action="#">
                                {{csrf_field()}}

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" >
                                            <label for="name">Company Name</label>
                                            <input id="name" required  type="text" class="form-control" name="name" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" >
                                            <label for="email">Company Email</label>
                                            <input id="email" required  type="text" class="form-control" name="email" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" >
                                            <label for="question">Description</label>
                                            <textarea required type="text" rows="5" class="form-control" name="description" id="question"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" >
                                            <label for="network">Network</label>
                                            <select class="form-control" name="network">
                                                <option value="ALL">ALL</option>
                                                <option value="AT">AT</option>
                                                <option value="MTN">MTN</option>
                                                <option value="VODAFONE">VODAFONE</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" >
                                            <label for="client_id">Client ID</label>
                                            <input id="client_id" type="text" value="{{$cleint_id}}" readonly class="form-control" name="client_id" />
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Company</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- /.content -->
    </div>

@stop


@section('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.1/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.1/js/plugins/buffer.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.1/js/plugins/filetype.min.js" type="text/javascript"></script>

    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.1/js/fileinput.min.js"></script>
    <script>
        $(document).ready(function() {
            // with plugin options
            $("#input-id").fileinput({'previewFileType': 'any', 'showUpload': false, 'maxFileCount': 0});
        });
    </script>
    <script>

        $('#new_thirdparty').submit(function(event){
            event.preventDefault();

            var data = new FormData($("#new_thirdparty")[0]);
            var title = 'Add New Third Party Company';
            Swal.fire({
                title: title,
                text: "Are you sure?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Proceed'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{url('/save-third-party')}}",
                        method: "POST",
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        enctype: 'multipart/form-data',
                        success: function(res){
                            if(res.status =='success'){
                                Swal.fire(
                                    'Success',
                                    res.message,
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            }else{
                                Swal.fire(
                                    'Error',
                                    res.message,
                                    'error'
                                );
                            }
                        },
                        error: function(error){
                            console.log(error),
                                Swal.fire(
                                    'Error',
                                    "Oops something went wrong.",
                                    'error'
                                );
                        }
                    })
                }
            })
        })


    </script>


@stop
