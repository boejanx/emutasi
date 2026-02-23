<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\SystemLog;

class ManageUserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|integer|in:0,1,2,3,4',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        SystemLog::create([
            'id_user' => auth()->id() ?? 1,
            'action' => 'TAMBAH_USER',
            'description' => 'Menambahkan pengguna baru: ' . $user->name . ' (' . $user->email . ')',
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'System',
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|integer|in:0,1,2,3,4',
            'password' => 'nullable|string|min:8',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        SystemLog::create([
            'id_user' => auth()->id() ?? 1,
            'action' => 'UPDATE_USER',
            'description' => 'Memperbarui data pengguna: ' . $user->name . ' (' . $user->email . ')',
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'System',
        ]);

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;
        $email = $user->email;
        
        $user->delete();

        SystemLog::create([
            'id_user' => auth()->id() ?? 1,
            'action' => 'HAPUS_USER',
            'description' => 'Menghapus pengguna: ' . $name . ' (' . $email . ')',
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'System',
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
