@extends('layout.app')
@section('title', 'Booking Saya') {{-- Judul lebih sesuai untuk customer --}}

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Transaksi, {{ Auth::user()->name_212102 }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li> {{-- Sesuaikan route dashboard customer --}}
                    <li class="breadcrumb-item active">Booking Saya</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
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

        <div class="row">
            @forelse ($transaksis as $transaksi)
            <div class="card shadow-lg col-md-5 mb-3 mr-3">
                <div class="card-header">
                    <strong>Nomor Transaksi:</strong> {{ $transaksi->no_transaksi_212102 }} <br>
                    <strong>Status:</strong>
                    <span>
                        @if($transaksi->status_212102 == 1)
                            <span class="badge badge-warning">Menunggu ⏳</span>
                        @elseif($transaksi->status_212102 == 0)
                            <span class="badge badge-success">Diterima ✅</span>
                        @else
                            <span class="badge badge-danger">Gagal/Ditolak ❌</span>
                        @endif
                    </span>
                </div>
                <div class="card-body">
                    @php
                        $startTime = \Carbon\Carbon::parse($transaksi->start_time);
                        $endTime = $transaksi->end_time ? \Carbon\Carbon::parse($transaksi->end_time) : null;
                        $totalJam = $endTime ? $startTime->diffInHours($endTime) : 0;
                    @endphp
                    <div class="row mb-2">
                        <div class="col-md-5">Nama Penyewa</div>
                        <div class="col-md-6">: {{ $transaksi->user->name_212102 ?? 'Tidak tersedia' }}</div>

                        <div class="col-md-5">Harga Sewa/Jam</div>
                        <div class="col-md-6">:
                            Rp  {{ number_format($transaksi->price_212102 ?? 0, 2, ',', '.') }}
                        </div>

                        <div class="col-md-5">Tanggal</div>
                        <div class="col-md-6">:
                            {{ $startTime->translatedFormat('d F Y') }} {{-- Format tanggal lebih baik --}}
                        </div>

                        <div class="col-md-5">Waktu Mulai</div>
                        <div class="col-md-6">:
                            {{ $startTime->format('H:i') }}
                        </div>

                        <div class="col-md-5">Waktu Selesai</div>
                        <div class="col-md-6">:
                            {{ $endTime ? $endTime->format('H:i') : 'N/A' }}
                        </div>

                        <div class="col-md-5">Total Jam</div>
                        <div class="col-md-6">:
                            {{ $totalJam }} jam
                        </div>

                        <div class="col-md-5">Catatan</div>
                        <div class="col-md-6">:
                            {{ $transaksi->noted_212102 ?? '-' }}
                        </div>

                        <div class="col-md-5"><strong>Total Harga</strong></div>
                        <div class="col-md-6">:
                            <strong>Rp {{ number_format($transaksi->total_price_212102 ?? 0, 2, ',', '.') }}</strong>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="mt-3">
                        <button class="btn btn-sm btn-primary mr-1"
                                data-toggle="modal"
                                data-target="#statusModal-{{ $transaksi->id_212102 }}" {{-- ID Modal unik per item --}}
                                data-id="{{ $transaksi->id_212102 }}"
                                data-current-status="{{ $transaksi->status_212102 }}"
                                data-current-noted="{{ $transaksi->noted_212102 ?? '' }}">
                            <i class="fas fa-edit"></i> Ubah Status
                        </button>

                        {{-- Tombol Delete hanya muncul jika status bukan 'Diterima' (misalnya) --}}
                        {{-- Anda bisa sesuaikan kondisi ini --}}
                        @if($transaksi->status_212102 != 0)
                        <button class="btn btn-sm btn-danger"
                                data-toggle="modal"
                                data-target="#deleteConfirmModal-{{ $transaksi->id_212102 }}"> {{-- ID Modal unik per item --}}
                            <i class="fas fa-trash"></i> Batalkan Booking
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Modal Ubah Status untuk setiap transaksi --}}
            <div class="modal fade" id="statusModal-{{ $transaksi->id_212102 }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel-{{ $transaksi->id_212102 }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="statusForm-{{ $transaksi->id_212102 }}" method="POST" action="{{ route('admin.transaksi.updateStatus') }}"> {{-- Sesuaikan route jika ini halaman customer --}}
                        @csrf
                        @method('POST') {{-- Metode PUT untuk update --}}

                        <input type="hidden" name="id_212102" value="{{ $transaksi->id_212102 }}">

                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="statusModalLabel-{{ $transaksi->id_212102 }}">Ubah Status Transaksi: {{ $transaksi->no_transaksi_212102 }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="status_select_modal-{{ $transaksi->id_212102 }}">Status</label>
                                    <select name="status" id="status_select_modal-{{ $transaksi->id_212102 }}" class="form-control" required>
                                        <option value="" disabled>Pilih Status</option>
                                        <option value="0" {{ old('status', $transaksi->status_212102) == '0' ? 'selected' : '' }}>Diterima</option>
                                        <option value="1" {{ old('status', $transaksi->status_212102) == '1' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="2" {{ old('status', $transaksi->status_212102) == '2' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                    @error('status', 'statusFormBag_' . $transaksi->id_212102) {{-- Error bag spesifik --}}
                                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="noted_modal-{{ $transaksi->id_212102 }}">Catatan (Opsional)</label>
                                    <textarea name="noted_212102" id="noted_modal-{{ $transaksi->id_212102 }}" class="form-control">{{ old('noted_212102', $transaksi->noted_212102 ?? '') }}</textarea>
                                    @error('noted_212102', 'statusFormBag_' . $transaksi->id_212102) {{-- Error bag spesifik --}}
                                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Modal Konfirmasi Delete untuk setiap transaksi --}}
            <div class="modal fade" id="deleteConfirmModal-{{ $transaksi->id_212102 }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $transaksi->id_212102 }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST" action="{{ route('transaksi.destroy', $transaksi->id_212102) }}"> {{-- Sesuaikan route --}}
                        @csrf
                        @method('DELETE')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel-{{ $transaksi->id_212102 }}">Konfirmasi Pembatalan Booking</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Anda yakin ingin membatalkan booking dengan nomor transaksi <strong>{{ $transaksi->no_transaksi_212102 }}</strong>?</p>
                                <p>Tindakan ini tidak dapat diurungkan.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <p>Anda belum memiliki data booking.</p>
                    <a href="{{ route('customer.booking.create') }}" class="btn btn-primary mt-2">Buat Booking Baru</a> {{-- Sesuaikan route --}}
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Script untuk modal ubah status (jika masih diperlukan untuk mengisi data dinamis,
    // namun dengan ID modal unik dan data diisi server-side, ini mungkin tidak terlalu krusial lagi
    // kecuali jika Anda ingin reset form atau perilaku dinamis lainnya)

    // Contoh: Jika Anda ingin memastikan modal ubah status direset saat ditutup
    $('[id^="statusModal-"]').on('hidden.bs.modal', function () {
        // Anda bisa menambahkan logika reset form di sini jika diperlukan
        // $(this).find('form')[0].reset();
        // $(this).find('.invalid-feedback').remove();
    });

    // Tidak perlu script khusus untuk mengisi ID ke modal delete karena ID sudah ada di action form modal
    // dan data-target pada tombol sudah unik.
});
</script>
@endpush