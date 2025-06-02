<?php

namespace App\Http\Controllers;

use App\Mail\TransaksiResult;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

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
            // ->whereDate('created_at', date('Y-m-d'))
            // ->where('status_212102', 1)
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

    public function updateStatus(Request $request)
    {
        // 1. Validasi data yang diterima dari request
        $validator = Validator::make($request->all(), [
            'id_212102'       => [
                'required',
                Rule::exists('transaksi_212102', 'id_212102')->where(function ($query) {
                    // Pastikan kita tidak mencoba mengupdate record yang sudah di-soft delete
                    // jika Anda tidak ingin hal itu terjadi.
                    return $query->whereNull('deleted_at');
                }),
            ],
            'status'          => 'required|in:0,1,2', // Sesuaikan nilai 'in' dengan value status yang valid
            'noted_212102'    => 'nullable|string|max:500', // Catatan opsional, maks 500 karakter
        ], [
            // Pesan kustom untuk validasi (opsional)
            'id_212102.required' => 'ID Transaksi wajib diisi.',
            'id_212102.exists'   => 'ID Transaksi tidak valid atau tidak ditemukan.',
            'status.required'    => 'Status wajib dipilih.',
            'status.in'          => 'Status yang dipilih tidak valid.',
            'noted_212102.max'   => 'Catatan tidak boleh lebih dari 500 karakter.',
        ]);

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan error dan input lama
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput(); // withInput() akan mengisi kembali form dengan data yang diinput user
        }

        // 2. Cari transaksi berdasarkan id_212102
        // Menggunakan findOrFail akan otomatis menghasilkan 404 jika tidak ditemukan
        try {
            $transaksi = Transaksi::findOrFail($request->input('id_212102'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Ini sebagai fallback jika Rule::exists tidak menangkap semua kasus (seharusnya sudah)
            // atau jika ada kondisi race condition.
            return redirect()->back()
                ->with('error', 'Transaksi tidak ditemukan.')
                ->withInput();
        }

        // 3. Update field pada model Transaksi
        $transaksi->status_212102 = $request->input('status');

        // Hanya update 'noted_212102' jika ada inputnya, jika tidak biarkan nilai lama atau set null jika diinginkan
        if ($request->filled('noted_212102')) { // filled() mengecek apakah ada dan tidak kosong
            $transaksi->noted_212102 = $request->input('noted_212102');
        } elseif ($request->has('noted_212102') && $request->input('noted_212102') === null) {
            // Jika field dikirim sebagai null (misalnya dari API atau textarea dikosongkan)
            $transaksi->noted_212102 = null;
        }
        // Jika 'noted_212102' tidak ada dalam request, field tersebut tidak akan diubah.

        // 4. Simpan perubahan
        try {
            $transaksi->save();
        } catch (\Exception $e) {
            // Tangani jika ada error saat menyimpan ke database
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status transaksi: ' . $e->getMessage())
                ->withInput();
        }

        // 5. Redirect ke halaman yang sesuai dengan pesan sukses
        // Anda bisa mengganti 'admin.transaksi.index' dengan route lain yang lebih sesuai,
        // misalnya halaman detail transaksi atau kembali ke halaman sebelumnya.
        return redirect()->back() // Ganti dengan nama route yang relevan
            ->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function destroy($transaksi_id_212102) // Nama parameter harus cocok dengan di route
    {
        try {
            $transaksi = Transaksi::where('id_212102', $transaksi_id_212102)->firstOrFail();

            // Tambahan: Kondisi sebelum menghapus (misalnya, hanya bisa dibatalkan jika status 'Menunggu')
            if ($transaksi->status_212102 == 0) { // 0 = Diterima
                return redirect()->back()->with('error', 'Booking yang sudah diterima tidak dapat dibatalkan.');
            }

            $transaksi->delete(); // Ini akan melakukan soft delete

            return redirect()->back() // Ganti dengan nama route halaman booking Anda
                ->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                ->with('error', 'Booking tidak ditemukan atau Anda tidak memiliki izin untuk membatalkannya.');
        } catch (\Exception $e) {
            // Tangani error umum lainnya
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membatalkan booking: ' . $e->getMessage());
        }
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

    public function history(Request $request) // Tambahkan Request $request
    {
        $search = $request->input('search');

        $query = Transaksi::with(['user', 'transaksi_details']) // Eager load relasi user
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_transaksi_212102', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        // Pastikan nama field di model User adalah 'name_212102'
                        $userQuery->where('name_212102', 'like', "%{$search}%");
                    })
                    // Anda bisa menambahkan pencarian berdasarkan status jika diperlukan:
                    // ->orWhere('status_212102', 'like', "%{$search}%") // Jika status dicari sebagai teks
                    // Atau jika status adalah angka dan ingin dicocokkan:
                    // ->orWhere(function($statusQuery) use ($search) {
                    //     if (strtolower($search) === 'menunggu') {
                    //         $statusQuery->where('status_212102', 1);
                    //     } elseif (strtolower($search) === 'diterima') {
                    //         $statusQuery->where('status_212102', 0);
                    //     } elseif (in_array(strtolower($search), ['ditolak', 'gagal'])) {
                    //         $statusQuery->where('status_212102', 2);
                    //     }
                    // })
                ;
            });
        }

        // Paginasi dan tambahkan query string pencarian ke link paginasi
        $history = $query->paginate(10)->appends(request()->query());

        return view('admin.transaksi.history', compact('history', 'search')); // Kirim 'search' ke view
    }
}
