@extends('layout.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 id="greeting" class="m-0 text-dark">Selamat Datang, {{ Auth::user()->name_212102 ?? Auth::user()->name ?? 'Admin' }}!</h1>
                <p class="text-muted" id="current-date-time"></p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalPendapatanHariIni ?? 0, 0, ',', '.') }}</h3>
                        <p>Pendapatan Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-cash"></i>
                    </div>
                    <a href="{{ route('admin.transaksi.history') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalPendapatanBulanIni ?? 0, 0, ',', '.') }}</h3>
                        <p>Pendapatan Bulan Ini</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('admin.transaksi.history') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $jumlahBookingPending ?? 0 }}</h3>
                        <p>Booking Pending</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-load-a"></i> {{-- Atau ion-alert-circled --}}
                    </div>
                    <a href="{{ route('booking.user') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $jumlahMenuItem ?? 0 }}</h3>
                        <p>Jumlah Item Menu</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pizza"></i> {{-- Atau ion-coffee, ion-fork, ion-knife --}}
                    </div>
                    <a href="{{ route('menu-item.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            {{-- Anda bisa menambahkan $jumlahPelanggan jika sudah diimplementasikan
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $jumlahPelanggan ?? 0 }}</h3>
                        <p>Total Pelanggan</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-stalker"></i>
                    </div>
                    <a href="{{ route('pengguna.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
             --}}
        </div>
        <div class="row">
            <section class="col-lg-7 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-1"></i>
                            Pendapatan 7 Hari Terakhir
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="salesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list-alt mr-1"></i>
                            5 Transaksi Terbaru
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.transaksi.history') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Pelanggan</th>
                                    <th>Item</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksiTerbaru as $transaksi)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.transaksi.show', ['transaksi_id_212102' => $transaksi->id_212102]) }}">
                                            {{ $transaksi->no_transaksi_212102 }}
                                        </a>
                                    </td>
                                    <td>{{ $transaksi->user->name_212102 ?? ($transaksi->user->name ?? 'N/A') }}</td>
                                    <td>{{ $transaksi->item->name_212102 ?? 'N/A' }}</td>
                                    <td>Rp {{ number_format($transaksi->total_price_212102, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $badgeClass = 'secondary';
                                            if ($transaksi->status_212102 == 'pending') $badgeClass = 'warning';
                                            elseif ($transaksi->status_212102 == 'selesai') $badgeClass = 'success';
                                            elseif ($transaksi->status_212102 == 'diterima') $badgeClass = 'info';
                                            elseif ($transaksi->status_212102 == 'ditolak') $badgeClass = 'danger';
                                        @endphp
                                        <span class="badge badge-{{ $badgeClass }}">{{ ucfirst($transaksi->status_212102) }}</span>
                                    </td>
                                    <td>{{ $transaksi->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada transaksi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                </section>
            <section class="col-lg-5 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-1"></i>
                            Status Transaksi
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="transactionStatusPieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-fire mr-1"></i>
                            Menu Terpopuler
                        </h3>
                         <div class="card-tools">
                            <a href="{{ route('menu-item.index') }}" class="btn btn-sm btn-info">Lihat Semua Menu</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse ($menuPopuler as $item)
                            <li class="list-group-item">
                                {{ $item->name_212102 }}
                                <span class="badge badge-primary float-right">{{ $item->jumlah_terjual }} terjual</span>
                            </li>
                            @empty
                            <li class="list-group-item text-center">Data menu populer belum tersedia.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                </section>
            </div>
        </div></section>
@endsection

@push('styles')
<style>
    .content-header h1 {
        font-size: 1.8rem; /* Sedikit lebih besar untuk judul utama */
        font-weight: 600;
    }
    .breadcrumb {
        background-color: transparent;
    }
    #greeting {
        color: #333;
    }
    .small-box .icon {
        font-size: 70px; /* Icon lebih kecil agar tidak terlalu dominan */
        top: 10px;
    }
    .small-box h3 {
        font-size: 2.0rem; /* Ukuran angka KPI */
    }
    .card-title {
        font-weight: 600; /* Judul card lebih tebal */
    }
</style>
{{-- Jika Anda menggunakan ionicons, pastikan sudah ter-include di layout utama atau tambahkan CDN-nya --}}
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush

@section('js-source')
{{-- Chart.js sudah Anda sertakan --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
{{-- File JavaScript khusus dashboard Anda tetap bisa digunakan untuk fungsi lain jika ada --}}
{{-- <script src="{{ asset('src/dashboard.js') }}"></script> --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Script salam dan tanggal-waktu dinamis Anda yang sudah ada
    const greetingElement = document.getElementById('greeting');
    const now = new Date();
    const hours = now.getHours();
    let greetingText = "Selamat Datang";

    if (hours < 4) { greetingText = "Selamat Dini Hari"; }
    else if (hours < 11) { greetingText = "Selamat Pagi"; }
    else if (hours < 15) { greetingText = "Selamat Siang"; }
    else if (hours < 19) { greetingText = "Selamat Sore"; }
    else { greetingText = "Selamat Malam"; }

    if(greetingElement) {
        const userName = "{{ Auth::user()->name_212102 ?? Auth::user()->name ?? 'Admin' }}";
        greetingElement.textContent = `${greetingText}, ${userName}! 👋`;
    }

    const dateTimeElement = document.getElementById('current-date-time');
    function updateDateTime() {
        const currentDate = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        if(dateTimeElement) {
            dateTimeElement.textContent = currentDate.toLocaleDateString('id-ID', options);
        }
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // Data dari Controller untuk Charts
    const salesChartLabels = @json($salesChartLabels ?? []);
    const salesChartData = @json($salesChartData ?? []);
    const statusChartLabels = @json($statusChartLabels ?? []);
    const statusChartData = @json($statusChartData ?? []);
    const statusChartColors = ["#007BFF", "#FFC107", "#28A745", "#DC3545", "#6C757D"];


    // Inisialisasi Sales Chart (Line Chart)
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        new Chart(salesCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: salesChartLabels,
                datasets: [{
                    label: 'Pendapatan',
                    data: salesChartData,
                    backgroundColor: 'rgba(40, 167, 69, 0.2)', // Area fill color (mis: success)
                    borderColor: 'rgba(40, 167, 69, 1)',     // Line color
                    borderWidth: 2,
                    tension: 0.3, // Membuat garis sedikit melengkung
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // Inisialisasi Transaction Status Pie Chart
    const pieCtx = document.getElementById('transactionStatusPieChart');
    if (pieCtx && statusChartData.length > 0) { // Hanya render jika ada data
        new Chart(pieCtx.getContext('2d'), {
            type: 'pie', // atau 'doughnut'
            data: {
                labels: statusChartLabels,
                datasets: [{
                    label: 'Status Transaksi',
                    data: statusChartData,
                    backgroundColor: statusChartColors, // Warna dari controller
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom', // Posisi legenda
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    } else if(pieCtx) {
        pieCtx.getContext('2d').font = "16px Arial";
        pieCtx.getContext('2d').fillStyle = "#888";
        pieCtx.getContext('2d').textAlign = "center";
        pieCtx.getContext('2d').fillText("Belum ada data status transaksi.", pieCtx.width / 2, pieCtx.height / 2);
    }

});
</script>
@endsection