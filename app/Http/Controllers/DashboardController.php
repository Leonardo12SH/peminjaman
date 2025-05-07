<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Transaksi;
use App\Models\MenuItem;

class DashboardController extends Controller
{
    //
    public function card()
    {
        $total = Transaksi::count();
        $sucsess = Transaksi::where('status', 1)->count();
        $failed = Transaksi::where('status', 2)->orWhere('status', '=', 0)->count();
        $omset = DB::table('transaksi')
                    ->selectRaw('SUM(total_price) as total')
                    ->where('status', '=', 1)
                    ->get();

        return response()->json([
            'status' => true,
            'code'   => 200,
            'total'   =>$total,
            'success' => $sucsess,
            'failed'  => $failed,
            'omset'   => $omset
        ]);
    }

    public function MenuItem()
    {
        $data = MenuItem::get();

        return response()->json([
            'status' => true,
            'code'   => 200,
            'data'   =>$data
        ]);
    }

    public function week(Request $request)
    {
        // $minggu = Transaksi::whereBetWeen('created_at', [Carbon::now('Asia/Jakarta')->startOfWeek()->format('Y-m-d'), Carbon::now('Asia/Jakarta')->endOfWeek()->format('Y-m-d')])
        //             ->get();
        // $minggu = Transaksi::where('created_at', '>', '2022-01-01')
        //                 ->where('created_at', '<', Carbon::now()->endOfWeek())
        //                 ->get();
        
        $date = Carbon::now('Asia/Jakarta')->daysInMonth;

        for ($i = 0; $i <= $date; $i++) {
            $perTanggal[$i] = Transaksi::whereYear('created_at', $request->year)
                ->whereMonth('created_at', $request->month)
                ->whereDay('created_at', "$i")
                ->count();
        }
                    
        return response()->json([
            'status' => true,
            'code'   => 200,
            'data'   =>$perTanggal
        ]);
    }

    public function month(Request $request)
    {
        $month = Transaksi::select(DB::raw('MONTH(created_at) as monthKey'), DB::raw('count(*) as total'))
                    ->whereYear('created_at', $request->year)
                    ->groupBy('monthKey')
                    ->get();
                    
        return response()->json([
            'status' => true,
            'code'   => 200,
            'data'   =>$month
        ]);
    }
}