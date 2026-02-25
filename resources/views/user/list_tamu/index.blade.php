<x-app-layout title="List Tamu">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">{{ __('List Tamu') }}</h2>
    </x-slot>

    <div class="min-h-screen font-sans" x-data="{ qrModalOpen: false, qrGuestName: '', qrImageSrc: '' }">

        {{-- HERO --}}
        <div class="relative overflow-hidden rounded-2xl mb-6" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6)">
            <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 20% 50%,white 1px,transparent 1px);background-size:40px 40px"></div>
            <div class="relative px-5 py-6 flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
                        </div>
                        <span class="text-white/80 text-xs font-semibold uppercase tracking-widest">Daftar Tamu</span>
                    </div>
                    <h1 class="text-2xl font-black text-white">DIGITAL GUESTBOOK</h1>
                    <p class="text-blue-200 text-sm font-medium mt-0.5 uppercase">{{ Auth::user()->name }}
                        @if(Auth::user()->event_date) · {{ Auth::user()->event_date }}@endif
                        @if(Auth::user()->event_location) · {{ Auth::user()->event_location }}@endif
                    </p>
                </div>
                <div class="hidden sm:flex w-16 h-16 rounded-2xl bg-white/15 border border-white/20 items-center justify-center">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
        </div>

        {{-- FLASH --}}
        @if(session('success'))
        <div class="mb-4 flex items-center gap-3 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl" x-data x-init="setTimeout(()=>$el.remove(),5000)">
            <div class="w-7 h-7 rounded-full bg-emerald-100 dark:bg-emerald-800 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>
            <p class="text-sm font-semibold">{{ session('success') }}</p>
        </div>
        @endif
        @if(session('error'))
        <div class="mb-4 flex items-center gap-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded-xl">
            <div class="w-7 h-7 rounded-full bg-red-100 dark:bg-red-800 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></div>
            <p class="text-sm font-semibold">{{ session('error') }}</p>
        </div>
        @endif

        {{-- TOOLBAR --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4 mb-4">

            {{-- ROW 1: Search + Filter Tanggal --}}
            <form method="GET" action="{{ route('guests.index') }}" id="filter-form">
                <div class="flex flex-col gap-3">

                    {{-- Baris Atas: Search + Tombol Aksi --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex gap-2 flex-1 min-w-0">
                            <div class="relative flex-1 min-w-0">
                                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                                <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition" placeholder="Cari nama tamu..." autofocus autocomplete="off">
                            </div>
                            <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition whitespace-nowrap active:scale-95">Cari</button>
                            @if(request()->hasAny(['search','filter_date','filter_month','filter_year']))
                                <a href="{{ route('guests.index') }}" class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl transition whitespace-nowrap flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Reset
                                </a>
                            @endif
                        </div>
                        <div id="bulk-actions-group" class="hidden gap-2 items-center flex-shrink-0">
                            <button type="button" onclick="submitBulkAction('qr')" class="flex items-center gap-1.5 px-3 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3"/></svg> QR (<span class="selected-count-display">0</span>)
                            </button>
                            <button type="button" onclick="submitBulkAction('delete')" class="flex items-center gap-1.5 px-3 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> Hapus (<span class="selected-count-display">0</span>)
                            </button>
                        </div>
                    </div>

                    {{-- Baris Filter Tanggal --}}
                    <div class="flex flex-wrap gap-2 items-center p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                        <div class="flex items-center gap-1.5 text-blue-600 dark:text-blue-400">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs font-bold uppercase tracking-wide whitespace-nowrap">Filter Tanggal:</span>
                        </div>

                        {{-- Filter Tanggal Spesifik --}}
                        <div class="flex-1 min-w-[150px]">
                            <input type="date" name="filter_date" value="{{ request('filter_date') }}"
                                   class="w-full text-sm bg-white dark:bg-gray-800 border border-blue-200 dark:border-blue-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                                   title="Filter berdasarkan tanggal spesifik">
                        </div>

                        <span class="text-xs text-gray-400 dark:text-gray-500 font-semibold">atau</span>

                        {{-- Filter Bulan --}}
                        <div class="flex-1 min-w-[130px]">
                            <select name="filter_month"
                                    class="w-full text-sm bg-white dark:bg-gray-800 border border-blue-200 dark:border-blue-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                                    onchange="this.form.submit()">
                                <option value="">Semua Bulan</option>
                                @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $num => $name)
                                    <option value="{{ $num }}" {{ request('filter_month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Tahun --}}
                        <div class="flex-1 min-w-[100px]">
                            <select name="filter_year"
                                    class="w-full text-sm bg-white dark:bg-gray-800 border border-blue-200 dark:border-blue-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                                    onchange="this.form.submit()">
                                <option value="">Semua Tahun</option>
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" {{ request('filter_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                                {{-- Fallback jika tidak ada data --}}
                                @if($availableYears->isEmpty())
                                    <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                @endif
                            </select>
                        </div>

                        <button type="submit" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition active:scale-95 whitespace-nowrap">
                            Terapkan
                        </button>
                    </div>

                </div>
            </form>
            {{-- Tombol Aksi Kanan --}}
            <div class="flex gap-2 mt-3 justify-end">
                <button x-data="" x-on:click.prevent="$dispatch('open-modal','manual-input-modal')" class="flex items-center gap-1.5 px-3 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition whitespace-nowrap active:scale-95"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>Input</button>
                <a href="{{ route('guests.export') }}" class="flex items-center gap-1.5 px-3 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition whitespace-nowrap"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>Ekspor</a>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal','import-guest-modal')" class="flex items-center gap-1.5 px-3 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl transition whitespace-nowrap"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>Impor</button>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between border-b border-gray-100 dark:border-gray-700" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6)">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg></div>
                    <div>
                        <h2 class="text-white font-bold text-sm uppercase tracking-wide">Daftar Tamu Undangan</h2>
                        @if(request()->hasAny(['filter_date','filter_month','filter_year']))
                        <p class="text-blue-200 text-xs mt-0.5">
                            Filter aktif:
                            @if(request('filter_date')) Tgl {{ \Carbon\Carbon::parse(request('filter_date'))->format('d/m/Y') }}@endif
                            @if(request('filter_month')) Bln {{ ['01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des'][request('filter_month')] ?? '' }}@endif
                            @if(request('filter_year')) Thn {{ request('filter_year') }}@endif
                        </p>
                        @endif
                    </div>
                </div>
                <span class="text-xs font-bold text-blue-100 bg-white/20 px-3 py-1 rounded-full">{{ $guests->count() }} data</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="w-10 py-3 px-3 text-center"><input type="checkbox" id="select-all" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 cursor-pointer"></th>
                            <th class="py-3 px-3 text-center text-xs font-bold text-gray-400 uppercase">No</th>
                            <th class="py-3 px-4 text-left text-xs font-bold text-gray-400 uppercase">Nama Tamu</th>
                            <th class="py-3 px-3 text-center text-xs font-bold text-blue-500 uppercase">Online</th>
                            <th class="py-3 px-3 text-center text-xs font-bold text-emerald-500 uppercase">Fisik</th>
                            <th class="py-3 px-3 text-center text-xs font-bold text-gray-400 uppercase">Total</th>
                            <th class="py-3 px-4 text-left text-xs font-bold text-amber-500 uppercase whitespace-nowrap">Ditambahkan</th>
                            <th class="py-3 px-3 text-center text-xs font-bold text-gray-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($guests as $index => $guest)
                        <tr class="hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-colors">
                            <td class="py-3 px-3 text-center"><input type="checkbox" value="{{ $guest->id }}" class="checkbox-item w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 cursor-pointer"></td>
                            <td class="py-3 px-3 text-center"><span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400 text-xs font-bold">{{ $index + 1 }}</span></td>
                            <td class="py-3 px-4"><p class="text-sm font-bold text-gray-900 dark:text-white uppercase">@if(request('search')){!! preg_replace('/('.preg_quote(request('search'),'/').')/i','<mark class="bg-yellow-200 rounded px-0.5">$1</mark>',$guest->name) !!}@else{{ $guest->name }}@endif</p></td>
                            <td class="py-3 px-3 text-center">@if(($guest->pax_online??0)>0)<span class="bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400 text-sm font-black px-2.5 py-1 rounded-full">{{ $guest->pax_online }}</span>@else<span class="text-gray-300 dark:text-gray-600">—</span>@endif</td>
                            <td class="py-3 px-3 text-center">@if(($guest->pax_physical??0)>0)<span class="bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 text-sm font-black px-2.5 py-1 rounded-full">{{ $guest->pax_physical }}</span>@else<span class="text-gray-300 dark:text-gray-600">—</span>@endif</td>
                            <td class="py-3 px-3 text-center"><span class="text-xl font-black text-gray-900 dark:text-white">{{ $guest->pax }}</span></td>
                            <td class="py-3 px-4">
                                <p class="text-xs font-mono font-bold text-gray-800 dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($guest->created_at)->timezone('Asia/Jakarta')->format('d M Y') }}
                                </p>
                                <p class="text-[10px] font-mono text-gray-400 dark:text-gray-500 mt-0.5">
                                    {{ \Carbon\Carbon::parse($guest->created_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                </p>
                            </td>
                            <td class="py-3 px-3 text-center" x-data>
                                <div class="flex items-center justify-center gap-1.5">
                                    <button x-on:click.prevent="$dispatch('open-modal','edit-guest-{{ $guest->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 hover:bg-amber-100 border border-amber-200 dark:border-amber-800 transition hover:scale-105 active:scale-95" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>
                                    <button x-on:click.prevent="$dispatch('open-modal','delete-guest-{{ $guest->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 hover:bg-red-100 border border-red-200 dark:border-red-800 transition hover:scale-105 active:scale-95" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                                <x-modal name="edit-guest-{{ $guest->id }}" focusable>
                                    <form method="post" action="{{ route('guests.update', $guest->id) }}" class="p-6">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="source" value="list_tamu">
                                        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100 dark:border-gray-700">
                                            <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></div>
                                            <div><h2 class="text-base font-bold text-gray-900 dark:text-white">Edit Data Tamu</h2><span class="text-xs font-semibold text-blue-600 bg-blue-50 dark:bg-blue-900/50 px-2 py-0.5 rounded-full">Kuota: {{ $guest->pax }}</span></div>
                                        </div>
                                        <div class="mb-4"><x-input-label for="name_{{ $guest->id }}" value="Nama Tamu"/><x-text-input id="name_{{ $guest->id }}" name="name" type="text" class="mt-1 block w-full uppercase text-black" value="{{ $guest->name }}" required/></div>
                                        <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-xl border border-amber-200 dark:border-amber-800 mb-5">
                                            <p class="text-xs text-amber-700 dark:text-amber-400 mb-3 font-bold text-center uppercase">⚠ Isi ulang untuk hitung total baru</p>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div><x-input-label value="Jatah Online" class="!text-gray-700 dark:!text-gray-300 text-xs"/><x-text-input name="pax_online" type="number" min="0" value="0" class="mt-1 block w-full text-center text-black" required/></div>
                                                <div><x-input-label value="Jatah Fisik" class="!text-gray-700 dark:!text-gray-300 text-xs"/><x-text-input name="pax_physical" type="number" min="0" value="0" class="mt-1 block w-full text-center text-black" required/></div>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-xl text-sm uppercase transition active:scale-95">Update</button>
                                            <button type="button" x-on:click="$dispatch('close')" class="flex-1 border-2 border-gray-200 dark:border-gray-600 font-bold uppercase rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm py-3 transition">Batal</button>
                                        </div>
                                    </form>
                                </x-modal>
                                <x-modal name="delete-guest-{{ $guest->id }}" focusable>
                                    <form method="post" action="{{ route('guests.destroy', $guest->id) }}" class="p-6 text-center">
                                        @csrf @method('DELETE')
                                        <div class="w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-4"><svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></div>
                                        <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase mb-1">Hapus Tamu?</h2>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Data <strong class="text-gray-900 dark:text-white">{{ $guest->name }}</strong> akan dihapus permanen.</p>
                                        <div class="flex gap-2">
                                            <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl text-sm uppercase transition active:scale-95">Hapus</button>
                                            <button type="button" x-on:click="$dispatch('close')" class="flex-1 border-2 border-gray-200 dark:border-gray-600 font-bold uppercase rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm py-3 transition">Batal</button>
                                        </div>
                                    </form>
                                </x-modal>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center"><svg class="w-8 h-8 text-blue-300 dark:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg></div>
                                <p class="text-gray-400 dark:text-gray-500 text-sm">{{ request('search') ? 'Tamu "'.request('search').'" tidak ditemukan.' : 'Belum ada data tamu.' }}</p>
                            </div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form id="bulk-action-form" method="POST" class="hidden">@csrf<input type="hidden" name="_method" id="bulk-method" value="POST"><input type="hidden" name="ids" id="bulk-action-ids"></form>

    <x-modal name="manual-input-modal" focusable>
        <div class="p-6">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100 dark:border-gray-700">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg></div>
                <div><h2 class="text-base font-bold text-gray-900 dark:text-white uppercase">Input Tamu Manual</h2><p class="text-xs text-gray-500 dark:text-gray-400">Tambah tamu baru ke daftar</p></div>
            </div>
            <form method="POST" action="{{ route('guests.store') }}">
                @csrf<input type="hidden" name="source" value="list_tamu">
                <div class="mb-4"><x-input-label for="manual_name" value="Nama Tamu"/><x-text-input id="manual_name" class="block mt-1 w-full uppercase" type="text" name="name" required autofocus placeholder="Contoh: BPK. BUDI"/></div>
                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-600 mb-5">
                    <p class="text-xs font-bold text-gray-500 mb-3 uppercase text-center">Hitung Kuota</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div><x-input-label value="Jatah Online" class="!text-gray-700 dark:!text-gray-300 text-xs"/><x-text-input name="pax_online" type="number" min="0" value="0" class="mt-1 block w-full text-center" required/></div>
                        <div><x-input-label value="Jatah Fisik" class="!text-gray-700 dark:!text-gray-300 text-xs"/><x-text-input name="pax_physical" type="number" min="0" value="1" class="mt-1 block w-full text-center" required/></div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl text-sm uppercase transition active:scale-95">Simpan</button>
                    <button type="button" x-on:click="$dispatch('close')" class="flex-1 border-2 border-gray-200 dark:border-gray-600 font-bold uppercase rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm py-3.5 transition">Batal</button>
                </div>
            </form>
        </div>
    </x-modal>

    <x-modal name="import-guest-modal" focusable>
        <form method="post" action="{{ route('guests.import') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100 dark:border-gray-700">
                <div class="w-10 h-10 rounded-xl bg-sky-100 dark:bg-sky-900/50 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg></div>
                <div><h2 class="text-base font-bold text-gray-900 dark:text-white uppercase">Impor Data Tamu</h2><p class="text-xs text-gray-500 dark:text-gray-400">Upload file Excel (.xlsx, .xls, .csv)</p></div>
            </div>
            <div class="mb-5">
                <input class="block w-full text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4 cursor-pointer hover:border-sky-400 transition focus:outline-none" type="file" name="file" accept=".xlsx,.xls,.csv" required>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-sky-600 hover:bg-sky-700 text-white font-bold py-3.5 rounded-xl text-sm uppercase transition active:scale-95">Impor Data</button>
                <button type="button" x-on:click="$dispatch('close')" class="flex-1 border-2 border-gray-200 dark:border-gray-600 font-bold uppercase rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm py-3.5 transition">Batal</button>
            </div>
        </form>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll  = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.checkbox-item');
            const bulkGroup  = document.getElementById('bulk-actions-group');
            const countDisplays = document.querySelectorAll('.selected-count-display');

            // Pilih semua checkbox
            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                    toggle();
                });
            }

            // Tiap checkbox berubah
            checkboxes.forEach(cb => cb.addEventListener('change', toggle));

            function toggle() {
                const count = document.querySelectorAll('.checkbox-item:checked').length;
                countDisplays.forEach(el => el.innerText = count);
                if (count > 0) {
                    bulkGroup.classList.remove('hidden');
                    bulkGroup.classList.add('flex');
                } else {
                    bulkGroup.classList.add('hidden');
                    bulkGroup.classList.remove('flex');
                }
            }
        });

        function submitBulkAction(type) {
            // Kumpulkan semua ID yang dicentang
            const ids = Array.from(document.querySelectorAll('.checkbox-item:checked')).map(cb => cb.value);
            if (!ids.length) {
                alert('Pilih minimal 1 tamu terlebih dahulu.');
                return;
            }

            const form       = document.getElementById('bulk-action-form');
            const idsInput   = document.getElementById('bulk-action-ids');
            const methodInput = document.getElementById('bulk-method');

            // Masukkan IDs ke hidden input
            idsInput.value = ids.join(',');

            if (type === 'delete') {
                if (!confirm('Yakin ingin menghapus ' + ids.length + ' tamu terpilih secara permanen?')) return;
                form.action  = "{{ route('guests.bulk_destroy') }}";
                methodInput.value = 'DELETE';
                form.target  = '_self';
                form.submit();
            } else {
                // Cetak QR
                form.action  = "{{ route('guests.bulk_qr') }}";
                methodInput.value = 'POST';
                form.target  = '_blank';
                form.submit();
                setTimeout(() => { form.target = '_self'; }, 500);
            }
        }
    </script>
</x-app-layout>