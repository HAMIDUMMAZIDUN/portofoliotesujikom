<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>QR Code Tamu - PDF</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        .page-title {
            text-align: center;
            padding: 15px 0 10px;
            font-size: 16px;
            font-weight: bold;
            border-bottom: 2px solid #333;
            margin-bottom: 15px;
        }
        table { width: 100%; border-collapse: collapse; }
        td {
            width: 33.33%;
            text-align: center;
            padding: 15px 10px;
            border: 1px dashed #ccc;
            vertical-align: middle;
        }
        .qr-img { width: 100px; height: 100px; margin-bottom: 8px; }
        .name {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
            word-break: break-word;
        }
        .pax { font-size: 10px; color: #555; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="page-title">Daftar QR Code Tamu</div>

    <table>
        @php
            $items = count($guests);
            $cols  = 3;
            $perPage = 15;
            $counter = 0;
        @endphp

        <tr>
        @foreach($guests as $guest)
            @php $counter++; @endphp

            <td>
                {{-- QR Code dari google API --}}
                <img
                    src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode($guest->name) }}"
                    class="qr-img"
                    alt="QR {{ $guest->name }}"
                >
                <div class="name">{{ $guest->name }}</div>
                <div class="pax">Kuota: {{ $guest->pax }} Pax</div>
            </td>

            {{-- Tutup baris setiap 3 kolom --}}
            @if($counter % $cols === 0 && !$loop->last)
                </tr>
                @if($counter % $perPage === 0)
                    </table>
                    <div class="page-break"></div>
                    <table><tr>
                @else
                    <tr>
                @endif
            @endif

        @endforeach

        {{-- Isi sel kosong jika sisa kolom tidak penuh --}}
        @php $remaining = $counter % $cols; @endphp
        @if($remaining > 0)
            @for($i = $remaining; $i < $cols; $i++)
                <td></td>
            @endfor
        @endif
        </tr>
    </table>
</body>
</html>
