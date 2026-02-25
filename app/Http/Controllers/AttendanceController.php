<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use App\Models\GuestLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Proses scan QR code untuk check-in tamu.
     * Kolom yang digunakan: name (dari QR), check_in_at, actual_pax, server_number
     */
    public function scan(Request $request)
    {
        try {
            // 1. Validasi input tidak boleh kosong
            $request->validate([
                'barcode_code' => 'required|string'
            ]);

            $keyword = trim($request->barcode_code);

            // 2. Cari tamu berdasarkan NAMA (karena QR menyimpan nama tamu)
            //    dan pastikan milik user yang login
            $guest = Guest::where('name', $keyword)
                          ->where('user_id', Auth::id())
                          ->first();

            // Fallback: cari dengan LIKE jika exact match tidak ditemukan
            if (!$guest) {
                $guest = Guest::where('name', 'like', '%' . $keyword . '%')
                              ->where('user_id', Auth::id())
                              ->first();
            }

            // 3. Jika tamu ditemukan
            if ($guest) {
                // Cek apakah tamu sudah pernah scan sebelumnya
                if ($guest->check_in_at) {
                    return response()->json([
                        'status'  => 'warning',
                        'message' => 'Tamu a.n ' . $guest->name . ' sudah check-in sebelumnya pada ' .
                                     Carbon::parse($guest->check_in_at)->timezone('Asia/Jakarta')->format('H:i:s d/m/Y')
                    ]);
                }

                // Update waktu kehadiran dan catat log
                $guest->update([
                    'check_in_at'   => Carbon::now(),
                    'actual_pax'    => $guest->pax,
                    'server_number' => 1,
                ]);

                GuestLog::create([
                    'guest_id'   => $guest->id,
                    'pax'        => $guest->pax,
                    'activity'   => 'attendance',
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Selamat Datang, ' . $guest->name . '! (' . $guest->pax . ' pax)'
                ]);
            }

            // 4. Jika tamu TIDAK ditemukan
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tamu "' . $keyword . '" tidak ditemukan.'
            ], 404);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Input tidak valid: ' . implode(' ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}