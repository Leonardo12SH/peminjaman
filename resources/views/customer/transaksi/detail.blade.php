@extends('customer.layout.app')
@section('title', 'Detail Transaksi: ' . $transaksi->no_transaksi_212102)

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Detail Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        {{-- Ganti route('home') dengan route dashboard customer Anda --}}
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        {{-- Ganti route('history') dengan route riwayat transaksi customer Anda --}}
                        <li class="breadcrumb-item"><a href="{{ route('history') }}">Riwayat Transaksi</a></li>
                        <li class="breadcrumb-item active">Detail: {{ $transaksi->no_transaksi_212102 }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Nomor Transaksi: <strong>{{ $transaksi->no_transaksi_212102 }}</strong></h3>
                            <div class="card-tools">
                                {{-- Ganti route('history') dengan route riwayat transaksi customer Anda --}}
                                <a href="{{ route('history') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- PESAN KHUSUS JIKA STATUS DITERIMA/SELESAI --}}
                            @if($transaksi->status_212102 == 0) {{-- Asumsi status 0 adalah Diterima/Selesai --}}
                                <div class="alert alert-success" role="alert">
                                    <h4 class="alert-heading">Pesanan Diterima!</h4>
                                    <p>Silahkan datang ke toko kami pada alamat <strong>Jl. Geol da van si</strong> untuk pengambilan atau layanan sesuai dengan waktu yang telah Anda pesan.</p>
                                    <hr>
                                    <p class="mb-0">Pastikan Anda membawa detail transaksi ini atau nomor transaksi saat datang.</p>
                                </div>
                            @endif
                            {{-- AKHIR PESAN KHUSUS --}}

                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Informasi Umum</h4>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th style="width: 30%;">Nama Penyewa</th>
                                            <td>: {{ $transaksi->user->name_212102 ?? 'Tidak Diketahui' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Transaksi</th>
                                            <td>: {{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d F Y, H:i') }} WIB</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>:
                                                @if($transaksi->status_212102 == 1)
                                                    <span class="badge badge-warning">Menunggu Konfirmasi</span>
                                                @elseif($transaksi->status_212102 == 0)
                                                    <span class="badge badge-success">Diterima/Selesai</span>
                                                @elseif($transaksi->status_212102 == 2)
                                                    <span class="badge badge-info">Aktif/Berjalan</span>
                                                @elseif($transaksi->status_212102 == 3)
                                                    <span class="badge badge-danger">Dibatalkan/Ditolak</span>
                                                @else
                                                    <span class="badge badge-secondary">Status Tidak Diketahui</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h4>Detail Waktu Sewa</h4>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th style="width: 30%;">Waktu Mulai</th>
                                            <td>: {{ \Carbon\Carbon::parse($transaksi->start_time)->translatedFormat('d M Y, H:i') }} WIB</td>
                                        </tr>
                                        <tr>
                                            <th>Waktu Selesai</th>
                                            <td>: {{ $transaksi->end_time ? \Carbon\Carbon::parse($transaksi->end_time)->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Durasi</th>
                                            <td>:
                                                @php
                                                    $startTime = \Carbon\Carbon::parse($transaksi->start_time);
                                                    $endTime = $transaksi->end_time ? \Carbon\Carbon::parse($transaksi->end_time) : null;
                                                    $totalJam = 0;
                                                    if ($endTime && $endTime->greaterThan($startTime)) {
                                                        $totalMenit = $startTime->diffInMinutes($endTime);
                                                        $totalJam = number_format($totalMenit / 60, 2);
                                                    }
                                                @endphp
                                                {{ $totalJam }} Jam
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <hr>

                            <h4>Item yang Disewa</h4>
                            @if($transaksi->transaksi_details && $transaksi->transaksi_details->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Item</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($transaksi->transaksi_details as $index => $detail)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $detail->menuitem->name_212102 ?? 'Item tidak tersedia' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                @if($transaksi->relationLoaded('item') && $transaksi->item)
                                    <p><strong>Item Utama:</strong> {{ $transaksi->item->name_212102 ?? 'Tidak ada item terkait' }}</p>
                                @elseif(isset($transaksi->item_id_212102) && method_exists($transaksi, 'item') && $transaksi->item) {{-- Cek jika relasi 'item' ada dan item_id_212102 terisi --}}
                                    <p><strong>Item Utama:</strong> {{ $transaksi->item->name_212102 }}</p>
                                @elseif(isset($transaksi->item_id_212102))
                                    <p><em>Tidak ada detail item spesifik. Item ID pada transaksi ini adalah {{ $transaksi->item_id_212102 }}. Anda mungkin perlu memuat relasi itemnya di controller.</em></p>
                                @else
                                    <p><em>Tidak ada detail item yang tercatat untuk transaksi ini.</em></p>
                                @endif
                            @endif


                            @if($transaksi->noted_212102)
                            <hr>
                            <h4>Catatan Tambahan</h4>
                            <p>{{ nl2br(e($transaksi->noted_212102)) }}</p>
                            @endif

                            <hr>

                            <h4>Rincian Pembayaran</h4>
                            <div class="row">
                                <div class="col-md-6 offset-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Harga Sewa per Jam</th>
                                            <td class="text-right">Rp {{ number_format($transaksi->price_212102 ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="h5">Total Pembayaran</th>
                                            <td class="text-right h5"><strong>Rp {{ number_format($transaksi->total_price_212102 ?? 0, 0, ',', '.') }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer text-right">
                            {{-- Ganti route('history') dengan route riwayat transaksi customer Anda --}}
                            <a href="{{ route('history') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .table-borderless th, .table-borderless td {
        border: 0 !important;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
    }
    .card-body h4 {
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-bottom: 1px solid #eee;
        padding-bottom: 0.5rem;
    }
    .card-body h4:first-of-type {
        margin-top: 0;
    }
    .alert-heading {
        font-size: 1.25rem; /* Sedikit lebih besar untuk judul alert */
    }
</style>
@endpush