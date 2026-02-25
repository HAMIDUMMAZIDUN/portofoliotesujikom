<!DOCTYPE html>
<html>
<head>
    <title>Rekap Kehadiran & Souvenir</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.2cm;
        }
        body { font-family: sans-serif; font-size: 10px; line-height: 1.3; color: #000; }
        
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #000; padding-bottom: 8px; }
        .title-text { margin: 0; font-size: 16px; text-transform: uppercase; font-weight: bold; }
        .subtitle-text { margin: 2px 0 0 0; font-size: 8px; color: #2563eb; font-weight: bold; }
        .generated-text { margin-top: 4px; font-size: 8px; color: #666; }

        .info-box { margin-bottom: 15px; background-color: #f3f4f6; border: 1px solid #000; padding: 8px; }
        .info-row { margin-bottom: 2px; }
        .label { font-weight: bold; display: inline-block; width: 120px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #000; padding: 6px 8px; }
        th { background-color: #000; color: #fff; text-transform: uppercase; font-size: 9px; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* Summary Styling */
        .summary-header th { background-color: #0000FF; color: white; border: 1px solid #000; }
        .bg-gray { background-color: #e5e7eb; }
        .text-blue { color: #0000FF; }
        .text-green { color: #15803d; }
        .text-purple { color: #7e22ce; }

        /* Detail Table Styling */
        .detail-table tbody tr { background-color: #d1e7dd; } /* Sesuai warna di web image */
        .detail-table tbody tr:nth-child(even) { background-color: #c3ddd2; }
        
        .photo-circle { width: 28px; height: 28px; border-radius: 50%; border: 0.5px solid #000; }
        .badge-kehadiran { 
            background-color: #d1e7dd; 
            color: #15803d; 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 7px; 
            padding: 2px 4px;
            border: 0.5px solid #15803d;
            border-radius: 3px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title-text">DIGITAL GUESTBOOK BY BIRU ID</h1>
        <p class="subtitle-text">INSTAGRAM: @BYBIRU.ID | WHATSAPP: 0895-2621-6334</p>
        <p class="generated-text">Laporan diunduh pada: {{ now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') }} WIB</p>
    </div>

    <div class="info-box">
        <div class="info-row">
            <span class="label">NAMA PENGANTIN</span>: 
            <span style="text-transform: uppercase; font-weight: bold;">{{ Auth::user()->name }}</span>
        </div>
        <div class="info-row">
            <span class="label">TANGGAL & LOKASI</span>: 
            <span style="text-transform: uppercase;">
                {{ Auth::user()->event_date ?? '1 DESEMBER 2025' }} / {{ Auth::user()->event_location ?? 'BANDUNG' }}
            </span>
        </div>
    </div>

    {{-- TABEL RINGKASAN (Sesuai Image 3) --}}
    <table>
        <thead>
            <tr class="summary-header">
                <th style="width: 70%; text-align: left;">KETERANGAN AKTIVITAS</th>
                <th style="width: 30%;">TOTAL (UNIT/ORANG/PCS)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-bold">Jumlah Undangan Masuk (QR Scanned)</td>
                <td class="text-center font-bold text-blue" style="font-size: 12px;">{{ $total_invitation_entered }}</td>
            </tr>
            <tr>
                <td class="font-bold">Jumlah Orang Masuk (Total Pax)</td>
                <td class="text-center font-bold text-green" style="font-size: 12px;">{{ $total_people_entered }}</td>
            </tr>
            <tr>
                <td class="font-bold">Jumlah Souvenir yang Diambil</td>
                <td class="text-center font-bold text-purple" style="font-size: 12px;">{{ $total_souvenir_taken }}</td>
            </tr>
            <tr class="bg-gray">
                <td class="font-bold uppercase">GRAND TOTAL AKTIVITAS</td>
                <td class="text-center font-bold" style="font-size: 12px;">{{ $grand_total_activity }}</td>
            </tr>
        </tbody>
    </table>

    <h3 style="text-transform: uppercase; font-size: 10px; margin-bottom: 6px; font-weight: bold;">Detail Riwayat Scan Kehadiran</h3>
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 10%;">Foto</th>
                <th style="width: 40%; text-align: left;">Nama Lengkap</th>
                <th style="width: 15%;">Jenis Scan</th>
                <th style="width: 10%;">Pax</th>
                <th style="width: 20%;">Waktu Scan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($guests as $guest)
                {{-- SINKRONISASI: Hanya tampilkan jika actual_pax > 0 sesuai tampilan web --}}
                @if(($guest->actual_pax ?? 0) > 0)
                    @php
                        $photoPath = $guest->photo_path ? public_path('uploads/guests/' . $guest->photo_path) : null;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="text-center">
                            @if($photoPath && file_exists($photoPath))
                                <img src="{{ $photoPath }}" class="photo-circle">
                            @else
                                -
                            @endif
                        </td>
                        <td style="text-transform: uppercase; font-weight: bold;">{{ $guest->name }}</td>
                        <td class="text-center">
                            <span class="badge-kehadiran">Kehadiran</span>
                        </td>
                        <td class="text-center font-bold">{{ $guest->actual_pax }}</td>
                        <td class="text-center" style="font-size: 9px; font-family: monospace;">
                            {{ \Carbon\Carbon::parse($guest->check_in_at)->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') }}
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; background-color: #fff;">Belum ada data scan kehadiran.</td>
                </tr>
            @endforelse
            
            {{-- Pesan jika setelah difilter ternyata kosong --}}
            @if($no == 1 && count($guests) > 0)
                 <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; background-color: #fff;">Tidak ada tamu dengan status masuk.</td>
                </tr>
            @endif
        </tbody>
    </table>

</body>
</html>