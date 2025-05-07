<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        // Menampilkan data menu dengan paginasi 15 item per halaman
        $menus = Menu::paginate(15);
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menu.tambah');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'menu' => 'required|unique:menus_212102,name_212102|max:255',
        ]);

        // Menyimpan data menu baru
        $menu = new Menu;
        $menu->name_212102 = trim($request->menu);
        $menu->status_212102 = (int) $request->status;
        $menu->save();

        return redirect()->route('menu.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        // Ambil menu berdasarkan id_212102
        $menu = Menu::where('id_212102', $id)->firstOrFail();

        // Ubah nama properti agar lebih mudah digunakan di blade
        $menu->name = $menu->name_212102;
        $menu->status = $menu->status_212102;

        return view('admin.menu.edit', compact('menu'));
    }

    public function update($id, Request $request)
    {
        // Validasi input
        $request->validate([
            'menu' => 'required|max:255',
        ]);

        // Update data menu
        $menu = Menu::where('id_212102', $id)->firstOrFail();
        $menu->name_212102 = trim($request->menu);
        $menu->status_212102 = (int) $request->status;
        $menu->save();

        return redirect()->route('menu.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        // Hapus data menu
        $menu = Menu::where('id_212102', $id)->firstOrFail();
        $menu->delete();

        return response()->json([
            'success' => 'Berhasil delete data!'
        ]);
    }
}
