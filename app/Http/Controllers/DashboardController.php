<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Transaksi; // Pastikan model Transaksi Anda
use App\Models\MenuItem;  // Pastikan model MenuItem Anda
use App\Models\User;      // Jika Anda memiliki model User dan ingin menghitung pelanggan

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Kunci (KPI)
        $totalPendapatanHariIni = Transaksi::where('status_212102', 'selesai') // Asumsi 'selesai' adalah status transaksi sukses
            ->whereDate('created_at', Carbon::today())
            ->sum('total_price_212102');

        $totalPendapatanBulanIni = Transaksi::where('status_212102', 'selesai')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price_212102');

        $jumlahBookingPending = Transaksi::where('status_212102', 'pending')->count(); // Asumsi 'pending'

        $jumlahMenuItem = MenuItem::count();

        // Jika Anda punya model User dan kolom role untuk membedakan customer
        // $jumlahPelanggan = User::where('role_212102', 'customer')->count(); 
        // Atau, jika user_id_212102 di tabel transaksi merujuk ke pelanggan unik:
        $jumlahPelanggan = Transaksi::distinct('user_id_212102')->count('user_id_212102');


        // 2. Data untuk Grafik Pendapatan (Contoh: 7 hari terakhir)
        $salesLast7Days = Transaksi::where('status_212102', 'selesai')
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay()) // 6 hari lalu + hari ini = 7 hari
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price_212102) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $salesChartLabels = $salesLast7Days->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('D, M j'); // Format: Mon, Jun 1
        });
        $salesChartData = $salesLast7Days->pluck('total');

        // 3. Data untuk Grafik Status Transaksi (Pie Chart)
        $statusCounts = Transaksi::select('status_212102', DB::raw('count(*) as total'))
            ->groupBy('status_212102')
            ->pluck('total', 'status_212102');

        $statusChartLabels = $statusCounts->keys()->map(function ($status) {
            return ucfirst($status); // Misal: Pending, Selesai, Ditolak
        });
        $statusChartData = $statusCounts->values();
        $pieChartColors = [ // Anda bisa definisikan warna-warna yang menarik
            'Pending' => '#FFC107', // Kuning
            'Selesai' => '#28A745', // Hijau
            'Diterima' => '#17A2B8', // Info
            'Ditolak' => '#DC3545', // Merah
            'Kadaluarsa' => '#6C757D' // Abu-abu
            // Tambahkan status lain jika ada
        ];
        $statusChartColors = $statusChartLabels->map(function ($label) use ($pieChartColors) {
            return $pieChartColors[$label] ?? '#' . substr(md5(rand()), 0, 6); // Warna acak jika tidak terdefinisi
        });


        // 4. Transaksi Terbaru (misalnya 5 terakhir)
        $transaksiTerbaru = Transaksi::with(['user', 'item']) // Eager load relasi
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 5. Menu Item Terpopuler (Contoh: berdasarkan jumlah transaksi)
        // Asumsi kolom `item_id_212102` di tabel `transaksi_212102` merujuk ke `id_212102` di `menu_items_212102`
        $menuPopuler = MenuItem::select('menu_items_212102.name_212102', DB::raw('COUNT(transaksi_212102.id_212102) as jumlah_terjual'))
            ->join('transaksi_212102', 'menu_items_212102.id_212102', '=', 'transaksi_212102.item_id_212102')
            ->where('transaksi_212102.status_212102', 'selesai') // Hanya hitung yang sukses
            ->groupBy('menu_items_212102.id_212102', 'menu_items_212102.name_212102') // Group by ID juga untuk akurasi
            ->orderBy('jumlah_terjual', 'desc')
            ->take(5)
            ->get();


        return view('admin.home', compact( // Pastikan nama view Anda adalah admin.dashboard
            'totalPendapatanHariIni',
            'totalPendapatanBulanIni',
            'jumlahBookingPending',
            'jumlahMenuItem',
            'jumlahPelanggan',
            'salesChartLabels',
            'salesChartData',
            'statusChartLabels',
            'statusChartData',
            'statusChartColors',
            'transaksiTerbaru',
            'menuPopuler'
        ));
    }
}
