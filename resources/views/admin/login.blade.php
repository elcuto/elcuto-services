<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>El Cuto Dashboard | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('assets/bower_components/Ionicons/css/ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/dist/css/AdminLTE.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('assets/plugins/iCheck/square/blue.css')}}">

    <style>
        #bgleft {
            background-image: url("{{asset('assets/images/bgwhite1100.jpg')}}");
            height: 100vh;
            min-height: 100vh;
            background-position: bottom;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            object-fit: cover;
            background-size: auto 100%;
        }
        #bgleft h1{
            font-weight: bold;
            color: white;
        }
        #bgleft h3{
            color: white;
        }
        #loginsection {
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 80px;
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->

    <!-- Fonts -->
    <!-- <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" /> -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300&display=swap" rel="stylesheet">


</head>
<body class="hold-transition login-page" style="min-height: 100vh;">
<div id="bgleft" class="col-md-7">
    <div class="" style="background-color: rgba(0, 0, 0, 0.707); padding: 50px;">
        <h1>EL CUTO SERVICES</h1>
        <h3>MANAGEMENT PORTAL</h3>
    </div>
</div>

<div class="col-md-5" id="loginsection">
    <div class="login-box" >
        <div class="login-logo">
            <a href="#"><b>ADMIN </b>LOGIN</a>
        </div>
        <div class="login-box-body" style="padding-top: 50px; padding-bottom: 50px;">
            <p class="login-box-msg">Sign in to continue</p>

            <p class="alert alert-danger" style="display:none" id="errormessage"></p>
            @if(Session::has('success'))
                <p class="alert alert-success">{{Session::get('success')}}</p>
            @endif

            <form id="userLogin" action="#" method="post">
                {{csrf_field()}}
                <div class="form-group has-feedback">
                    <input type="text" name="email" class="form-control" placeholder="Email Address">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">

                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-lock" id="lock"></i>  <i class="fa fa-spinner fa-spin" id="loader" style="display:none"></i> Sign In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery 3 -->
<script src="{{asset('assets/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });
    });
</script>

<script>
    $('#userLogin').submit(function(event){
        event.preventDefault();
        $('#lock').hide();
        $('#loader').show();
        $.ajax({
            url: "{{url('/admin-login')}}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res){
                $('#lock').show();
                $('#loader').hide();
                if(res.status =='success'){
                    window.location.href=res.url;
                }else{
                    $('#errormessage').text(res.message).show().delay(1500).slideUp(1000);
                }
            },
            error: function(error){
                console.log(error);
                $('#lock').show();
                $('#loader').hide();
            }
        })
    })

    $('.alert .alert-success').setTimeout(() => {
        $(this).hide()
    }, 2000);
</script>
</body>
</html>
