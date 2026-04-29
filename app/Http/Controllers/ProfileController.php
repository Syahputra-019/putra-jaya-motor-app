<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $no_telp = '';

        // Ambil no_telp berdasarkan role
        if ($user->role === 'pelanggan') {
            $data = DB::table('pelanggans')->where('user_id', $user->id)->first();
            $no_telp = $data ? $data->no_telp : '';
        } elseif ($user->role === 'mekanik') {
            $data = DB::table('mekaniks')->where('user_id', $user->id)->first();
            $no_telp = $data ? $data->no_telp : '';
        }

        return view('profile.index', compact('user', 'no_telp'));
    }

    public function updateInfo(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:20048',
        ]);

        // 1. Update Nama di tabel users
        $user->name = $request->name;

        // 3. Proses Upload Foto (Jika ada file yang diunggah)
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika sebelumnya sudah punya foto
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Simpan foto baru ke dalam folder storage/app/public/profile_photos
            $path = $request->file('foto')->store('profile_photos', 'public');
            
            // Simpan path foto ke database
            $user->foto = $path;
        }
        
        $user->save();

        // 2. Update No Telp di tabel terkait
        if ($user->role === 'pelanggan') {
            DB::table('pelanggans')->updateOrInsert(
                ['user_id' => $user->id],
                ['no_telp' => $request->no_telp]
            );
        } elseif ($user->role === 'mekanik') {
            DB::table('mekaniks')->updateOrInsert(
                ['user_id' => $user->id],
                ['no_telp' => $request->no_telp]
            );
        }

        return back()->with('success', 'Profil dan nomor telepon berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah!']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah!');
    }
}