<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\GuestLog;
use Illuminate\Http\Request;
use App\Exports\GuestsExport;
use App\Imports\GuestsImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Guest::where('user_id', Auth::id());

            // Filter by name (search)
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Filter by specific date (YYYY-MM-DD)
            if ($request->filled('filter_date')) {
                $query->whereDate('created_at', $request->filter_date);
            }

            // Filter by month (1-12)
            if ($request->filled('filter_month')) {
                $query->whereMonth('created_at', $request->filter_month);
            }

            // Filter by year (e.g. 2025)
            if ($request->filled('filter_year')) {
                $query->whereYear('created_at', $request->filter_year);
            }

            $guests = $query->orderBy('created_at', 'desc')->get();

            // Get available years for the year filter dropdown
            $availableYears = Guest::where('user_id', Auth::id())
                ->selectRaw('YEAR(created_at) as year')
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->pluck('year');

            return view('user.list_tamu.index', compact('guests', 'availableYears'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat daftar tamu: ' . $e->getMessage());
        }
    }

    public function ajaxSearch(Request $request)
    {
        try {
            $query = $request->get('query');
            if (!$query) return response()->json([]);

            $guests = Guest::where('user_id', Auth::id())
                            ->where('name', 'like', '%' . $query . '%')
                            ->limit(100)
                            ->get(['id', 'name', 'pax', 'actual_pax', 'souvenir_pax', 'check_in_at']);

            return response()->json($guests);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mencari tamu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getHistory($id)
    {
        try {
            $guest = Guest::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

            $logs = GuestLog::where('guest_id', $guest->id)
                            ->orderBy('created_at', 'desc')
                            ->get()
                            ->map(function ($log) {
                                $type = $log->activity == 'souvenir' ? '(Souvenir)' : '(Masuk)';
                                return [
                                    'id'   => $log->id,
                                    'pax'  => $log->pax . ' ' . $type,
                                    'time' => Carbon::parse($log->created_at)->timezone('Asia/Jakarta')->format('H:i:s'),
                                    'date' => Carbon::parse($log->created_at)->timezone('Asia/Jakarta')->format('d M Y')
                                ];
                            });

            return response()->json($logs);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tamu tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memuat riwayat: ' . $e->getMessage()
            ], 500);
        }
    }

    // Fungsi lama untuk cetak PDF (bisa dibiarkan jika masih dipakai)
    public function exportQrPdf(Request $request)
    {
        try {
            $request->validate(['ids' => 'required']);
            $ids    = explode(',', $request->ids);
            $guests = Guest::where('user_id', Auth::id())->whereIn('id', $ids)->get();

            $pdf = Pdf::loadView('user.list_tamu.qr_pdf', compact('guests'));
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions(['isRemoteEnabled' => true]);

            return $pdf->download('qr_code_tamu.pdf');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor QR PDF: ' . $e->getMessage());
        }
    }

    // FUNGSI BARU: Untuk menampilkan halaman cetak QR dari checkbox
    public function bulkQr(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|string'
            ]);

            // Ubah string "1,2,3" menjadi array [1, 2, 3]
            $idsArray = explode(',', $request->ids);

            // Ambil data tamu berdasarkan ID yang dicentang dan milik user yang login
            $guests = Guest::where('user_id', Auth::id())
                           ->whereIn('id', $idsArray)
                           ->get();

            // Tampilkan ke view qr_view
            return view('user.list_tamu.qr_view', compact('guests'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat halaman QR: ' . $e->getMessage());
        }
    }

    public function bulkDestroyGuests(Request $request)
    {
        $request->validate([
            'ids' => 'required|string'
        ]);

        try {
            $idsArray = explode(',', $request->ids);

            $guests = Guest::whereIn('id', $idsArray)
                           ->where('user_id', Auth::id())
                           ->get();

            $count = $guests->count();

            foreach ($guests as $guest) {
                $guest->delete();
            }

            return back()->with('success', $count . " data tamu berhasil dihapus secara permanen.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data tamu: ' . $e->getMessage());
        }
    }

    public function server1(Request $request)
    {
        try {
            if ($request->has('search') && $request->filled('search')) {
                $keyword = $request->search;
                $guest   = Guest::where('user_id', Auth::id())->where('name', $keyword)->first();

                if (!$guest) {
                    $guest = Guest::where('user_id', Auth::id())->where('name', 'like', '%' . $keyword . '%')->first();
                }

                if ($guest) {
                    return redirect()->route('server1')->with('open_checkin_modal_id', $guest->id);
                } else {
                    return redirect()->route('server1')
                        ->with('error', "GAGAL: Tamu '{$keyword}' TIDAK DITEMUKAN.")
                        ->with('not_found_name', $keyword);
                }
            }

            $logsQuery = GuestLog::with('guest')
                ->whereHas('guest', function ($q) {
                    $q->where('user_id', Auth::id());
                })
                ->where('activity', 'attendance');

            // Filter by specific date
            if ($request->filled('filter_date')) {
                $logsQuery->whereDate('created_at', $request->filter_date);
            }
            // Filter by month
            if ($request->filled('filter_month')) {
                $logsQuery->whereMonth('created_at', $request->filter_month);
            }
            // Filter by year
            if ($request->filled('filter_year')) {
                $logsQuery->whereYear('created_at', $request->filter_year);
            }

            $logs = $logsQuery->orderBy('created_at', 'desc')->limit(200)->get();

            // Available years for year filter dropdown
            $availableYears = GuestLog::whereHas('guest', function ($q) {
                    $q->where('user_id', Auth::id());
                })
                ->where('activity', 'attendance')
                ->selectRaw('YEAR(created_at) as year')
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->pluck('year');

            $triggerGuest = null;
            if (session('open_checkin_modal_id')) {
                $triggerGuest = Guest::find(session('open_checkin_modal_id'));
            }

            return view('user.server_1.index', compact('logs', 'triggerGuest', 'availableYears'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat halaman server 1: ' . $e->getMessage());
        }
    }

    public function processCheckinServer1(Request $request)
    {
        try {
            $request->validate([
                'guest_id' => 'required|exists:guests,id',
                'pax_in'   => 'required|integer|min:1',
                'photo'    => 'nullable|image|max:5120',
            ]);

            $guest    = Guest::where('user_id', Auth::id())->where('id', $request->guest_id)->firstOrFail();
            $paxMasuk = (int) $request->pax_in;
            $newTotal = ($guest->actual_pax ?? 0) + $paxMasuk;

            if ($request->hasFile('photo')) {
                if ($guest->photo_path && file_exists(public_path('uploads/guests/' . $guest->photo_path))) {
                    @unlink(public_path('uploads/guests/' . $guest->photo_path));
                }
                $file     = $request->file('photo');
                $filename = time() . '_' . $guest->id . '.' . $file->getClientOriginalExtension();
                if (!file_exists(public_path('uploads/guests'))) {
                    mkdir(public_path('uploads/guests'), 0777, true);
                }
                $file->move(public_path('uploads/guests'), $filename);
                $guest->photo_path = $filename;
            }

            $guest->server_number      = 1;
            $guest->check_in_at        = Carbon::now();
            $guest->is_physical_invited = true;
            $guest->actual_pax         = $newTotal;
            $guest->save();

            GuestLog::create([
                'guest_id'   => $guest->id,
                'pax'        => $paxMasuk,
                'activity'   => 'attendance',
                'created_at' => Carbon::now()
            ]);

            $message = "BERHASIL: {$guest->name} check-in {$paxMasuk} orang.";
            if ($newTotal > $guest->pax) {
                $message .= " (Status: Melebihi Kuota)";
            }

            return redirect()->route('server1')->with('success', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Data tamu tidak ditemukan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan check-in: ' . $e->getMessage());
        }
    }

    public function souvenir(Request $request)
    {
        try {
            if ($request->has('guest_id') && $request->filled('guest_id')) {
                $guest        = Guest::where('user_id', Auth::id())->where('id', $request->guest_id)->firstOrFail();
                $paxAmbil     = (int) $request->input('pax_in', 1);
                $newTotalSouvenir = ($guest->souvenir_pax ?? 0) + $paxAmbil;

                $guest->update([
                    'souvenir_pax' => $newTotalSouvenir,
                    'check_in_at'  => Carbon::now(),
                    'server_number' => 2
                ]);

                GuestLog::create([
                    'guest_id'   => $guest->id,
                    'pax'        => $paxAmbil,
                    'activity'   => 'souvenir',
                    'created_at' => Carbon::now()
                ]);

                $message = "BERHASIL: {$guest->name} mengambil {$paxAmbil} souvenir.";
                if ($newTotalSouvenir > $guest->pax) {
                    $message .= " (Peringatan: Melebihi Jatah)";
                }

                return redirect()->route('souvenir')->with('success', $message);
            }

            if ($request->has('search') && $request->filled('search')) {
                $keyword = $request->search;
                $guest   = Guest::where('user_id', Auth::id())->where('name', 'like', '%' . $keyword . '%')->first();
                if ($guest) return redirect()->route('souvenir', ['guest_id' => $guest->id, 'pax_in' => 1]);
                return back()->with('error', "Tamu '{$keyword}' tidak ditemukan.")->with('not_found_name', $keyword);
            }

            $logsQuery = GuestLog::with('guest')
                ->whereHas('guest', function ($q) {
                    $q->where('user_id', Auth::id());
                })
                ->where('activity', 'souvenir');

            // Filter by specific date
            if ($request->filled('filter_date')) {
                $logsQuery->whereDate('created_at', $request->filter_date);
            }
            // Filter by month
            if ($request->filled('filter_month')) {
                $logsQuery->whereMonth('created_at', $request->filter_month);
            }
            // Filter by year
            if ($request->filled('filter_year')) {
                $logsQuery->whereYear('created_at', $request->filter_year);
            }

            $logs = $logsQuery->orderBy('created_at', 'desc')->limit(200)->get();

            // Available years for year filter dropdown
            $availableYears = GuestLog::whereHas('guest', function ($q) {
                    $q->where('user_id', Auth::id());
                })
                ->where('activity', 'souvenir')
                ->selectRaw('YEAR(created_at) as year')
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->pluck('year');

            return view('user.souvenir.index', compact('logs', 'availableYears'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Data tamu tidak ditemukan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat halaman souvenir: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'         => 'required|string|max:255',
                'pax_online'   => 'required|integer|min:0',
                'pax_physical' => 'required|integer|min:0',
            ]);

            $totalPax = (int) $request->pax_online + (int) $request->pax_physical;
            if ($totalPax == 0) $totalPax = 1;

            $guest = Guest::create([
                'user_id'             => Auth::id(),
                'name'                => $request->name,
                'pax'                 => $totalPax,
                'pax_online'          => (int) $request->pax_online,
                'pax_physical'        => (int) $request->pax_physical,
                'actual_pax'          => 0,
                'souvenir_pax'        => 0,
                'is_online_invited'   => $request->pax_online > 0,
                'is_physical_invited' => $request->pax_physical > 0,
            ]);

            if ($request->input('source') === 'souvenir' || $request->input('from_souvenir') == 1) {
                $paxSouvenir = (int) $request->input('pax_physical', 1);
                $guest->update(['souvenir_pax' => $paxSouvenir, 'check_in_at' => Carbon::now(), 'server_number' => 2]);
                GuestLog::create(['guest_id' => $guest->id, 'pax' => $paxSouvenir, 'activity' => 'souvenir']);
                return redirect()->route('souvenir')->with('success', "Tamu '{$guest->name}' ditambahkan & Souvenir diserahkan.");
            }

            if ($request->input('source') === 'server1') {
                $guest->update(['actual_pax' => $totalPax, 'check_in_at' => Carbon::now(), 'server_number' => 1]);
                GuestLog::create(['guest_id' => $guest->id, 'pax' => $totalPax, 'activity' => 'attendance']);
                return redirect()->route('server1')->with('success', "Tamu '{$guest->name}' ditambahkan & Check-in.");
            }

            return redirect()->route('guests.index')->with('success', "Tamu '{$guest->name}' berhasil didaftarkan.");
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan tamu: ' . $e->getMessage());
        }
    }

    public function attendance(Request $request)
    {
        try {
            $allGuestsQuery         = Guest::where('user_id', Auth::id());
            $total_invitation_entered = (clone $allGuestsQuery)->where('actual_pax', '>', 0)->count();
            $total_people_entered   = (clone $allGuestsQuery)->sum('actual_pax');
            $total_souvenir_taken   = (clone $allGuestsQuery)->sum('souvenir_pax');
            $grand_total_activity   = $total_people_entered + $total_souvenir_taken;

            $query = Guest::where('user_id', Auth::id())->where('actual_pax', '>', 0);
            if ($request->has('search') && $request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
            $guests = $query->orderBy('check_in_at', 'desc')->get();

            return view('user.list_tamu_hadir.index', compact(
                'guests',
                'total_invitation_entered',
                'total_people_entered',
                'total_souvenir_taken',
                'grand_total_activity'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data kehadiran: ' . $e->getMessage());
        }
    }

    public function updateLog(Request $request)
    {
        try {
            $request->validate(['log_id' => 'required|exists:guest_logs,id', 'pax' => 'required|integer|min:1']);
            $log    = GuestLog::findOrFail($request->log_id);
            $guest  = Guest::findOrFail($log->guest_id);
            $selisih = $request->pax - $log->pax;

            if ($log->activity == 'souvenir') {
                $guest->increment('souvenir_pax', $selisih);
            } else {
                $guest->increment('actual_pax', $selisih);
            }

            $log->update(['pax' => $request->pax]);
            return back()->with('success', "Data riwayat berhasil diperbarui.");
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui riwayat: ' . $e->getMessage());
        }
    }

    public function destroyLog(Request $request)
    {
        try {
            $log   = GuestLog::findOrFail($request->log_id);
            $guest = Guest::findOrFail($log->guest_id);

            if ($log->activity == 'souvenir') {
                $guest->decrement('souvenir_pax', $log->pax);
            } else {
                $guest->decrement('actual_pax', $log->pax);
            }

            $log->delete();
            return back()->with('success', "Riwayat berhasil dihapus.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Data riwayat tidak ditemukan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus riwayat: ' . $e->getMessage());
        }
    }

    public function bulkDestroy(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array']);
            $logs = GuestLog::whereIn('id', $request->ids)->get();

            foreach ($logs as $log) {
                $guest = Guest::find($log->guest_id);
                if ($guest) {
                    if ($log->activity == 'souvenir') {
                        $guest->decrement('souvenir_pax', $log->pax);
                    } else {
                        $guest->decrement('actual_pax', $log->pax);
                    }
                }
                $log->delete();
            }

            return back()->with('success', count($request->ids) . " riwayat berhasil dihapus.");
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus riwayat: ' . $e->getMessage());
        }
    }

    public function exportPdf()
    {
        try {
            $guests = \App\Models\Guest::where('user_id', auth()->id())
                        ->whereNotNull('check_in_at')
                        ->orderBy('check_in_at', 'desc')
                        ->get();

            $total_invitation_entered = $guests->count();
            $total_people_entered     = $guests->sum('actual_pax');
            $total_souvenir_taken     = $guests->sum('souvenir_pax');
            $grand_total_activity     = $total_invitation_entered + $total_people_entered + $total_souvenir_taken;

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.list_tamu_hadir.pdf', [
                'guests'                  => $guests,
                'total_invitation_entered' => $total_invitation_entered,
                'total_people_entered'    => $total_people_entered,
                'total_souvenir_taken'    => $total_souvenir_taken,
                'grand_total_activity'    => $grand_total_activity,
            ]);

            $pdf->setPaper('a4', 'portrait');

            return $pdf->download('Rekap_Kehadiran_Tamu.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Guest $guest)
    {
        try {
            $data = $request->all();

            if ($request->has('pax_online') && $request->has('pax_physical')) {
                $pax_online  = (int) $request->pax_online;
                $pax_physical = (int) $request->pax_physical;

                $data['pax']                 = $pax_online + $pax_physical;
                $data['pax_online']          = $pax_online;
                $data['pax_physical']        = $pax_physical;
                $data['is_online_invited']   = $pax_online > 0;
                $data['is_physical_invited'] = $pax_physical > 0;

                if ($data['pax'] == 0) $data['pax'] = 1;
            }

            $guest->update($data);
            return back()->with('success', "Data tamu '{$guest->name}' berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui data tamu: ' . $e->getMessage());
        }
    }

    public function destroy(Guest $guest)
    {
        try {
            $guest->delete();
            return back()->with('success', "Data tamu berhasil dihapus.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus tamu: ' . $e->getMessage());
        }
    }

    public function export()
    {
        try {
            return Excel::download(new GuestsExport, 'daftar_tamu.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor data ke Excel: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv'
            ]);
            Excel::import(new GuestsImport, $request->file('file'));
            return back()->with('success', 'Data tamu berhasil diimpor.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = 'Gagal import, ada data yang tidak valid: ';
            foreach ($failures as $failure) {
                $errorMsg .= 'Baris ' . $failure->row() . ' - ' . implode(', ', $failure->errors()) . '. ';
            }
            return back()->with('error', $errorMsg);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor data tamu: ' . $e->getMessage());
        }
    }

    /**
     * Upload foto tamu secara manual dari halaman list tamu.
     */
    public function uploadPhoto(Request $request)
    {
        try {
            $request->validate([
                'guest_id' => 'required|exists:guests,id',
                'photo'    => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);

            $guest = Guest::where('user_id', Auth::id())
                          ->where('id', $request->guest_id)
                          ->firstOrFail();

            // Hapus foto lama jika ada
            if ($guest->photo_path && file_exists(public_path('uploads/guests/' . $guest->photo_path))) {
                @unlink(public_path('uploads/guests/' . $guest->photo_path));
            }

            // Simpan foto baru
            $file     = $request->file('photo');
            $filename = time() . '_' . $guest->id . '.' . $file->getClientOriginalExtension();
            $dir      = public_path('uploads/guests');

            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            $file->move($dir, $filename);
            $guest->update(['photo_path' => $filename]);

            return back()->with('success', "Foto tamu '{$guest->name}' berhasil diupload.");
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Data tamu tidak ditemukan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal upload foto: ' . $e->getMessage());
        }
    }
}