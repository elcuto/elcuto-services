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
                <li class="active">Add SMS Content</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">New Content</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form method="POST" id="newContent" action="#">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="s_service">Select Service</label>
                                            <select id="s_service" class="form-control" name="service_id" required>
                                                <option value="" disabled="disabled" selected="selected">Select an option</option>
                                                @foreach($services as $s)
                                                    <option value="{{$s->id}}">{{$s->service_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" id="usernamecont">
                                            <label for="sms_content">SMS Content</label>
                                            <textarea  type="text" rows="5" maxlength="160" class="form-control" name="sms_content" id="sms_content"></textarea>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Content</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Load From File(Excel)</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form method="POST" id="excelContentLoad" action="#">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Select Service</label>
                                            <select class="form-control" name="service_id" required>
                                                <option value="" disabled="disabled" selected="selected">Select an option</option>
                                                @foreach($services as $s)
                                                    <option value="{{$s->id}}">{{$s->service_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" >
                                            <label for="excel_file">Excel File</label>
                                            <input type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" id="excel_file" class="form-control" name="excel_file" />
                                            <small style="color:red;">Load one service at a time</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-plus-circle"></i> Load File</button>
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

        $('#newContent').submit(function(event){
            event.preventDefault();

            var data = new FormData($("#newContent")[0]);
            var title = 'Add New SMS Content';
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
                        url: "{{url('/save-content')}}",
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

        $('#excelContentLoad').submit(function(event){
            event.preventDefault();

            var data = new FormData($("#excelContentLoad")[0]);
            var title = 'Upload SMSContent File';
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
                        url: "{{url('/load-excel-content')}}",
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
