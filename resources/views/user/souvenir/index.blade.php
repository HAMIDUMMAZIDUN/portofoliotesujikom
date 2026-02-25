<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Pos Souvenir') }}
        </h2>
    </x-slot>

    <div class="min-h-screen font-sans"
         x-data="{
            selectedGuest: { id: '', name: '', pax: 0, actual_pax: 0, souvenir_pax: 0 },
            historyGuest: { name: '', logs: [] },
            editingLog: { id: null, name: '', pax: 0 },
            deletingLog: { id: null, name: '' },
            paxInput: 1,
            isLoadingHistory: false,
            autoOpenName: '{{ session('not_found_name') }}',
            filterQuery: '',
            searchResults: [],
            searchLoading: false,
            selectedLogs: [],
            get allLogIds() {
                return Array.from(document.querySelectorAll('.log-checkbox')).map(el => el.value);
            },
            toggleAll() {
                if (this.selectedLogs.length === this.allLogIds.length) {
                    this.selectedLogs = [];
                } else {
                    this.selectedLogs = this.allLogIds;
                }
            }
         }"
         x-init="
            if (autoOpenName && autoOpenName.length > 0) {
                $dispatch('open-modal', 'manual-input-modal');
            }
         "
         @set-selected-guest.window="
            selectedGuest = $event.detail;
            let sisa = selectedGuest.pax - selectedGuest.souvenir_pax;
            paxInput = (sisa > 0) ? 1 : 0;
            $dispatch('open-modal', 'checkin-modal');
         "
         @open-history.window="
            historyGuest.name = $event.detail.name;
            isLoadingHistory = true;
            $dispatch('open-modal', 'history-modal');
            fetch(`/guests/${$event.detail.id}/history`)
                .then(res => res.json())
                .then(data => {
                    historyGuest.logs = data;
                    isLoadingHistory = false;
                })
                .catch(err => {
                    console.error(err);
                    isLoadingHistory = false;
                });
         "
         @open-manual-input.window="$dispatch('open-modal', 'manual-input-modal');"
    >

        {{-- ===== HERO HEADER ===== --}}
        <div class="relative overflow-hidden rounded-2xl mx-0 mb-6"
             style="background: linear-gradient(135deg, #6d28d9 0%, #7c3aed 40%, #a855f7 100%);">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: radial-gradient(circle at 20% 50%, white 1px, transparent 1px), radial-gradient(circle at 80% 20%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
            <div class="relative px-5 py-6 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span class="text-white/80 text-xs font-semibold uppercase tracking-widest">Pos Souvenir</span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">DIGITAL GUESTBOOK</h1>
                    <p class="text-purple-200 text-sm font-medium mt-0.5">{{ Auth::user()->name }}</p>
                </div>
                <div class="hidden sm:flex items-center justify-center w-16 h-16 rounded-2xl bg-white/15 backdrop-blur-sm border border-white/20 shadow-inner">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- ===== FLASH MESSAGES ===== --}}
        @if(session('success'))
            <div class="mb-5 flex items-start gap-3 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 text-emerald-800 dark:text-emerald-300 px-4 py-3.5 rounded-xl shadow-sm"
                 x-data x-init="setTimeout(() => $el.remove(), 6000)">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-800 flex items-center justify-center mt-0.5">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm">BERHASIL!</p>
                    <p class="text-sm mt-0.5 opacity-90">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 flex items-start gap-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3.5 rounded-xl shadow-sm">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 dark:bg-red-800 flex items-center justify-center mt-0.5">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm">GAGAL!</p>
                    <p class="text-sm mt-0.5 opacity-90">{{ session('error') }}</p>
                    @if(session('not_found_name'))
                        <button type="button"
                                x-on:click="$dispatch('open-modal', 'manual-input-modal')"
                                class="mt-2 inline-flex items-center gap-1 text-xs font-bold text-red-700 dark:text-red-400 underline underline-offset-2 hover:no-underline transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Input Manual "{{ session('not_found_name') }}" Sekarang?
                        </button>
                    @endif
                </div>
            </div>
        @endif

        {{-- ===== INPUT SECTION ===== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

            {{-- Scanner / Cari Nama --}}
            <div class="relative z-50">
                <label class="block text-xs font-bold text-purple-600 dark:text-purple-400 mb-2 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 16v1"/>
                    </svg>
                    Scanner / Input Souvenir
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-purple-400 group-focus-within:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" id="search-input"
                           class="block w-full py-4 pl-12 pr-4 text-base font-semibold text-gray-900 dark:text-white bg-white dark:bg-gray-800 border-2 border-purple-200 dark:border-purple-800 rounded-xl shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 dark:focus:border-purple-500 transition-all outline-none placeholder-gray-400"
                           placeholder="Scan Barcode / Cari Nama Tamu..." autofocus autocomplete="off">
                </div>
                <div id="autocomplete-results"
                     class="hidden absolute w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl mt-1.5 max-h-64 overflow-y-auto z-50 divide-y divide-gray-100 dark:divide-gray-700"></div>
            </div>

            {{-- Cek Status Filter --}}
            <div class="relative z-40">
                <label class="block text-xs font-bold text-blue-600 dark:text-blue-400 mb-2 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Filter Riwayat / Cek Status
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-blue-400 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="filterQuery"
                           @input.debounce.300ms="
                                if(filterQuery.length < 2) { searchResults = []; return; }
                                searchLoading = true;
                                fetch(`{{ route('guests.ajax_search') }}?query=${encodeURIComponent(filterQuery)}`)
                                    .then(res => res.json())
                                    .then(data => { searchResults = data; searchLoading = false; })
                                    .catch(() => { searchLoading = false; });
                           "
                           class="block w-full py-4 pl-12 pr-4 text-base font-semibold text-gray-900 dark:text-white bg-white dark:bg-gray-800 border-2 border-blue-200 dark:border-blue-800 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:focus:border-blue-500 transition-all outline-none placeholder-gray-400"
                           placeholder="Cari nama untuk cek status..." autocomplete="off">
                </div>

                {{-- Dropdown Hasil Filter --}}
                <div x-show="searchResults.length > 0 && filterQuery.length >= 2"
                     @click.away="searchResults = []"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl mt-1.5 max-h-64 overflow-y-auto z-50 divide-y divide-gray-100 dark:divide-gray-700">
                    <template x-for="guest in searchResults" :key="guest.id">
                        <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 flex justify-between items-center cursor-pointer transition-colors"
                             @click="filterQuery = guest.name; searchResults = []">
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-sm uppercase" x-text="guest.name"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Jatah: <span class="font-semibold" x-text="guest.pax"></span></p>
                            </div>
                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-full"
                                  :class="guest.souvenir_pax > 0
                                    ? 'bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300'
                                    : 'bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300'"
                                  x-text="guest.souvenir_pax > 0 ? '✓ SUDAH' : '✗ BELUM'">
                            </span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- ===== FILTER TANGGAL ===== --}}
        <form method="GET" action="{{ route('souvenir') }}" id="date-filter-form" class="mb-5">
            <div class="flex flex-wrap gap-2 items-center p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-xl">
                <div class="flex items-center gap-1.5 text-purple-700 dark:text-purple-400">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-xs font-bold uppercase tracking-wide whitespace-nowrap">Filter Riwayat:</span>
                </div>

                {{-- Filter Tanggal Spesifik --}}
                <div class="flex-1 min-w-[150px]">
                    <input type="date" name="filter_date" value="{{ request('filter_date') }}"
                           class="w-full text-sm bg-white dark:bg-gray-800 border border-purple-200 dark:border-purple-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition"
                           title="Filter berdasarkan tanggal spesifik">
                </div>

                <span class="text-xs text-gray-400 dark:text-gray-500 font-semibold">atau</span>

                {{-- Filter Bulan --}}
                <div class="flex-1 min-w-[130px]">
                    <select name="filter_month"
                            class="w-full text-sm bg-white dark:bg-gray-800 border border-purple-200 dark:border-purple-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition"
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
                            class="w-full text-sm bg-white dark:bg-gray-800 border border-purple-200 dark:border-purple-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition"
                            onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ request('filter_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                        @if($availableYears->isEmpty())
                            <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                        @endif
                    </select>
                </div>

                <button type="submit" class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-lg transition active:scale-95 whitespace-nowrap">Terapkan</button>

                @if(request()->hasAny(['filter_date','filter_month','filter_year']))
                    <a href="{{ route('souvenir') }}" class="px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-lg transition flex items-center gap-1 whitespace-nowrap">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- ===== TABLE SECTION ===== --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            {{-- Table Header --}}
            <div class="px-5 py-4 flex items-center justify-between border-b border-gray-100 dark:border-gray-700"
                 style="background: linear-gradient(135deg, #6d28d9 0%, #7c3aed 100%);">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-white font-bold text-sm uppercase tracking-wide">Riwayat Pengambilan Terkini</h2>
                        @if(request()->hasAny(['filter_date','filter_month','filter_year']))
                        <p class="text-purple-200 text-xs mt-0.5">
                            Filter:
                            @if(request('filter_date')) Tgl {{ \Carbon\Carbon::parse(request('filter_date'))->format('d/m/Y') }}@endif
                            @if(request('filter_month')) Bln {{ ['01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des'][request('filter_month')] ?? '' }}@endif
                            @if(request('filter_year')) Thn {{ request('filter_year') }}@endif
                        </p>
                        @else
                        <p class="text-purple-200 text-xs" x-show="filterQuery.length > 0">
                            Filter: <span class="font-semibold" x-text="filterQuery"></span>
                        </p>
                        @endif
                    </div>
                </div>

                {{-- Hapus Terpilih Button --}}
                <button x-show="selectedLogs.length > 0"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-90"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-on:click="$dispatch('open-modal', 'bulk-delete-modal')"
                        class="flex items-center gap-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-bold px-3 py-2 rounded-lg shadow transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    HAPUS (<span x-text="selectedLogs.length"></span>)
                </button>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="w-10 py-3 px-3 text-center">
                                <input type="checkbox"
                                       @click="toggleAll()"
                                       :checked="selectedLogs.length === allLogIds.length && allLogIds.length > 0"
                                       class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-purple-600 focus:ring-purple-500 cursor-pointer">
                            </th>
                            <th class="py-3 px-3 text-center text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">No</th>
                            <th class="py-3 px-4 text-left text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="py-3 px-4 text-left text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Nama Tamu</th>
                            <th class="py-3 px-4 text-center text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Diambil</th>
                            <th class="py-3 px-4 text-center text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($logs as $index => $log)
                            <tr class="hover:bg-purple-50 dark:hover:bg-purple-900/10 transition-colors duration-150"
                                data-name="{{ strtolower($log->guest->name) }}"
                                x-show="filterQuery === '' || $el.dataset.name.includes(filterQuery.toLowerCase())">

                                <td class="py-3 px-3 text-center">
                                    <input type="checkbox"
                                           value="{{ $log->id }}"
                                           x-model="selectedLogs"
                                           class="log-checkbox w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-purple-600 focus:ring-purple-500 cursor-pointer">
                                </td>

                                <td class="py-3 px-3 text-center">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-400 text-xs font-bold">
                                        {{ $index + 1 }}
                                    </span>
                                </td>

                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 flex-shrink-0"></div>
                                        <span class="font-mono text-sm font-bold text-gray-800 dark:text-gray-200">
                                            {{ \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Jakarta')->format('H:i:s') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 font-mono pl-3.5 mt-0.5">
                                        {{ \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Jakarta')->format('d M') }}
                                    </p>
                                </td>

                                <td class="py-3 px-4">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white uppercase leading-tight">
                                        {{ $log->guest->name }}
                                    </p>
                                </td>

                                <td class="py-3 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-400 text-sm font-black px-3 py-1 rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12z"/>
                                        </svg>
                                        {{ $log->pax }} Pcs
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button type="button"
                                                title="Edit"
                                                x-on:click="editingLog = { id: {{ $log->id }}, name: '{{ $log->guest->name }}', pax: {{ $log->pax }} }; $dispatch('open-modal', 'edit-log-modal');"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50 border border-amber-200 dark:border-amber-800 transition-all hover:scale-105 active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </button>
                                        <button type="button"
                                                title="Hapus"
                                                x-on:click="deletingLog = { id: {{ $log->id }}, name: '{{ $log->guest->name }}' }; $dispatch('open-modal', 'delete-log-modal');"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 border border-red-200 dark:border-red-800 transition-all hover:scale-105 active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-2xl bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-purple-300 dark:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                            </svg>
                                        </div>
                                        <p class="text-gray-400 dark:text-gray-500 font-medium text-sm">Belum ada pengambilan souvenir.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== MODAL: CHECKIN SOUVENIR ===== --}}
        <x-modal name="checkin-modal" focusable>
            <div class="p-6">
                {{-- Modal Header --}}
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white uppercase">Serahkan Souvenir</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Konfirmasi pengambilan souvenir tamu</p>
                    </div>
                </div>

                {{-- Guest Info Card --}}
                <div class="rounded-xl p-4 mb-5 border border-purple-200 dark:border-purple-800"
                     style="background: linear-gradient(135deg, #f5f3ff, #faf5ff);">
                    <p class="text-xs font-semibold text-purple-500 uppercase tracking-wide mb-1">Nama Tamu</p>
                    <p class="text-xl font-black text-gray-900 uppercase leading-tight" x-text="selectedGuest.name"></p>

                    <div class="grid grid-cols-3 gap-3 mt-4">
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 text-center border border-gray-100 dark:border-gray-700 shadow-sm">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Total Jatah</p>
                            <p class="text-2xl font-black text-gray-900 dark:text-white" x-text="selectedGuest.pax"></p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 text-center border border-gray-100 dark:border-gray-700 shadow-sm">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Sudah Ambil</p>
                            <p class="text-2xl font-black text-purple-600" x-text="selectedGuest.souvenir_pax"></p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 text-center border border-gray-100 dark:border-gray-700 shadow-sm">
                            <p class="text-[10px] font-bold uppercase mb-1"
                               :class="(selectedGuest.pax - selectedGuest.souvenir_pax) <= 0 ? 'text-red-400' : 'text-gray-400'">Sisa</p>
                            <p class="text-2xl font-black"
                               :class="(selectedGuest.pax - selectedGuest.souvenir_pax) <= 0 ? 'text-red-500' : 'text-emerald-600'"
                               x-text="selectedGuest.pax - selectedGuest.souvenir_pax"></p>
                        </div>
                    </div>
                </div>

                {{-- Form atau Pesan Habis --}}
                <template x-if="selectedGuest.pax - selectedGuest.souvenir_pax > 0">
                    <form method="GET" action="{{ route('souvenir') }}">
                        <input type="hidden" name="guest_id" x-bind:value="selectedGuest.id">
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-2">
                                Jumlah Souvenir Diambil
                            </label>
                            <input class="block w-full text-center text-4xl font-black border-2 border-purple-300 dark:border-purple-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white py-4 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition"
                                   type="number" name="pax_in" x-model.number="paxInput"
                                   min="1" :max="selectedGuest.pax - selectedGuest.souvenir_pax" required/>
                        </div>
                        <button type="submit"
                                class="w-full py-4 text-white font-black text-sm uppercase tracking-widest rounded-xl shadow-lg transition-all hover:opacity-90 active:scale-98"
                                style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                            ✓ SERAHKAN SOUVENIR
                        </button>
                    </form>
                </template>

                <template x-if="selectedGuest.pax - selectedGuest.souvenir_pax <= 0">
                    <div class="text-center py-4">
                        <div class="w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-black text-red-600 uppercase mb-1">Jatah Souvenir Habis!</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">Tamu ini sudah mengambil semua jatah souvenir.</p>
                        <button type="button" x-on:click="$dispatch('close')"
                                class="w-full py-3 border-2 border-gray-300 dark:border-gray-600 font-bold rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm uppercase">
                            Tutup
                        </button>
                    </div>
                </template>
            </div>
        </x-modal>

        {{-- ===== MODAL: INPUT MANUAL ===== --}}
        <x-modal name="manual-input-modal" focusable>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white uppercase">Input Manual</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Tambah tamu baru & serahkan souvenir</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('guests.store') }}">
                    @csrf
                    <input type="hidden" name="from_souvenir" value="1">
                    <input type="hidden" name="source" value="souvenir">
                    <input type="hidden" name="pax_online" value="0">

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-2">
                            Nama Tamu
                        </label>
                        <input id="manual_name" type="text" name="name"
                               class="block w-full border-2 border-gray-200 dark:border-gray-600 rounded-xl uppercase font-bold p-3.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400"
                               x-model="autoOpenName" required autofocus placeholder="Ketik nama tamu..."/>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-2">
                            Jumlah Souvenir
                        </label>
                        <input type="number" name="pax_physical" value="1" min="1"
                               class="block w-full border-2 border-gray-200 dark:border-gray-600 rounded-xl p-3.5 font-bold text-center text-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition bg-white dark:bg-gray-800 text-gray-900 dark:text-white" required/>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl text-sm uppercase tracking-wide shadow transition-all active:scale-95">
                            Simpan & Berikan
                        </button>
                        <button type="button" x-on:click="$dispatch('close')"
                                class="flex-1 border-2 border-gray-200 dark:border-gray-600 font-bold uppercase rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm transition py-3.5">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>

        {{-- ===== MODAL: HAPUS MASSAL ===== --}}
        <x-modal name="bulk-delete-modal" focusable>
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase mb-2">Hapus Riwayat?</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Hapus <span class="font-black text-red-600" x-text="selectedLogs.length"></span> riwayat pengambilan terpilih?
                </p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-6">Kuota souvenir tamu akan dikembalikan secara otomatis.</p>

                <form method="POST" action="{{ route('guests.logs.bulk_destroy') }}">
                    @csrf @method('DELETE')
                    <template x-for="id in selectedLogs" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <div class="flex gap-2">
                        <button type="submit"
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 rounded-xl text-sm uppercase transition active:scale-95">
                            Ya, Hapus Semua
                        </button>
                        <button type="button" x-on:click="$dispatch('close')"
                                class="flex-1 border-2 border-gray-200 dark:border-gray-600 font-bold uppercase rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm transition py-3.5">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>

        {{-- ===== MODAL: EDIT LOG ===== --}}
        <x-modal name="edit-log-modal" focusable>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white uppercase">Edit Riwayat</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase" x-text="editingLog.name"></p>
                    </div>
                </div>

                <form method="POST" action="{{ route('guests.logs.update') }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="log_id" :value="editingLog.id">

                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-2">
                        Jumlah Souvenir (Pcs)
                    </label>
                    <input class="w-full text-center text-4xl font-black border-2 border-gray-200 dark:border-gray-600 rounded-xl py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition"
                           type="number" name="pax" x-model="editingLog.pax" min="1" required>

                    <button type="submit"
                            class="bg-amber-500 hover:bg-amber-600 text-white font-bold w-full py-3.5 rounded-xl mt-5 text-sm uppercase tracking-wide shadow transition-all active:scale-95">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </x-modal>

        {{-- ===== MODAL: HAPUS LOG ===== --}}
        <x-modal name="delete-log-modal" focusable>
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase mb-2">Hapus Riwayat?</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Hapus riwayat pengambilan souvenir untuk:
                </p>
                <p class="font-black text-gray-900 dark:text-white uppercase mb-1" x-text="deletingLog.name"></p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-6">Kuota souvenir tamu akan dikembalikan.</p>

                <form method="POST" action="{{ route('guests.logs.destroy') }}">
                    @csrf @method('DELETE')
                    <input type="hidden" name="log_id" :value="deletingLog.id">
                    <div class="flex gap-2">
                        <button type="submit"
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 rounded-xl text-sm uppercase transition active:scale-95">
                            Hapus
                        </button>
                        <button type="button" x-on:click="$dispatch('close')"
                                class="flex-1 border-2 border-gray-200 dark:border-gray-600 font-bold uppercase rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm transition py-3.5">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const resultsDiv  = document.getElementById('autocomplete-results');
            let debounceTimer;

            searchInput.addEventListener('input', function() {
                const query = this.value;
                clearTimeout(debounceTimer);
                if (query.length < 2) {
                    resultsDiv.classList.add('hidden');
                    return;
                }
                debounceTimer = setTimeout(() => {
                    fetch(`{{ route('guests.ajax_search') }}?query=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            resultsDiv.innerHTML = '';
                            if (data.length > 0) {
                                resultsDiv.classList.remove('hidden');
                                data.forEach(guest => {
                                    const sisa   = guest.pax - (guest.souvenir_pax || 0);
                                    const isFull = sisa <= 0;
                                    const item   = document.createElement('div');
                                    item.className = `flex items-center justify-between p-3 cursor-pointer hover:bg-purple-50 transition-colors ${isFull ? 'bg-red-50' : 'bg-white'}`;
                                    item.innerHTML = `
                                        <div>
                                            <p class="font-bold text-sm text-gray-900 uppercase">${guest.name}</p>
                                            <p class="text-xs font-semibold mt-0.5 ${isFull ? 'text-red-500' : 'text-purple-600'}">${isFull ? '✗ Jatah Habis' : '✓ Sisa: ' + sisa + ' pcs'}</p>
                                        </div>
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full border ${isFull ? 'border-red-200 bg-red-50 text-red-600' : 'border-purple-200 bg-purple-50 text-purple-700'}">Total: ${guest.pax}</span>
                                    `;
                                    item.addEventListener('click', () => {
                                        window.dispatchEvent(new CustomEvent('set-selected-guest', {
                                            detail: { ...guest, souvenir_pax: guest.souvenir_pax || 0 }
                                        }));
                                        resultsDiv.classList.add('hidden');
                                        searchInput.value = '';
                                    });
                                    resultsDiv.appendChild(item);
                                });
                            } else {
                                resultsDiv.classList.remove('hidden');
                                resultsDiv.innerHTML = `
                                    <div class="p-4 text-center">
                                        <p class="text-sm text-gray-500">Tamu tidak ditemukan.</p>
                                        <button type="button" onclick="openManualInput('${query}')"
                                                class="mt-2 text-sm font-bold text-blue-600 hover:text-blue-800 underline underline-offset-2">
                                            + Input Manual?
                                        </button>
                                    </div>`;
                            }
                        });
                }, 300);
            });

            window.openManualInput = function(name) {
                const inputManual = document.getElementById('manual_name');
                if (inputManual) inputManual.value = name.toUpperCase();
                window.dispatchEvent(new CustomEvent('open-manual-input'));
                resultsDiv.classList.add('hidden');
                searchInput.value = '';
            };

            document.addEventListener('click', (e) => {
                if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
                    resultsDiv.classList.add('hidden');
                }
            });
        });
    </script>

</x-app-layout>