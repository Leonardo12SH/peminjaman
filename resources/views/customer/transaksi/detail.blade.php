<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('assets/code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <title>BOOKING HASIL</title>
</head>

<body>

    <div class="col-12">
        <div class="card col-md-6 mb-2 mr-2 mx-auto my-5">
            <div class="card-body">
                <h4 style="font-size: 20px;">Nomor Transaksi : {{ $transaksi->no_transaksi }}</h4>
                <h4 style="font-size: 20px;">Status :
                    {{ $transaksi->status == 0 ? 'Menunggu' : ($transaksi->status == 1 ? 'Diterima' : 'Gagal') }}</h4>
                <div class="row">
                    <div class="col-md-3">
                        Nama:
                    </div>
                    <div class="col-md-9">
                        : {{ $transaksi->user->name }}
                    </div>
                    <div class="col-md-3">
                        Nama Sewa
                    </div>
                    <div class="col-md-9">
                        : <span id="item">{{ $transaksi->transaksi_details[0]->menuitem->name }}</span>
                    </div>
                    <div class="col-md-3">
                        Harga Sewa
                    </div>
                    <div class="col-md-9">
                        : Rp. <span id="harga">{{ number_format($transaksi->price, '2', ',') }}</span>
                    </div>
                    <div class="col-md-3">
                        Tanggal
                    </div>
                    <div class="col-md-9">
                        : <span id="tgl">{{ $transaksi->start_time }}</span>
                    </div>
                    <div class="col-md-3">
                        Total Jam
                    </div>
                    <div class="col-md-9">
                        : <span id="total_jam">{{ count(json_decode($transaksi->jam)) }} jam</span>
                    </div>
                    @php
                        $jam = json_decode($transaksi->jam);
                        usort($jam, function ($a, $b) {
                            return (float) $a > (float) $b;
                        });
                    @endphp
                    <div class="col-md-3">
                        Detail Jam
                    </div>
                    <div class="col-md-9">
                        : <span id="detail_jam">
                            @foreach ($jam as $j)
                                {{ $j }}
                            @endforeach
                        </span>
                    </div>
                    <div class="col-md-3">
                        Total Harga
                    </div>
                    <div class="col-md-9">
                        : Rp. <span id="total">{{ number_format($transaksi->total_price, '2', ',') }}</span>
                    </div>
                    <div class="col-md-3">
                        Catatan
                    </div>
                    <div class="col-md-9">
                        : <span>{{ $transaksi->noted }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
