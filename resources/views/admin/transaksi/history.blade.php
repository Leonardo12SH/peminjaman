@extends('layout.app') {{-- Pastikan ini adalah layout admin yang benar --}}
@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Riwayat Transaksi</h1>
                </div><div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li> {{-- Sesuaikan dengan route dashboard admin --}}
                        <li class="breadcrumb-item active">Riwayat Transaksi</li>
                    </ol>
                </div></div></div></div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Semua Transaksi</h3>
                             <div class="card-tools">
                                {{-- Form Pencarian --}}
                                <form action="{{ route('admin.transaksi.history') }}" method="GET" class="input-group input-group-sm" style="width: 250px;">
                                    <input type="text" name="search" class="form-control float-right" placeholder="Cari No. Transaksi/Nama..." value="{{ $search ?? '' }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                        @if($search)
                                        <a href="{{ route('historyAdmin') }}" class="btn btn-warning" title="Reset Pencarian"><i class="fas fa-times"></i></a>
                                        @endif
                                    </div>
                                </form>
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
                                            <th>Nama Penyewa</th>
                                            <th class="d-none d-lg-table-cell">Tanggal Transaksi</th> {{-- Sembunyikan di layar kecil-menengah --}}
                                            <th class="d-none d-md-table-cell">Waktu Mulai</th> {{-- Sembunyikan di layar kecil --}}
                                            <th>Total Harga</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($history as $index => $transaksi)
                                            <tr>
                                                <td>{{ $history->firstItem() + $index }}</td>
                                                <td>{{ $transaksi->no_transaksi_212102 }}</td>
                                                <td>{{ $transaksi->user->name_212102 ?? 'N/A' }}</td>
                                                <td class="d-none d-lg-table-cell">{{ \Carbon\Carbon::parse($transaksi->start_time)->translatedFormat('d M Y') }}</td>
                                                <td class="d-none d-md-table-cell">{{ \Carbon\Carbon::parse($transaksi->start_time)->format('H:i') }}</td>
                                                <td>Rp {{ number_format($transaksi->total_price_212102 ?? 0, 0, ',', '.') }}</td>
                                                <td>
                                                    @if($transaksi->status_212102 == 1)
                                                        <span class="badge badge-warning">Menunggu</span>
                                                    @elseif($transaksi->status_212102 == 0)
                                                        <span class="badge badge-success">Diterima</span>
                                                    @else
                                                        <span class="badge badge-danger">Ditolak/Gagal</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.transaksi.show', $transaksi->id_212102) }}" class="btn btn-info btn-xs" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i> <span class="d-none d-md-inline">Detail</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">
                                                    @if($search)
                                                        Transaksi dengan kata kunci "<strong>{{ $search }}</strong>" tidak ditemukan.
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
                            {{ $history->links() }}
                        </div>
                    </div>
                    </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    {{-- Jika Anda menggunakan DataTables atau styling khusus untuk tabel --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"> --}}
    <style>
        /* Tambahan style untuk tombol aksi yang lebih kecil */
        .btn-xs {
            padding: .25rem .4rem;
            font-size: .875rem;
            line-height: .5;
            border-radius: .2rem;
        }
        /* Memastikan form search di card-tools rata kanan */
        .card-header .card-tools {
            margin-right: 0; /* Override default jika ada */
        }
    </style>
@endpush

@push('scripts')
    {{-- jQuery diasumsikan sudah ada dari layout utama --}}
    {{-- Jika Anda menggunakan DataTables client-side (tidak disarankan untuk server-side pagination search) --}}
    {{-- <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script> --}}
    <script>
        $(function () {
            // Jika Anda tidak menggunakan DataTables untuk pencarian (karena sudah server-side),
            // script di sini bisa kosong atau untuk fungsionalitas lain.
        });
    </script>
@endpush