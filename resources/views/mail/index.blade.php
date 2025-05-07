<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

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
    <title>Reset Password</title>
</head>

<body>
    <div class="card">
        <div class="card-header">
            Password Reset
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    Token:
                </div>
                <div class="col-md-9">
                    : {{ $token }}
                </div>
                <div class="col-md-3">
                    Expired Token:
                </div>
                <div class="col-md-9">
                    : {{ $expired_date }}
                </div>
                <div class="col-md-3">
                    Name:
                </div>
                <div class="col-md-9">
                    : {{ $name }}
                </div>
                <div class="col-md-12">
                    <a href="{{ url('/') . '/verify-token' . '?token=' . $token }}" class="btn btn-primary btn-sm">Click
                        Here</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
