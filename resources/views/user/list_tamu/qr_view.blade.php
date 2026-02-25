<!DOCTYPE html>
<html>
<head>
    <title>Cetak QR Code Tamu</title>
    <style>
        body { font-family: sans-serif; }
        .grid-container {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .grid-item {
            display: table-cell;
            width: 33.33%; /* 3 Kolom per baris */
            text-align: center;
            padding: 20px;
            border: 1px dashed #ccc; /* Garis potong */
            vertical-align: middle;
        }
        .row { display: table-row; }
        .qr-img { width: 120px; height: 120px; margin-bottom: 10px; }
        .name { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
        .pax { font-size: 12px; color: #555; }
        .page-break { page-break-after: always; }
        
        /* Hilangkan margin default saat diprint */
        @media print {
            body { margin: 0; padding: 10px; }
        }
    </style>
</head>
<body onload="window.print()"> <!-- Otomatis memunculkan dialog print -->
    <div class="grid-container">
        @php $counter = 0; @endphp
        <div class="row">
        @foreach($guests as $guest)
            @if($counter > 0 && $counter % 3 == 0)
                </div><div class="row"> {{-- Ganti baris setiap 3 item --}}
            @endif
            
            <div class="grid-item">
                {{-- Data QR yang digenerate adalah NAMA TAMU, nantinya saat discan akan memunculkan nama ini --}}
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($guest->name) }}" class="qr-img">
                <div class="name">{{ $guest->name }}</div>
                <div class="pax">Kuota: {{ $guest->pax }} Orang</div>
            </div>

            @php $counter++; @endphp
            
            {{-- Logic Page Break (Misal 15 item per halaman) --}}
            @if($counter % 15 == 0 && !$loop->last)
                </div></div><div class="page-break"></div><div class="grid-container"><div class="row">
            @endif
        @endforeach
        </div>
    </div>
</body>
</html>