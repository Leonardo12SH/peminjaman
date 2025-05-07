<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionController extends Controller
{
  public function index()
  {
    $items = MenuItem::where('status_212102', 0)->get(); // Menampilkan item yang tersedia
    return view('customer.transaksi.index', compact('items'));
  }

  public function transaksi(Request $request)
  {
    $request->validate([
      'item_id_212102' => 'required|integer|exists:menu_items_212102,id_212102',
      'tanggal' => 'required|date',
    ]);

    $itemId = $request->input('item_id_212102');
    $tanggal = $request->input('tanggal');

    // Mengecek apakah item sudah dipesan pada tanggal dan waktu tertentu
    $booked = TransaksiDetail::whereHas('transaksi', function ($query) use ($itemId, $tanggal) {
      $query->where('menuitem_id', $itemId)
        ->whereDate('start_time', $tanggal)
        ->where('status_212102', 1);
    })->pluck('jam');

    return response()->json([
      'status' => true,
      'booked' => $booked,
    ]);
  }

  public function store(Request $request)
  {
    // $request->validate([
    //   'item_id_212102' => 'required|integer|exists:menu_items_212102,id_212102',
    //   'tanggal' => 'required|date',
    //   'jam' => 'required|date_format:H:i',
    //   'jam_selesai' => 'required|date_format:H:i|after:jam',
    //   'noted_212102' => 'nullable|string',
    //   'price' => 'required|numeric',
    //   'total_price' => 'required|numeric',
    //   'start_time' => 'required|date',
    //   'end_time' => 'required|date',
    // ]);

    // try {
    $transaksi = new Transaksi();
    $transaksi->user_id_212102 = Auth::id();
    $transaksi->item_id_212102 = $request->item_id_212102;
    $transaksi->no_transaksi_212102 = 'TRX-' . strtoupper(Str::random(10));
    $transaksi->price_212102 = $request->price;
    $transaksi->total_price_212102 = $request->total_price;
    $transaksi->noted_212102 = $request->noted_212102;
    $transaksi->start_time = $request->start_time;
    $transaksi->end_time = $request->end_time;
    $transaksi->status_212102 = 1;
    $transaksi->save();

    // // Tambahkan detail transaksi (opsional, tergantung desain DB Anda)
    // $detail = new TransaksiDetail();
    // $detail->transaksi_id_212102 = $transaksi->id_212102;
    // $detail->start_time = Carbon::parse($request->start_time)->format('H:i');
    // $detail->save();

    return redirect()->route('customer')->with('success', 'Transaksi berhasil disimpan!');
    // } catch (\Exception $e) {
    //   return redirect()->route('customer')->with('error', 'Terjadi kesalahan pada server: ' . $e->getMessage());
    // }
  }



  public function booking()
  {
    $user = Auth::user();
    $transaksis = Transaksi::with(['transaksi_details'])
      ->where('user_id_212102', $user->id_212102)
      ->get();

    return view('customer.transaksi.booking', compact('transaksis', 'user'));
  }

  public function detail($id)
  {
    $transaksi = Transaksi::with('user', 'transaksi_details', 'transaksi_details.menuitem')->findOrFail($id);
    return view('customer.transaksi.detail', compact('transaksi'));
  }

  public function history(Request $request)
  {
    $user = Auth::user();
    $history = Transaksi::with(['transaksi_details'])
      ->where('user_id_212102', $user->id_212102)
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('customer.transaksi.history', compact('history'));
  }
}
