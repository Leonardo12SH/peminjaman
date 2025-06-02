@extends('layout.app') {{-- Pastikan ini adalah layout admin yang benar --}}
@section('title', 'Detail Transaksi ' . $transaksi->no_transaksi_212102)

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Detail Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.transaksi.history') }}">Riwayat Transaksi</a></li>
                        <li class="breadcrumb-item active">Detail: {{ $transaksi->no_transaksi_212102 }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary card-outline shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Transaksi: <strong>{{ $transaksi->no_transaksi_212102 }}</strong></h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.transaksi.history') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
                                </a>
                                {{-- Tombol aksi lain seperti cetak atau edit bisa ditambahkan di sini --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Nomor Transaksi</dt>
                                <dd class="col-sm-8">: {{ $transaksi->no_transaksi_212102 }}</dd>

                                <dt class="col-sm-4">Status</dt>
                                <dd class="col-sm-8">:
                                    @if($transaksi->status_212102 == 1)
                                        <span class="badge badge-warning">Menunggu</span>
                                    @elseif($transaksi->status_212102 == 0)
                                        <span class="badge badge-success">Diterima</span>
                                    @else
                                        <span class="badge badge-danger">Ditolak/Gagal</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Tanggal Transaksi</dt>
                                <dd class="col-sm-8">: {{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('l, d F Y H:i') }}</dd>

                                <dt class="col-sm-4">Waktu Booking</dt>
                                <dd class="col-sm-8">: {{ \Carbon\Carbon::parse($transaksi->start_time)->translatedFormat('d F Y, H:i') }}
                                    @if($transaksi->end_time)
                                        s/d {{ \Carbon\Carbon::parse($transaksi->end_time)->format('H:i') }}
                                        ({{ \Carbon\Carbon::parse($transaksi->start_time)->diffInHours($transaksi->end_time) }} jam)
                                    @endif
                                </dd>

                                @if($transaksi->item) {{-- Jika ada relasi ke item utama --}}
                                <dt class="col-sm-4">Item Utama Dibooking</dt>
                                <dd class="col-sm-8">: {{ $transaksi->item->name_212102 ?? $transaksi->item->name_212102 ?? 'N/A' }}</dd>
                                @elseif($transaksi->item_id_212102)
                                <dt class="col-sm-4">ID Item Utama</dt>
                                <dd class="col-sm-8">: {{ $transaksi->item_id_212102 }} (Item tidak terelasi)</dd>
                                @endif

                                <dt class="col-sm-4">Harga per Jam/Sesi</dt>
                                <dd class="col-sm-8">: Rp {{ number_format($transaksi->price_212102 ?? 0, 0, ',', '.') }}</dd>

                                <dt class="col-sm-4">Total Harga</dt>
                                <dd class="col-sm-8">: <strong>Rp {{ number_format($transaksi->total_price_212102 ?? $transaksi->total_price ?? 0, 0, ',', '.') }}</strong></dd>

                                <dt class="col-sm-4">Catatan</dt>
                                <dd class="col-sm-8">: {{ $transaksi->noted_212102 ?: '-' }}</dd>

                                <dt class="col-sm-4">Terakhir Diperbarui</dt>
                                <dd class="col-sm-8">: {{ \Carbon\Carbon::parse($transaksi->updated_at)->diffForHumans() }}</dd>
                            </dl>
                        </div>
                    </div>

                    @if($transaksi->transaksi_details && $transaksi->transaksi_details->count() > 0)
                    <div class="card card-info card-outline shadow-sm mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Detail Item/Layanan Tambahan</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Item/Layanan</th>
                                            <th>Kuantitas</th>
                                            <th>Harga Satuan</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transaksi->transaksi_details as $idx => $detail)
                                            <tr>
                                                <td>{{ $idx + 1 }}</td>
                                                <td>{{ $detail->menuItem->nama_menu_212102 ?? $detail->menuItem->name ?? 'Item Tidak Diketahui' }}</td> {{-- Sesuaikan dengan nama field di model MenuItem Anda --}}
                                                <td>{{ $detail->quantity_212102 ?? $detail->qty ?? 1 }}</td> {{-- Sesuaikan field quantity --}}
                                                <td>Rp {{ number_format($detail->price_212102 ?? $detail->price ?? 0, 0, ',', '.') }}</td> {{-- Sesuaikan field price --}}
                                                <td>Rp {{ number_format(($detail->quantity_212102 ?? $detail->qty ?? 1) * ($detail->price_212102 ?? $detail->price ?? 0), 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                <div class="col-md-4">
                    <div class="card card-secondary card-outline shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Penyewa</h3>
                        </div>
                        <div class="card-body">
                            @if($transaksi->user)
                                <dl>
                                    <dt>Nama</dt>
                                    <dd>{{ $transaksi->user->name_212102 ?? 'N/A' }}</dd>

                                    <dt>Email</dt>
                                    <dd>{{ $transaksi->user->email_212102 ?? 'N/A' }}</dd>

                                    <dt>No. Telepon</dt>
                                    <dd>{{ $transaksi->user->telephone_212102 ?? $transaksi->user->telephone_212102 ?? '-' }}</dd> {{-- Sesuaikan field telepon --}}
                                </dl>
                            @else
                                <p class="text-muted">Informasi penyewa tidak tersedia.</p>
                            @endif
                        </div>
                    </div>
                     {{-- Tempat untuk QR Code jika ada --}}
                    {{-- <div class="card card-default shadow-sm mt-3">
                        <div class="card-header">
                            <h3 class="card-title">QR Code Transaksi</h3>
                        </div>
                        <div class="card-body text-center">
                             @php
                                 $qrCodeUrl = route('public.transaksi.detail', $transaksi->no_transaksi_212102); // Buat route publik jika perlu
                             @endphp
                             {!! QrCode::size(150)->generate($qrCodeUrl); !!}
                             <p class="mt-2"><small>Pindai untuk melihat detail</small></p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .card-body dl dd {
        margin-bottom: .5rem; /* Jarak antar baris detail */
    }
    .card-body dl dt {
        font-weight: 600; /* Sedikit tebalkan label */
    }
</style>
@endpush

@push('scripts')
{{-- Script khusus untuk halaman ini jika ada --}}
@endpush