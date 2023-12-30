@extends('admin.includes.master')


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
                <li class="active">Third Parties</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Third Parties</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="group" class="table table-bordered table-striped text-nowrap">
                                    <thead>
                                    <tr>
                                        <th>ID#</th>
                                        <th>NAME</th>
                                        <th>NETWORK</th>
                                        <th>CLIENT ID</th>
                                        <th>API SECRET</th>
                                        <th>ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($parties as $key => $s )
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$s->name}}</td>
                                            <td>{{$s->network}}</td>
                                            <td>{{$s->client_id}}</td>
                                            <td>******************************</td>
                                            <td>
                                                <button class="btn btn-info" ><i class="fa fa-edit"></i> Edit</button>
                                                {{-- <a href="{{url("/service-content?service_id=".$s->id)}}" class="btn bg-purple" ><i class="fa fa-eye"></i> View Content</a> --}}
{{--                                                <button class="btn btn-danger btn-sm" onclick="deleteBody('{{$s->id}}')"><i class="fa fa-trash"></i> Delete</button>--}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

@stop


@section('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <script>

        $('#newBody').submit(function(event){
            event.preventDefault();

            var data = $(this).serialize()
            var title = 'Add Accreditation Body';
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
                        url: "{{url('/new-body')}}",
                        method: "POST",
                        data: data,
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

        function deleteBody(id){
            var title = 'Delete Accreditation Body';
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
                        url: "{{url('delete-body')}}",
                        method: "POST",
                        data: {id: id, _token: "{{Session::token()}}"},
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
        }



        function showModal(id, oldname, oldcountry){
            $('#id').val(id)
            $('#editname').val(oldname)
            // $('edicountry').val(oldcountry)
            $('#bodyModal').modal({
                show: 'false'
            });
        }

        $('#updateBody').submit(function(event){
            event.preventDefault();
            Swal.fire({
                title: "Updating Accreditation Body",
                text: "Are you sure?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Update'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{url('update-body')}}",
                        method: "POST",
                        data: $('#updateBody').serialize(),
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
