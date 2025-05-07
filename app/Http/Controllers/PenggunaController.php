<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
{
    $users = User::orderBy('name_212102')->paginate(10); // ✅ ini hasilkan LengthAwarePaginator
    return view('admin.pengguna.index', compact('users'));
}

    public function create()
    {
        return view('admin.pengguna.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_212102' => 'required|string|max:255',
            'email_212102' => 'required|email|unique:users_212102,email_212102',
            'telephone_212102' => 'required|string|max:15',
            'role_212102' => 'required|in:admin,user,customer',
            'password_212102' => 'required|string|min:6',
        ]);
    
        $user = new User;
        $user->name_212102 = $request->name_212102;
        $user->email_212102 = $request->email_212102;
        $user->telephone_212102 = $request->telephone_212102;
        $user->role_212102 = $request->role_212102;
        $user->password_212102 = Hash::make($request->password_212102);
        $user->save();
    
        return redirect()->route('pengguna.index')->with('success', 'Data pengguna berhasil ditambahkan.');
    }
    
    public function edit($id)
    {
        $user = User::where('id_212102', $id)->firstOrFail();
        return view('admin.pengguna.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id_212102', $id)->firstOrFail();

        $request->validate([
            'name_212102' => 'required',
            'telephone_212102' => 'required',
        ]);

        $user->update([
            'name_212102' => $request->name_212102,
            'telephone_212102' => $request->telephone_212102,
        ]);

        return redirect()->route('pengguna.index')->with('success', 'Data pengguna berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::where('id_212102', $id)->firstOrFail();
        $user->delete();
        return response()->json(['success' => 'Pengguna berhasil dihapus']);
    }
}