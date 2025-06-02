@extends('layout.app') {{-- Pastikan ini adalah layout admin yang benar --}}
@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Riwayat Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        {{-- Sesuaikan route('home') dengan route dashboard admin Anda, misal route('admin.dashboard') --}}
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
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
                            <h3 class="card-title">Daftar Semua Transaksi</h3>
                            <div class="card-tools">
                                {{-- Form Pencarian --}}
                                <form action="{{ route('admin.transaksi.history') }}" method="GET" class="input-group input-group-sm" style="width: 250px;">
                                    <input type="text" name="search" class="form-control float-right" placeholder="Cari No. Transaksi/Nama..." value="{{ $search ?? '' }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                        @if($search)
                                            {{-- Pastikan route 'historyAdmin' atau 'admin.transaksi.history' benar untuk reset --}}
                                            <a href="{{ route('admin.transaksi.history') }}" class="btn btn-warning" title="Reset Pencarian"><i class="fas fa-times"></i></a>
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
                                            <th class="d-none d-lg-table-cell">Tanggal Transaksi</th>
                                            <th class="d-none d-md-table-cell">Waktu Mulai</th>
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
                                                    <a href="{{ route('admin.transaksi.show', $transaksi->id_212102) }}" class="btn btn-info btn-xs mr-1" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i> <span class="d-none d-md-inline">Detail</span>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-xs" title="Hapus Transaksi"
                                                            data-toggle="modal" data-target="#deleteConfirmModal-{{ $transaksi->id_212102 }}">
                                                        <i class="fas fa-trash"></i> <span class="d-none d-md-inline">Hapus</span>
                                                    </button>
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

    {{-- Modal Konfirmasi Delete --}}
    @foreach ($history as $transaksi)
    <div class="modal fade" id="deleteConfirmModal-{{ $transaksi->id_212102 }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $transaksi->id_212102 }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
         
            <form method="POST" action="{{ route('transaksi.destroy', $transaksi->id_212102) }}">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel-{{ $transaksi->id_212102 }}">Konfirmasi Hapus Transaksi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Anda yakin ingin menghapus transaksi dengan nomor <strong>{{ $transaksi->no_transaksi_212102 }}</strong>?</p>
                        <p>Tindakan ini akan menghapus data secara permanen (jika tidak menggunakan soft delete) atau memindahkannya ke arsip (jika menggunakan soft delete).</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endforeach

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
            margin-right: 0;
        }
        .table td, .table th { /* Untuk memastikan tombol tidak terlalu mepet */
            vertical-align: middle;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function () {
            // Tidak ada script khusus yang diperlukan untuk modal delete ini
            // karena data-target sudah unik dan form action sudah benar.
        });
    </script>
@endpush