@extends('customer.layout.app')
@section('title', 'Booking')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Welcome {{ Auth::user()->name_212102 }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Customer</a></li>
                    <li class="breadcrumb-item active">Booking</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Main row -->
        <div class="col-md-12 row">
            @foreach ($transaksis as $transaksi)
            <div class="card shadow-lg col-md-5 mb-3 mr-3">
                <div class="card-header">
                    <div style="font-size: 14px;">Nomor Transaksi : {{$transaksi->no_transaksi_212102}}</div>
                    <div style="font-size: 14px;">Status :
                        {{$transaksi->status_212102 == 1 ? 'Menunggu' : ($transaksi->status_212102 == 0 ? 'Diterima' : 'Gagal')}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            Harga Sewa
                        </div>
                        <div class="col-md-8">
                            : Rp. 
                            @if(is_numeric($transaksi->price_212102) && $transaksi->price_212102 > 0)
                                <span id="harga">{{ number_format($transaksi->price_212102, 2, ',', '.') }}</span>
                            @else
                                <span id="harga">Rp. 0,00</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            Tanggal
                        </div>
                        <div class="col-md-8">
                            : <span id="tgl">{{ \Carbon\Carbon::parse($transaksi->start_time)->format('d-m-Y') }}</span>
                        </div>

                        <div class="col-md-4">
                            Waktu Mulai
                        </div>
                        <div class="col-md-8">
                            : <span id="waktu_mulai">{{ \Carbon\Carbon::parse($transaksi->start_time)->format('H:i') }}</span>
                        </div>

                        <div class="col-md-4">
                            Waktu Selesai
                        </div>
                        <div class="col-md-8">
                            : <span id="waktu_selesai">{{ $transaksi->end_time ? \Carbon\Carbon::parse($transaksi->end_time)->format('H:i') : 'Tidak ada waktu selesai' }}</span>
                        </div>

                        <div class="col-md-4">
                            Total Jam
                        </div>
                        <div class="col-md-8">
                            : 
                            @php
                                $startTime = \Carbon\Carbon::parse($transaksi->start_time);
                                $endTime = $transaksi->end_time ? \Carbon\Carbon::parse($transaksi->end_time) : null;
                                $totalJam = $endTime ? $startTime->diffInHours($endTime) : 0;
                            @endphp
                            <span id="total_jam">{{ $totalJam }} jam</span>
                        </div>

                        <div class="col-md-4">
                            Catatan
                        </div>
                        <div class="col-md-8">
                            : <span>{{ $transaksi->noted_212102 }}</span>
                        </div>

                        <div class="col-md-4">
                            Total Harga
                        </div>
                        <div class="col-md-8">
                            : Rp. 
                            @if(is_numeric($transaksi->total_price_212102) && $transaksi->total_price_212102 > 0)
                                <span id="total">{{ number_format($transaksi->total_price_212102, 2, ',', '.') }}</span>
                            @else
                                <span id="total">Rp. 0,00</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!-- /.row (main row) -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('js-source')
<script src="{{ asset('src/main.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
@endsection
