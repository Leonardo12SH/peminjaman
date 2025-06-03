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
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

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
    // 1. Definisikan Aturan Validasi
    // Nama field di sini harus sesuai dengan atribut 'name' pada input HTML Anda
    $rules = [
      'item_id_212102'         => 'required|integer|exists:menu_items_212102,id_212102',
      'price'                  => 'required|numeric|min:0',
      'total_price'            => 'required|numeric|min:0',
      'start_time'             => 'required|date_format:Y-m-d\TH:i:s.v\Z',
      'end_time'               => 'required|date_format:Y-m-d\TH:i:s.v\Z|after:start_time',
      'noted_212102'           => 'nullable|string|max:1000',
      'tanggal_mulai_212102'   => 'required|date',
      'jam_mulai_212102'       => 'required',
      'tanggal_selesai_212102' => 'required|date|after_or_equal:tanggal_mulai_212102',
      'jam_selesai_212102'     => 'required',
    ];

    // 2. Definisikan Pesan Error Kustom
    $messages = [
      'item_id_212102.required' => 'Item harus dipilih.',
      'price.required'          => 'Harga per jam (dari kalkulasi) tidak boleh kosong.',
      'price.numeric'           => 'Harga per jam harus berupa angka.',
      'price.min'               => 'Harga per jam tidak boleh negatif.',
      'total_price.required'    => 'Total harga (dari kalkulasi) tidak boleh kosong.',
      'total_price.numeric'     => 'Total harga harus berupa angka.',
      'total_price.min'         => 'Total harga tidak boleh negatif.',
      'start_time.required'     => 'Waktu mulai (ISO) hasil kalkulasi tidak boleh kosong.',
      'start_time.date_format'  => 'Format waktu mulai (ISO) tidak valid.',
      'end_time.required'       => 'Waktu selesai (ISO) hasil kalkulasi tidak boleh kosong.',
      'end_time.date_format'    => 'Format waktu selesai (ISO) tidak valid.',
      'end_time.after'          => 'Waktu selesai harus setelah waktu mulai.',
      'noted_212102.string'     => 'Catatan harus berupa teks.',
      'noted_212102.max'        => 'Catatan maksimal 1000 karakter.',
      'tanggal_mulai_212102.required' => 'Tanggal mulai harus diisi.',
      'jam_mulai_212102.required'     => 'Jam mulai harus diisi.',
      'tanggal_selesai_212102.required' => 'Tanggal selesai harus diisi.',
      'tanggal_selesai_212102.after_or_equal' => 'Tanggal selesai harus pada atau setelah tanggal mulai.',
      'jam_selesai_212102.required'     => 'Jam selesai harus diisi.',
    ];

    // 3. Lakukan Validasi
    $validator = Validator::make($request->all(), $rules, $messages);

    // 4. Jika Validasi Gagal
    if ($validator->fails()) {
      return redirect()->back()
        ->withErrors($validator) // Kirim error validasi kembali
        ->withInput();            // Kirim input sebelumnya kembali untuk repopulate form
    }

    // 5. Jika Validasi Berhasil, ambil data yang tervalidasi
    $validatedData = $validator->validated();

    // 6. Proses Penyimpanan Data
    try {
      $transaksi = new Transaksi();
      $transaksi->user_id_212102 = Auth::id(); // Pastikan user sudah login
      $transaksi->item_id_212102 = $validatedData['item_id_212102'];
      $transaksi->no_transaksi_212102 = 'TRX-' . strtoupper(Str::random(10));

      // Menggunakan nama kolom database yang benar
      // $request->price dikirim dari HTML dengan name="price"
      // Kolom database adalah price_212102
      $transaksi->price_212102 = $validatedData['price'];
      $transaksi->total_price_212102 = $validatedData['total_price']; // Asumsi kolom DB adalah total_price_212102

      $transaksi->noted_212102 = $validatedData['noted_212102'] ?? null; // Jika nullable dan tidak ada, set null

      // Kolom start_time dan end_time di DB (sesuai kode Anda sebelumnya)
      $transaksi->start_time = $validatedData['start_time'];
      $transaksi->end_time = $validatedData['end_time'];

      $transaksi->status_212102 = 1; // Status default, misal: 1 = 'Pending' atau 'Booked'

      $transaksi->save();

      return redirect()->route('customer')->with('success', 'Transaksi berhasil disimpan!');
    } catch (QueryException $e) {
      // Log::error('Kesalahan Database saat menyimpan transaksi: ' . $e->getMessage()); // Uncomment untuk logging
      return redirect()->back()
        ->with('error', 'Terjadi kesalahan pada database saat menyimpan transaksi. Silakan coba lagi.')
        ->withInput();
    } catch (\Exception $e) {
      // Log::error('Kesalahan Umum saat menyimpan transaksi: ' . $e->getMessage()); // Uncomment untuk logging
      return redirect()->back()
        ->with('error', 'Terjadi kesalahan umum. Silakan coba lagi atau hubungi administrator.')
        ->withInput();
    }
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
    $user = Auth::user();
    $transaksi = Transaksi::with([
      'user', // Relasi ke model User
      'item', // Relasi ke item utama yang dibooking (jika ada)
      'transaksi_details', // Relasi ke model TransaksiDetail
      'transaksi_details.menuItem' // Asumsi TransaksiDetail punya relasi ke MenuItem
    ])
      ->where('user_id_212102', $user->id_212102) // Filter berdasarkan user yang login
      ->where('id_212102', $id) // Atau nama primary key Anda jika bukan 'id_212102'
      ->firstOrFail(); // Gunakan firstOrFail untuk error 404 jika tidak ditemukan atau bukan milik user

    return view('customer.transaksi.detail', compact('transaksi'));
  }

  public function history(Request $request)
  {
    $search = $request->input('search');
    $user = Auth::user(); // 1. Mendapatkan informasi user yang sedang login

    // 2. Memulai query ke tabel Transaksi
    $query = Transaksi::with(['user']) // Eager load relasi user jika diperlukan
      // 3. FILTER UTAMA: Hanya ambil transaksi di mana user_id_212102 SAMA DENGAN ID user yang login
      ->where('user_id_212102', $user->id_212102);

    // Jika ada input pencarian, tambahkan kondisi WHERE ke query
    if ($search) {
      $query->where(function ($subQuery) use ($search) {
        // Pencarian hanya akan dilakukan pada data user yang sudah terfilter di atas
        $subQuery->where('no_transaksi_212102', 'like', '%' . $search . '%');
        // Tambahkan field lain yang ingin dicari milik user ini jika perlu
      });
    }

    // Urutkan dan lakukan paginasi
    $history = $query->orderBy('created_at', 'desc')
      ->paginate(10);

    // Tambahkan parameter pencarian ke link paginasi
    if ($search) {
      $history->appends(['search' => $search]);
    }

    return view('customer.transaksi.history', compact('history', 'search'));
  }
}
