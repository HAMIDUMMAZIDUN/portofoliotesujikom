<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function index()
    {
        try {
            $users = User::orderBy('created_at', 'desc')->get();
            $guestsWithPhotos = Guest::whereNotNull('photo_path')->orderBy('check_in_at', 'desc')->get();

            return view('admin.dashboard', compact('users', 'guestsWithPhotos'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat halaman dashboard: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role'     => ['required', 'in:admin,user'],
            ]);

            User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Akun user berhasil dibuat!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e; // Biarkan Laravel menangani validasi error otomatis
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal membuat akun user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            if ($user->id === Auth::id()) {
                return back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
            }

            $user->delete();

            return back()->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    public function cleanupPhotos()
    {
        try {
            $path = public_path('uploads/guests');

            if (File::exists($path)) {
                File::cleanDirectory($path);
            }

            Guest::whereNotNull('photo_path')->update(['photo_path' => null]);

            return back()->with('success', 'Semua foto berhasil dibersihkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membersihkan foto: ' . $e->getMessage());
        }
    }

    public function deleteSelectedPhotos(Request $request)
    {
        try {
            $request->validate([
                'guest_ids'   => 'required|array',
                'guest_ids.*' => 'exists:guests,id'
            ]);

            $guests = Guest::whereIn('id', $request->guest_ids)->get();

            foreach ($guests as $guest) {
                if ($guest->photo_path) {
                    $filePath = public_path('uploads/guests/' . $guest->photo_path);
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                    $guest->update(['photo_path' => null]);
                }
            }

            return back()->with('success', count($request->guest_ids) . ' foto berhasil dihapus.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus foto yang dipilih: ' . $e->getMessage());
        }
    }
}