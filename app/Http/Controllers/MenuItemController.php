<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::with(['menu'])->paginate(15);
        return view('admin.menuitem.index', compact('menuItems'));
    }

    public function create()
    {
        $menus = Menu::where('status_212102', 0)->get();
        return view('admin.menuitem.tambah', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_id_212102' => 'required|integer',
            'name_212102' => 'required|string',
            'price_212102' => 'required|numeric',
            'status_212102' => 'required|in:0,1',
        ]);

        MenuItem::create([
            'menu_id_212102' => $request->menu_id_212102,
            'name_212102' => strtolower(trim($request->name_212102)),
            'price_212102' => $request->price_212102,
            'status_212102' => $request->status_212102,
        ]);

        return redirect()->route('menu-item.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $menus = Menu::where('status_212102', 0)->get();
        $menuItem = MenuItem::where('id_212102', $id)->firstOrFail(); // <-- ini disesuaikan
        return view('admin.menuitem.edit', compact('menus', 'menuItem'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'menu_id_212102' => 'required|integer',
            'name_212102' => 'required|string',
            'price_212102' => 'required|numeric',
            'status_212102' => 'required|in:0,1',
        ]);

        $menuItem = MenuItem::where('id_212102', $id)->firstOrFail(); // <-- ini juga disesuaikan
        $menuItem->update([
            'menu_id_212102' => $request->menu_id_212102,
            'name_212102' => strtolower(trim($request->name_212102)),
            'price_212102' => $request->price_212102,
            'status_212102' => $request->status_212102,
        ]);

        return redirect()->route('menu-item.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $menuItem = MenuItem::where('id_212102', $id)->firstOrFail(); // <-- disesuaikan
        $menuItem->delete();

        return response()->json([
            'success' => 'Berhasil delete data!'
        ]);
    }
}
