<?php

namespace App\Http\Controllers;

use App\Mail\TransaksiResult;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TransaksiController extends Controller
{

    public function __construct()
    {
        $this->middleware(['admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaksis = Transaksi::with(['transaksi_details', 'user'])
            ->whereDate('created_at', date('Y-m-d'))
            ->where('status_212102', 1)
            ->get();
        return view('admin.transaksi.booking', compact('transaksis'));
    }

    public function storeAccept(Request $request)
    {
        $menu_id = $request->menu_id;
        $transaksi = Transaksi::findOrFail($request->id);
        $qrcode = QrCode::size(150)->generate(route('detail', $request->id));
        $transaksi->status = 1;
        $transaksi->save();
        Mail::to($transaksi->user->email)->send(new TransaksiResult($transaksi->user, $transaksi, $qrcode));
        $getTransaksiAll = Transaksi::whereTime('start_time', date('H:i:s', strtotime($transaksi->start_time)))
            ->where('status', 0)
            ->whereHas('transaksi_details', function ($q) use ($menu_id) {
                $q->where('menuitem_id', $menu_id);
            })
            ->get();

        foreach ($getTransaksiAll as $tReject) {
            $tReject->status = 2;
            $tReject->save();
            Mail::to($tReject->user->email)->send(new TransaksiResult($tReject->user, $tReject, $qrcode));
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil di update'
        ]);
    }

    public function storeReject(Request $request)
    {
        $transaksi = Transaksi::with('user')->findOrFail($request->id);
        $qrcode = QrCode::size(150)->generate(route('detail', $request->id));
        $transaksi->status = 2;
        $transaksi->save();
        Mail::to($transaksi->user->email)->send(new TransaksiResult($transaksi->user, $transaksi, $qrcode));
        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil di update'
        ]);
    }

    public function history()
    {
        $history = Transaksi::with(['transaksi_details'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.transaksi.history', compact('history'));
    }
}
