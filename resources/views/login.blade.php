<!DOCTYPE html>
<html>

<!-- Mirrored from adminlte.io/themes/dev/AdminLTE/pages/examples/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 27 May 2021 13:28:20 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Penyewaan</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('assets/code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href=""><b>Penyewaan</b> </a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div id="login" class="card-body login-card-body">
                <!-- /.login-card-body -->
                <form id="form-login" method="POST" action="{{ route('loginaksi') }}">
    @csrf
    <div class="input-group mb-3">
        <input type="email" class="form-control" placeholder="Email" name="email_212102" required>
    </div>
    <div class="input-group mb-3">
        <input id="password" type="password" class="form-control" placeholder="Password" name="password_212102" required>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="">
                <input type="checkbox" id="look">
                <label for="s-password">Show Password</label>
            </div>
        </div>
        <div class="col-6">
            <div class="row col-md-12">
                <button id="btn-regis" type="button" class="btn btn-sm btn-success">Sign Up</button>&ensp;
                <button type="submit" class="btn btn-sm btn-primary">Sign In</button>
            </div>
        </div>
    </div>
</form>

                <!-- <a href="{{url('/').'/forgot'}}">Lupa password</a> -->
            </div>
            <!-- /.login-card-body -->

            <div id="regis" class="card-body login-card-body d-none">
                <p class="login-box-msg">Register</p>

                <form id="form-regis" method="POST" action="{{ route('register') }}">
    @csrf
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Nama" name="name_212102" required>
    </div>
    <div class="input-group mb-3">
        <input type="email" class="form-control" placeholder="Email" name="email_212102" required>
    </div>
    <div class="input-group mb-3">
        <input id="password_regis" type="password" class="form-control" placeholder="Password" name="password_212102" required>
    </div>
    <div class="input-group mb-3">
        <input min="0" type="number" class="form-control" placeholder="No Handphone" name="telephone_212102" required>
    </div>
    <div class="row">
        <div class="col-6"></div>
        <div class="col-6">
            <button id="btn-login" type="button" class="btn btn-success btn-sm">Sign In</button>
            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
        </div>
    </div>
</form>

            </div>
        </div>
        <!-- /.login-box -->

        <!-- jQuery -->
        <script src="{{ asset('assets//plugins/jquery/jquery.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>

        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
        <script>
        $(document).ready(function() {
            $('#look').on('click', function() {
                // console.log("Aaa");
                if ($(this).is(":checked")) {
                    $("#password").attr("type", "text");
                } else {
                    $("#password").attr("type", "password");
                }
            })

            $("#btn-regis").on('click', function(e) {
                $('#regis').removeClass("d-none")
                $('#login').addClass("d-none")
            })

            $("#btn-login").on('click', function(e) {
                $('#login').removeClass("d-none")
                $('#regis').addClass("d-none")
            })

        })
        </script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

</body>

<!-- Mirrored from adminlte.io/themes/dev/AdminLTE/pages/examples/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 27 May 2021 13:28:20 GMT -->

</html>