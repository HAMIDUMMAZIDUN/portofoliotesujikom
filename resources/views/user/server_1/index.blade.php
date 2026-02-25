<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">{{ __('Scan Check-In') }}</h2>
    </x-slot>

    <div class="min-h-screen font-sans"
         x-data="{
            selectedGuest:{id:'',name:'',pax:0,actual_pax:0,souvenir_pax:0},
            historyGuest:{name:'',logs:[]},
            editingLog:{id:null,name:'',pax:0},
            deletingLog:{id:null,name:''},
            cameraGuest:{id:null,name:''},
            cameraModalOpen:false,checkinCameraActive:false,photoPreview:null,stream:null,isCheckinCamera:false,
            paxInput:1,isLoadingHistory:false,
            autoOpenName:'{{ session('not_found_name') }}',
            filterQuery:'',searchResults:[],searchLoading:false,
            get sisaKuota(){ return this.selectedGuest.pax - this.selectedGuest.actual_pax; },
            startCamera(fromCheckin=false){
                this.isCheckinCamera=fromCheckin; this.photoPreview=null;
                if(fromCheckin){this.checkinCameraActive=true;}else{this.cameraModalOpen=true;}
                this.$nextTick(()=>{
                    let v=fromCheckin?this.$refs.videoElementCheckin:this.$refs.videoElement;
                    if(navigator.mediaDevices&&navigator.mediaDevices.getUserMedia){
                        navigator.mediaDevices.getUserMedia({video:true}).then(s=>{this.stream=s;v.srcObject=s;v.play();}).catch(e=>{alert('Gagal akses kamera.');this.stopCamera();});
                    }else{alert('Browser tidak mendukung kamera.');}
                });
            },
            stopCamera(){if(this.stream){this.stream.getTracks().forEach(t=>t.stop());this.stream=null;}this.cameraModalOpen=false;this.checkinCameraActive=false;},
            takePicture(fromCheckin=false){
                let v=fromCheckin?this.$refs.videoElementCheckin:this.$refs.videoElement,c=fromCheckin?this.$refs.canvasElementCheckin:this.$refs.canvasElement;
                if(v&&v.srcObject){c.width=v.videoWidth;c.height=v.videoHeight;c.getContext('2d').drawImage(v,0,0,c.width,c.height);this.photoPreview=c.toDataURL('image/jpeg');c.toBlob(b=>{const f=new File([b],'wajah_tamu.jpg',{type:'image/jpeg'});const dt=new DataTransfer();dt.items.add(f);const inp=document.getElementById(fromCheckin?'checkin_photo_input':'table_photo_input');if(inp)inp.files=dt.files;},'image/jpeg');this.stopCamera();}
            },
            resetCheckinForm(){this.paxInput=1;this.photoPreview=null;this.checkinCameraActive=false;let f=document.getElementById('checkin_photo_input');if(f)f.value='';}
         }"
         x-init="if(autoOpenName){$dispatch('open-modal','manual-input-modal');}"
         @set-selected-guest.window="selectedGuest=$event.detail;resetCheckinForm();$dispatch('open-modal','checkin-modal');"
         @open-history.window="historyGuest.name=$event.detail.name;isLoadingHistory=true;$dispatch('open-modal','history-modal');fetch(`/guests/${$event.detail.id}/history`).then(r=>r.json()).then(d=>{historyGuest.logs=d;isLoadingHistory=false;}).catch(e=>{console.error(e);isLoadingHistory=false;});"
         @open-manual-input.window="$dispatch('open-modal','manual-input-modal');">

        {{-- HERO --}}
        <div class="relative overflow-hidden rounded-2xl mb-6" style="background:linear-gradient(135deg,#065f46,#059669,#10b981)">
            <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 20% 50%,white 1px,transparent 1px);background-size:40px 40px"></div>
            <div class="relative px-5 py-6 flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <span class="text-white/80 text-xs font-semibold uppercase tracking-widest">Pintu Masuk Utama</span>
                    </div>
                    <h1 class="text-2xl font-black text-white">SCAN CHECK-IN</h1>
                    <p class="text-emerald-200 text-sm font-medium mt-0.5">{{ Auth::user()->name }}</p>
                </div>
                <div class="hidden sm:flex w-16 h-16 rounded-2xl bg-white/15 border border-white/20 items-center justify-center">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
        <div class="mb-5 flex items-start gap-3 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 text-emerald-800 dark:text-emerald-300 px-4 py-3.5 rounded-xl" x-data x-init="setTimeout(()=>$el.remove(),7000)">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-800 flex items-center justify-center"><svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>
            <div class="flex-1"><p class="font-bold text-sm">SUKSES!</p><p class="text-sm opacity-90 mt-0.5">{{ session('success') }}</p></div>
        </div>
        @endif
        @if(session('warning'))
        <div class="mb-5 flex items-start gap-3 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700 text-amber-800 dark:text-amber-300 px-4 py-3.5 rounded-xl">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center"><svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg></div>
            <div class="flex-1"><p class="font-bold text-sm">PERINGATAN!</p><p class="text-sm opacity-90 mt-0.5">{{ session('warning') }}</p></div>
        </div>
        @endif
        @if(session('error'))
        <div class="mb-5 flex items-start gap-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3.5 rounded-xl">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 flex items-center justify-center"><svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></div>
            <div class="flex-1">
                <p class="font-bold text-sm">GAGAL!</p>
                <p class="text-sm opacity-90 mt-0.5">{{ session('error') }}</p>
                @if(session('not_found_name'))
                    <button type="button" x-on:click="$dispatch('open-modal','manual-input-modal')" class="mt-2 text-xs font-bold underline underline-offset-2 hover:no-underline">+ Input Manual "{{ session('not_found_name') }}"</button>
                @endif
            </div>
        </div>
        @endif

        {{-- INPUT CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            {{-- Scanner --}}
            <div class="relative z-50">
                <label class="block text-xs font-bold text-emerald-600 dark:text-emerald-400 mb-2 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3"/></svg>
                    Scanner / Input Check-In
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none"><svg class="w-5 h-5 text-emerald-400 group-focus-within:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg></div>
                    <form method="GET" action="{{ route('server1') }}">
                        <input type="text" id="search-input" name="search"
                               class="block w-full py-4 pl-12 pr-4 text-base font-semibold text-gray-900 dark:text-white bg-white dark:bg-gray-800 border-2 border-emerald-200 dark:border-emerald-800 rounded-xl shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:focus:border-emerald-500 transition-all outline-none placeholder-gray-400"
                               placeholder="Scan Barcode / Ketik Nama Tamu..." autofocus autocomplete="off">
                    </form>
                </div>
                <div id="autocomplete-results" class="hidden absolute w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl mt-1.5 max-h-64 overflow-y-auto z-50 divide-y divide-gray-100 dark:divide-gray-700"></div>
            </div>

            {{-- Filter Cek Status --}}
            <div class="relative z-40">
                <label class="block text-xs font-bold text-blue-600 dark:text-blue-400 mb-2 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cek Status & Filter Riwayat
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none"><svg class="w-5 h-5 text-blue-400 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                    <div x-show="searchLoading" class="absolute inset-y-0 right-4 flex items-center"><svg class="animate-spin h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg></div>
                    <input type="text" x-model="filterQuery"
                           @input.debounce.300ms="if(filterQuery.length<2){searchResults=[];return;}searchLoading=true;fetch(`{{ route('guests.ajax_search') }}?query=${encodeURIComponent(filterQuery)}`).then(r=>r.json()).then(d=>{searchResults=d;searchLoading=false;}).catch(()=>{searchLoading=false;});"
                           class="block w-full py-4 pl-12 pr-10 text-base font-semibold text-gray-900 dark:text-white bg-white dark:bg-gray-800 border-2 border-blue-200 dark:border-blue-800 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none placeholder-gray-400"
                           placeholder="Cari nama untuk cek status..." autocomplete="off">
                </div>
                <div x-show="searchResults.length>0&&filterQuery.length>=2" @click.away="searchResults=[]"
                     x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl mt-1.5 max-h-64 overflow-y-auto z-50 divide-y divide-gray-100 dark:divide-gray-700">
                    <template x-for="guest in searchResults" :key="guest.id">
                        <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 flex justify-between items-center transition-colors cursor-pointer" @click="filterQuery=guest.name;searchResults=[]">
                            <div><p class="font-bold text-gray-900 dark:text-white text-sm uppercase" x-text="guest.name"></p><p class="text-xs text-gray-500 dark:text-gray-400">Total Pax: <span class="font-semibold" x-text="guest.pax"></span></p></div>
                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-full" :class="guest.actual_pax>0?'bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300':'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300'" x-text="guest.actual_pax>0?'✓ SUDAH MASUK':'✗ BELUM'"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- FILTER TANGGAL --}}
        <form method="GET" action="{{ route('server1') }}" id="date-filter-form" class="mb-5">
            <div class="flex flex-wrap gap-2 items-center p-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl">
                <div class="flex items-center gap-1.5 text-emerald-700 dark:text-emerald-400">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-xs font-bold uppercase tracking-wide whitespace-nowrap">Filter Riwayat:</span>
                </div>

                <div class="flex-1 min-w-[150px]">
                    <input type="date" name="filter_date" value="{{ request('filter_date') }}"
                           class="w-full text-sm bg-white dark:bg-gray-800 border border-emerald-200 dark:border-emerald-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition">
                </div>

                <span class="text-xs text-gray-400 font-semibold">atau</span>

                <div class="flex-1 min-w-[130px]">
                    <select name="filter_month" class="w-full text-sm bg-white dark:bg-gray-800 border border-emerald-200 dark:border-emerald-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition" onchange="this.form.submit()">
                        <option value="">Semua Bulan</option>
                        @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $num => $name)
                            <option value="{{ $num }}" {{ request('filter_month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 min-w-[100px]">
                    <select name="filter_year" class="w-full text-sm bg-white dark:bg-gray-800 border border-emerald-200 dark:border-emerald-700 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ request('filter_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                        @if($availableYears->isEmpty())
                            <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                        @endif
                    </select>
                </div>

                <button type="submit" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition active:scale-95 whitespace-nowrap">Terapkan</button>

                @if(request()->hasAny(['filter_date','filter_month','filter_year']))
                    <a href="{{ route('server1') }}" class="px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-lg transition flex items-center gap-1 whitespace-nowrap">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- TABLE --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between border-b border-gray-100 dark:border-gray-700" style="background:linear-gradient(135deg,#065f46,#059669)">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                    <div>
                        <h2 class="text-white font-bold text-sm uppercase tracking-wide">Riwayat Masuk Terkini</h2>
                        @if(request()->hasAny(['filter_date','filter_month','filter_year']))
                        <p class="text-emerald-200 text-xs mt-0.5">
                            Filter:
                            @if(request('filter_date')) Tgl {{ \Carbon\Carbon::parse(request('filter_date'))->format('d/m/Y') }}@endif
                            @if(request('filter_month')) Bln {{ ['01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des'][request('filter_month')] ?? '' }}@endif
                            @if(request('filter_year')) Thn {{ request('filter_year') }}@endif
                        </p>
                        @endif
                    </div>
                </div>
                <a href="{{ route('server1') }}" class="text-xs text-emerald-200 hover:text-white font-semibold flex items-center gap-1 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Refresh
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="py-3 px-3 text-center text-xs font-bold text-gray-400 uppercase">No</th>
                            <th class="py-3 px-4 text-left text-xs font-bold text-gray-400 uppercase">Waktu</th>
                            <th class="py-3 px-4 text-left text-xs font-bold text-gray-400 uppercase">Nama Tamu</th>
                            <th class="py-3 px-3 text-center text-xs font-bold text-gray-400 uppercase">Jumlah</th>
                            <th class="py-3 px-3 text-center text-xs font-bold text-gray-400 uppercase">Status Kuota</th>
                            <th class="py-3 px-3 text-center text-xs font-bold text-gray-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($logs as $index => $log)
                        <tr class="hover:bg-emerald-50 dark:hover:bg-emerald-900/10 transition-colors"
                            data-name="{{ strtolower($log->guest->name) }}"
                            x-show="filterQuery===''||$el.dataset.name.includes(filterQuery.toLowerCase())">
                            <td class="py-3 px-3 text-center"><span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 text-xs font-bold">{{ $index + 1 }}</span></td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 flex-shrink-0"></div>
                                    <span class="font-mono text-sm font-bold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Jakarta')->format('H:i:s') }}</span>
                                </div>
                                <p class="text-xs text-gray-400 font-mono pl-3.5 mt-0.5">{{ \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Jakarta')->format('d M Y') }}</p>
                            </td>
                            <td class="py-3 px-4"><p class="text-sm font-bold text-gray-900 dark:text-white uppercase">{{ $log->guest->name }}</p></td>
                            <td class="py-3 px-3 text-center"><span class="inline-flex items-center gap-1 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 text-sm font-black px-3 py-1 rounded-full">+{{ $log->pax }} Org</span></td>
                            <td class="py-3 px-3 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-sm font-black {{ $log->guest->actual_pax > $log->guest->pax ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">{{ $log->guest->actual_pax }}</span>
                                    <span class="text-gray-400 text-[10px]">/ {{ $log->guest->pax }}</span>
                                    @if($log->guest->actual_pax > $log->guest->pax)<span class="text-[10px] text-red-600 font-bold bg-red-100 px-1.5 py-0.5 rounded-full mt-0.5">OVER</span>@endif
                                </div>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button title="Edit" x-on:click="editingLog={id:{{ $log->id }},name:'{{ $log->guest->name }}',pax:{{ $log->pax }}};$dispatch('open-modal','edit-log-modal');" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 hover:bg-amber-100 border border-amber-200 dark:border-amber-800 transition hover:scale-105 active:scale-95"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                                    <button title="Hapus" x-on:click="deletingLog={id:{{ $log->id }},name:'{{ $log->guest->name }}'};$dispatch('open-modal','delete-log-modal');" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 hover:bg-red-100 border border-red-200 dark:border-red-800 transition hover:scale-105 active:scale-95"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    <button title="Riwayat" x-on:click="$dispatch('open-history',{id:{{ $log->guest->id }},name:'{{ $log->guest->name }}'})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 hover:bg-blue-100 border border-blue-200 dark:border-blue-800 transition hover:scale-105 active:scale-95"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center"><svg class="w-8 h-8 text-emerald-300 dark:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                                <p class="text-gray-400 dark:text-gray-500 text-sm">Belum ada tamu masuk hari ini.</p>
                            </div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL CHECK-IN --}}
        <x-modal name="checkin-modal" focusable>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#065f46,#10b981)"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div><h2 class="text-base font-bold text-gray-900 dark:text-white uppercase">Check-In Pintu Masuk</h2><p class="text-xs text-gray-500 dark:text-gray-400">Konfirmasi kehadiran tamu</p></div>
                </div>
                <div class="rounded-xl p-4 mb-5 border" :class="sisaKuota<=0?'border-red-200 bg-red-50':'border-emerald-200 bg-emerald-50'">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nama Tamu</p>
                    <p class="text-xl font-black uppercase leading-tight" :class="sisaKuota<=0?'text-red-900':'text-emerald-900'" x-text="selectedGuest.name"></p>
                    <div class="grid grid-cols-3 gap-3 mt-4">
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 text-center border border-gray-100 dark:border-gray-700 shadow-sm"><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Jatah</p><p class="text-2xl font-black text-gray-900 dark:text-white" x-text="selectedGuest.pax"></p></div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 text-center border border-gray-100 dark:border-gray-700 shadow-sm"><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Masuk</p><p class="text-2xl font-black text-emerald-600" x-text="selectedGuest.actual_pax"></p></div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 text-center border border-gray-100 dark:border-gray-700 shadow-sm"><p class="text-[10px] font-bold uppercase mb-1" :class="sisaKuota<=0?'text-red-400':'text-gray-400'">Sisa</p><p class="text-2xl font-black" :class="sisaKuota<=0?'text-red-500':'text-blue-600'" x-text="sisaKuota"></p></div>
                    </div>
                </div>
                <template x-if="sisaKuota<=0">
                    <div class="mb-4 bg-red-600 text-white p-4 rounded-xl text-center shadow-lg animate-bounce">
                        <strong class="text-lg">🚫 KUOTA SUDAH HABIS!</strong><br><span class="text-sm">Tamu ini tidak dapat check-in lagi.</span>
                    </div>
                </template>
                <form method="POST" action="{{ route('server1.checkin') }}" enctype="multipart/form-data">
                    @csrf<input type="hidden" name="guest_id" x-bind:value="selectedGuest.id">
                    <div class="mb-4" x-show="sisaKuota>0">
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-2">Jumlah Masuk Sekarang</label>
                        <input class="block w-full text-center text-4xl font-black border-2 border-emerald-300 dark:border-emerald-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white py-4 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition" type="number" name="pax_in" x-model.number="paxInput" min="1" :max="sisaKuota" required/>
                    </div>
                    <div class="flex gap-2 pt-3 border-t border-gray-100 dark:border-gray-700 mt-4">
                        <button type="button" x-on:click="$dispatch('close');stopCamera()" class="flex-1 py-3 border-2 border-gray-200 dark:border-gray-600 font-bold rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm uppercase">Tutup</button>
                        <button type="submit" x-show="sisaKuota>0" class="flex-1 text-white font-black py-3 rounded-xl uppercase tracking-wide shadow-lg transition hover:opacity-90 active:scale-95 text-sm" style="background:linear-gradient(135deg,#065f46,#10b981)">✓ KONFIRMASI MASUK</button>
                        <button type="button" x-show="sisaKuota<=0" disabled class="flex-1 bg-gray-300 text-white cursor-not-allowed rounded-xl py-3 text-sm font-bold uppercase">KUOTA HABIS</button>
                    </div>
                </form>
            </div>
        </x-modal>

        {{-- MODAL MANUAL INPUT --}}
        <x-modal name="manual-input-modal" focusable>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg></div>
                    <div><h2 class="text-base font-bold text-gray-900 dark:text-white uppercase">Input Tamu Manual</h2><p class="text-xs text-gray-500 dark:text-gray-400">Tambah tamu & langsung check-in</p></div>
                </div>
                <form method="POST" action="{{ route('guests.store') }}">
                    @csrf<input type="hidden" name="source" value="server1">
                    <div class="mb-4"><x-input-label for="manual_name" value="Nama Tamu"/><x-text-input id="manual_name" class="block mt-1 w-full uppercase" type="text" name="name" :value="session('not_found_name')" required autofocus placeholder="Contoh: BPK. BUDI"/></div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-600 mb-5">
                        <p class="text-xs font-bold text-gray-500 mb-3 uppercase text-center">Kuota Undangan</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div><x-input-label value="Jatah Online" class="!text-gray-700 dark:!text-gray-300 text-xs"/><x-text-input name="pax_online" type="number" min="0" value="0" class="mt-1 block w-full text-center" required/></div>
                            <div><x-input-label value="Jatah Fisik" class="!text-gray-700 dark:!text-gray-300 text-xs"/><x-text-input name="pax_physical" type="number" min="0" value="1" class="mt-1 block w-full text-center border-emerald-300 focus:border-emerald-500 focus:ring-emerald-500" required/></div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl text-sm uppercase transition active:scale-95">Simpan & Check In</button>
                        <button type="button" x-on:click="$dispatch('close')" class="flex-1 border-2 border-gray-200 dark:border-gray-600 font-bold uppercase rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm py-3.5 transition">Batal</button>
                    </div>
                </form>
            </div>
        </x-modal>

        {{-- MODAL HISTORY --}}
        <x-modal name="history-modal" focusable>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></div>
                    <div><h2 class="text-base font-bold text-gray-900 dark:text-white uppercase">Riwayat Pengambilan</h2><p class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase" x-text="historyGuest.name"></p></div>
                </div>
                <div x-show="isLoadingHistory" class="py-10 text-center"><svg class="animate-spin h-8 w-8 text-blue-600 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg></div>
                <div x-show="!isLoadingHistory">
                    <div class="rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/50"><tr><th class="py-2 px-4 text-left font-bold text-xs text-gray-400 uppercase">Waktu & Tanggal</th><th class="py-2 px-4 text-center font-bold text-xs text-gray-400 uppercase">Jumlah</th></tr></thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <template x-for="log in historyGuest.logs" :key="log.id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="py-2 px-4 font-mono text-gray-800 dark:text-gray-200"><span x-text="log.time" class="font-bold"></span> <span x-text="log.date" class="text-xs text-gray-400 ml-1"></span></td>
                                        <td class="py-2 px-4 text-center font-black text-emerald-600 dark:text-emerald-400" x-text="log.pax"></td>
                                    </tr>
                                </template>
                                <template x-if="historyGuest.logs.length===0"><tr><td colspan="2" class="py-8 text-center italic text-gray-400">Belum ada catatan riwayat.</td></tr></template>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" x-on:click="$dispatch('close')" class="mt-5 w-full py-3 border-2 border-gray-200 dark:border-gray-600 font-bold rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm uppercase">Tutup</button>
                </div>
            </div>
        </x-modal>

        {{-- MODAL EDIT --}}
        <x-modal name="edit-log-modal" focusable>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></div>
                    <div><h2 class="text-base font-bold text-gray-900 dark:text-white uppercase">Edit Data Check-In</h2><p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase" x-text="editingLog.name"></p></div>
                </div>
                <form method="POST" action="{{ route('guests.logs.update') }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="log_id" :value="editingLog.id">
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-2">Jumlah (Orang)</label>
                    <input class="w-full text-center text-4xl font-black border-2 border-gray-200 dark:border-gray-600 rounded-xl py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition" type="number" name="pax" x-model="editingLog.pax" min="1" required>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold w-full py-3.5 rounded-xl mt-5 text-sm uppercase transition active:scale-95">Simpan Perubahan</button>
                </form>
            </div>
        </x-modal>

        {{-- MODAL DELETE --}}
        <x-modal name="delete-log-modal" focusable>
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-4"><svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></div>
                <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase mb-1">Hapus Check-In?</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Hapus riwayat check-in untuk <strong class="text-gray-900 dark:text-white uppercase" x-text="deletingLog.name"></strong>?</p>
                <form method="POST" action="{{ route('guests.logs.destroy') }}">
                    @csrf @method('DELETE')
                    <input type="hidden" name="log_id" :value="deletingLog.id">
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 rounded-xl text-sm uppercase transition active:scale-95">Hapus</button>
                        <button type="button" x-on:click="$dispatch('close')" class="flex-1 border-2 border-gray-200 dark:border-gray-600 font-bold uppercase rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm py-3.5 transition">Batal</button>
                    </div>
                </form>
            </div>
        </x-modal>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const resultsDiv = document.getElementById('autocomplete-results');
            let debounceTimer;
            searchInput.focus();
            searchInput.addEventListener('input', function() {
                const query = this.value;
                clearTimeout(debounceTimer);
                if (query.length < 2) { resultsDiv.classList.add('hidden'); resultsDiv.innerHTML = ''; return; }
                debounceTimer = setTimeout(() => fetchResults(query), 300);
            });
            function fetchResults(query) {
                fetch(`{{ route('guests.ajax_search') }}?query=${encodeURIComponent(query)}`).then(r => r.json()).then(data => {
                    resultsDiv.innerHTML = '';
                    if (data.length > 0) {
                        resultsDiv.classList.remove('hidden');
                        data.forEach(guest => {
                            const sisa = guest.pax - (guest.actual_pax || 0), isFull = sisa <= 0;
                            const item = document.createElement('div');
                            item.className = `flex items-center justify-between p-3 cursor-pointer hover:bg-emerald-50 transition-colors ${isFull ? 'bg-red-50' : 'bg-white'}`;
                            item.innerHTML = `<div><p class="font-bold text-sm text-gray-900 uppercase">${guest.name}</p><p class="text-xs font-semibold mt-0.5 ${isFull ? 'text-red-500' : 'text-emerald-600'}">${isFull ? '✗ Kuota Penuh' : '✓ Sisa: ' + sisa + ' orang'}</p></div><span class="text-xs font-bold px-2.5 py-1 rounded-full ${isFull ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700'}">Total: ${guest.pax}</span>`;
                            item.addEventListener('click', () => {
                                window.dispatchEvent(new CustomEvent('set-selected-guest', { detail: { id: guest.id, name: guest.name, pax: guest.pax, actual_pax: guest.actual_pax || 0 } }));
                                resultsDiv.classList.add('hidden'); searchInput.value = '';
                            });
                            resultsDiv.appendChild(item);
                        });
                    } else {
                        resultsDiv.classList.remove('hidden');
                        resultsDiv.innerHTML = `<div class="p-4 text-center"><p class="text-sm text-gray-500 mb-2">Tamu tidak ditemukan</p><button onclick="openManualInput('${query}')" class="text-sm font-bold text-emerald-600 hover:text-emerald-800 underline underline-offset-2">+ Input Manual?</button></div>`;
                    }
                }).catch(e => console.error(e));
            }
            window.openManualInput = function(name) {
                const inp = document.getElementById('manual_name');
                if (inp) inp.value = name.toUpperCase();
                window.dispatchEvent(new CustomEvent('open-manual-input'));
                resultsDiv.classList.add('hidden'); searchInput.value = '';
            };
            document.addEventListener('click', e => { if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) resultsDiv.classList.add('hidden'); });
        });
    </script>

</x-app-layout>