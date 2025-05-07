@extends('layout.app')
@section('title', 'Dashboard')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 id="token" class="m-0 text-dark">DAFTAR TRANSAKSI BOOKING</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Booking</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="col-md row ">
            @foreach ($transaksis as $transaksi)
                <div class="card col-md-5 mb-2 mr-2">
                    <div class="card-body">
                        <h4 style="font-size: 20px;">Nomor Transaksi : {{$transaksi->no_transaksi_212102}}</h4>
                        <h4 style="font-size: 20px;">
                            Status : 
                            {{
                                $transaksi->status_212102 == 0 ? 'Menunggu' : 
                                ($transaksi->status_212102 == 1 ? 'Diterima' : 'Gagal')
                            }}
                        </h4>

                        @php
                            $jam = json_decode($transaksi->jam, true);
                            if (is_array($jam)) sort($jam);
                        @endphp

                        <div class="row">
                            <div class="col-md-3">Nama</div>
                            <div class="col-md-9">: {{ $transaksi->user->name }}</div>

                            <div class="col-md-3">Nama Sewa</div>
                            <div class="col-md-9">: 
                                {{ $transaksi->transaksi_details[0]->menuitem->name ?? 'Tidak tersedia' }}
                            </div>

                            <div class="col-md-3">Harga Sewa</div>
                            <div class="col-md-9">: Rp. {{ number_format($transaksi->price_212102, 2, ',', '.') }}</div>

                            <div class="col-md-3">Tanggal</div>
                            <div class="col-md-9">: {{ $transaksi->start_time }}</div>

                            <div class="col-md-3">Total Jam</div>
                            <div class="col-md-9">: 
                                {{ is_array($jam) ? count($jam) . ' jam' : 'Tidak ada data jam' }}
                            </div>

                            <div class="col-md-3">Detail Jam</div>
                            <div class="col-md-9">:
                                @if (is_array($jam))
                                    @foreach ($jam as $j)
                                        {{ $j }}
                                    @endforeach
                                @else
                                    Tidak ada data jam
                                @endif
                            </div>

                            <div class="col-md-3">Total Harga</div>
                            <div class="col-md-9">: Rp. {{ number_format($transaksi->total_price_212102, 2, ',', '.') }}</div>

                            <div class="col-md-3">Catatan</div>
                            <div class="col-md-9">: {{ $transaksi->noted_212102 }}</div>

                            <div class="col-md-12 mt-2">
                                <button class="btn btn-sm btn-primary" id="btn-accept"
                                        data-id="{{ $transaksi->id_212102 }}"
                                        menu-id="{{ $transaksi->transaksi_details[0]->menuitem_id ?? '' }}">
                                    Terima
                                </button>
                                <button class="btn btn-sm btn-danger" id="btn-reject"
                                        data-id="{{ $transaksi->id_212102 }}"
                                        menu-id="{{ $transaksi->transaksi_details[0]->menuitem_id ?? '' }}">
                                    Tolak
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@section('js-source')
<script src="{{ asset('src/main.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
@endsection
