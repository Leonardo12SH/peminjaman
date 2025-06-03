@extends('customer.layout.app') {{-- Tetap menggunakan layout customer --}}
@section('title', 'Riwayat Transaksi Anda') {{-- Judul diubah --}}

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- Judul halaman diubah agar lebih sesuai untuk customer --}}
                    <h1 class="m-0 text-dark">Riwayat Transaksi Anda</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        {{-- Sesuaikan dengan route dashboard customer jika ada, atau biarkan # --}}
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Riwayat Transaksi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Transaksi Anda</h3>
                            <div class="card-tools">
                                {{-- Form Pencarian --}}
                                {{-- Pastikan route('customer.history') adalah route yang benar untuk halaman ini --}}
                                <form action="{{ route('customer.transaksi.history') }}" method="GET" class="input-group input-group-sm" style="width: 250px;">
                                    <input type="text" name="search" class="form-control float-right" placeholder="Cari No. Transaksi..." value="{{ request('search') ?? '' }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                        @if(request('search'))
                                            <a href="{{ route('customer.transaksi.history') }}" class="btn btn-warning" title="Reset Pencarian"><i class="fas fa-times"></i></a>
                                        @endif
                                    </div>
                                </form>
                                {{-- CATATAN: Untuk fungsionalitas pencarian, method 'history' di TransactionController Anda perlu diupdate
                                     untuk menangani parameter 'search' dari request. --}}
                            </div>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table id="historyTable" class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>No. Transaksi</th>
                                            {{-- Nama Penyewa tidak perlu karena ini history milik user yg login --}}
                                            {{-- <th class="d-none d-lg-table-cell">Nama Penyewa</th> --}}
                                            <th class="d-none d-lg-table-cell">Tanggal Transaksi</th>
                                            <th class="d-none d-md-table-cell">Waktu Mulai</th>
                                            <th class="d-none d-md-table-cell">Waktu Selesai</th>
                                            <th class="d-none d-md-table-cell">Total Jam</th>
                                            <th>Total Harga</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($history as $index => $h)
                                            <tr>
                                                <td>{{ $history->firstItem() + $index }}</td>
                                                <td>{{ $h->no_transaksi_212102 }}</td>
                                                {{-- <td>{{ $h->user->name_212102 ?? 'N/A' }}</td> --}}
                                                <td class="d-none d-lg-table-cell">{{ \Carbon\Carbon::parse($h->start_time)->translatedFormat('d M Y') }}</td>
                                                <td class="d-none d-md-table-cell">{{ \Carbon\Carbon::parse($h->start_time)->format('H:i') }}</td>
                                                <td class="d-none d-md-table-cell">{{ $h->end_time ? \Carbon\Carbon::parse($h->end_time)->format('H:i') : '-' }}</td>
                                                <td class="d-none d-md-table-cell">
                                                    @php
                                                        $startTime = \Carbon\Carbon::parse($h->start_time);
                                                        $endTime = $h->end_time ? \Carbon\Carbon::parse($h->end_time) : null;
                                                        // Hitung selisih jam dengan presisi (misal, dalam menit lalu bagi 60)
                                                        $totalMenit = $endTime ? $startTime->diffInMinutes($endTime) : 0;
                                                        $totalJamCalculated = $endTime ? number_format($totalMenit / 60, 2) : 0;
                                                    @endphp
                                                    {{ $totalJamCalculated }} Jam
                                                </td>
                                                <td>Rp {{ number_format($h->total_price_212102 ?? 0, 0, ',', '.') }}</td>
                                                <td>
                                                    {{-- Sesuaikan logika status dengan sistem Anda --}}
                                                    {{-- Contoh berdasarkan store() Anda: 1 = baru dibuat (Menunggu Konfirmasi/Pembayaran) --}}
                                                    {{-- Asumsi status dari admin template: 1=Menunggu, 0=Diterima, lainnya=Ditolak --}}
                                                    @if($h->status_212102 == 1) {{-- Atau status awal Anda --}}
                                                        <span class="badge badge-warning">Menunggu Konfirmasi</span>
                                                    @elseif($h->status_212102 == 0) {{-- Misal 0 untuk Selesai/Diterima --}}
                                                        <span class="badge badge-success">Selesai</span>
                                                    @elseif($h->status_212102 == 2) {{-- Misal 2 untuk Dipesan/Aktif --}}
                                                        <span class="badge badge-info">Dipesan</span>
                                                    @else {{-- Misal status lain untuk Dibatalkan/Gagal --}}
                                                        <span class="badge badge-danger">Dibatalkan/Gagal</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{-- Pastikan route 'customer.transaksi.detail' ada dan benar --}}
                                                    <a href="{{ route('customer.transaksi.detail', $h->id_212102) }}" class="btn btn-info btn-xs mr-1" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i> <span class="d-none d-md-inline">Detail</span>
                                                    </a>
                                                    {{-- Tombol Delete dihilangkan sesuai permintaan --}}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center"> {{-- Colspan disesuaikan --}}
                                                    @if(request('search'))
                                                        Transaksi dengan kata kunci "<strong>{{ request('search') }}</strong>" tidak ditemukan.
                                                    @else
                                                        Tidak ada data riwayat transaksi.
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            {{-- Menampilkan link pagination, pastikan $history adalah instance Paginator --}}
                            {{ $history->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .btn-xs {
            padding: .25rem .4rem;
            font-size: .875rem;
            line-height: .5;
            border-radius: .2rem;
        }
        .card-header .card-tools {
            margin-right: 0; /* Mengatasi potensi override dari AdminLTE */
        }
        .table td, .table th { /* Untuk memastikan tombol tidak terlalu mepet dan teks rata tengah vertikal */
            vertical-align: middle;
        }
    </style>
@endpush

@push('scripts')
    {{-- Jika Anda menggunakan jQuery dari layout utama, ini mungkin tidak perlu,
         tapi jika tidak, pastikan jQuery dimuat sebelum script lain yang membutuhkannya. --}}
    {{-- <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script> --}}
    <script>
        $(function () {
            // Script khusus jika diperlukan, untuk saat ini kosong karena tombol delete dihilangkan
        });
    </script>
@endpush