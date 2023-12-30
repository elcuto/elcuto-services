@extends('admin.includes.master')

@section('pagestyles')

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
                <li class="active">Add New Service</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">New Service</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form method="POST" id="new_service" action="#">
                                {{csrf_field()}}

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="service_name">Service Name</label>
                                            <input  type="text"  class="form-control" name="service_name" id="service_name" />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="s_service">Select Short Code</label>
                                            <select id="s_service" class="form-control" name="shortcode" required>
                                                <option value="" disabled="disabled" selected="selected">Select an option</option>
                                                @foreach($shortcodes as $s)
                                                    <option value="{{$s->name}}">{{$s->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="network">Select Network</label>
                                            <select id="network" class="form-control" name="network" required>
                                                <option value="" disabled="disabled" selected="selected">Select an option</option>
                                                <option value="MTN">MTN</option>
                                                <option value="AT">AT</option>
                                                <option value="VODAFONE">VODAFONE</option>
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-md-12 at_field" style="display:none;">
                                        <div class="form-group">
                                            <label for="at_service_id">AT Product ID</label>
                                            <input  type="text"  class="form-control" name="at_service_id" id="at_service_id" />
                                        </div>
                                    </div>



                                    <div class="col-md-12 vf_field" style="display:none;">
                                        <div class="form-group">
                                            <label for="vf_service_id">Vodafone Service ID</label>
                                            <input  type="text"  class="form-control" name="vf_service_id" id="vf_service_id" />
                                        </div>
                                    </div>



                                    <div class="col-md-12 vf_field" style="display:none;">
                                        <div class="form-group">
                                            <label for="vf_offer_id">Vodafone Offer ID</label>
                                            <input  type="text"  class="form-control" name="vf_offer_id" id="vf_offer_id" />
                                        </div>
                                    </div>


                                <div class="row vf_field" style="display:none;">
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="vf_client_id">Vodafone Client ID</label>
                                                <input  type="text"  class="form-control" name="vf_client_id" id="vf_client_id" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="vf_api_key">Vodafone API Key</label>
                                                <input  type="text"  class="form-control" name="vf_api_key" id="vf_api_key" />
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                    <div class="col-md-12">
                                        <div class="form-group" id="usernamecont">
                                            <label for="description">Service Description</label>
                                            <textarea  type="text" rows="5" maxlength="160" class="form-control" name="description" id="description"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="service_owner">Service Owner</label>
                                            <select id="service_owner" class="form-control" name="service_owner" required>
                                                <option value="" disabled="disabled" selected="selected">Select an option</option>
                                                <option value="ELCUTO">ELCUTO</option>
                                                <option value="THIRD_PARTY">THIRD_PARTY</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="display: none" id="company_con">
                                        <div class="form-group">
                                            <label for="company">Company Name</label>
                                            <select id="company" class="form-control" name="company" >
                                                <option value="" disabled="disabled" selected="selected">Select an option</option>
                                                @foreach($parties as $p)
                                                    <option value="{{$p->id}}">{{$p->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12" id="callback_url_con" style="display:none;">
                                        <div class="form-group">
                                            <label for="callback_url">Callback URL</label>
                                            <input type="url"  class="form-control" name="callback_url" id="callback_url" />
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add New Service</button>
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

        $("#network").on("change", function(){
            var selected = $(this).val();
            if(selected == "AT"){
                $('.vf_field').hide();
                $('.at_field').show();
                $('#at_service_id').prop("required", true);
                $("#vf_offer_id").prop("required", false);
                $("#vf_service_id").prop("required", false);
                $("#vf_client_id").prop("required", false);
                $("#vf_api_key").prop("required", false);
            }else if(selected == "VODAFONE"){
                $('#at_service_id').prop("required", false);
                $('.vf_field').show();
                $("#vf_offer_id").prop("required", true);
                $("#vf_service_id").prop("required", true);
                $("#vf_client_id").prop("required", true);
                $("#vf_api_key").prop("required", true);
                $('.at_field').hide();
            }else if(selected == "MTN"){
                $("#vf_offer_id").prop("required", false);
                $("#vf_service_id").prop("required", false);
                $("#vf_client_id").prop("required", false);
                $("#vf_api_key").prop("required", false);
                $('#at_service_id').prop("required", false);
                $('.vf_field').hide();
                $('.at_field').hide();

            }
        })


        $("#service_owner").on("change", function(){
            var service_owner = $(this).val();
            if(service_owner == "ELCUTO"){
                $("#callback_url").prop("required", false);
                $("#company").prop("required", false);
                $('#callback_url_con').hide();
                $('#company_con').hide();
            }else{ 
                $('#callback_url_con').show();
                $('#company_con').show();
                $("#callback_url").prop("required", true);
                $("#company").prop("required", true);
            }
        })



        $('#new_service').submit(function(event){
            event.preventDefault();

            var data = new FormData($("#new_service")[0]);
            var title = 'Add New Service';
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
                        url: "{{url('/new-service')}}",
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
