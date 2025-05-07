@extends('customer.layout.app')
@section('title', 'History')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Welcome {{ Auth::user()->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Customer</a></li>
                    <li class="breadcrumb-item active">History</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Nama Penyewa</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Waktu Pinjam</th>
                        <th scope="col">Harga Sewa</th>
                        <th scope="col">Total Jam</th>
                        <th scope="col">Total Harga</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($history as $h)
                    <tr>
                        <td>{{ $h->user->name_212102 ?? 'Tidak Diketahui' }}</td>
                        <td>{{ \Carbon\Carbon::parse($h->start_time)->format('d-m-Y') }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($h->start_time)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($h->end_time)->format('H:i') }}
                        </td>
                        <td>Rp. {{ number_format($h->price_212102, 2, ',', '.') }}</td>
                        <td>
                            @php
                                $startTime = \Carbon\Carbon::parse($h->start_time);
                                $endTime = $h->end_time ? \Carbon\Carbon::parse($h->end_time) : null;
                                $totalJam = $endTime ? $startTime->diffInHours($endTime) : 0;
                            @endphp
                            {{ $totalJam }} Jam
                        </td>
                        <td>Rp. {{ number_format($h->total_price_212102, 2, ',', '.') }}</td>
                        <td>
                            @switch($h->status)
                                @case(0)
                                    Menunggu
                                    @break
                                @case(1)
                                    Diterima
                                    @break
                                @default
                                    Gagal
                            @endswitch
                        </td>
                    </tr>
                    @empty
                    <tr class="text-center">
                        <td colspan="7">Tidak ada data!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $history->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</section>
@endsection

@section('js-source')
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
@endsection
