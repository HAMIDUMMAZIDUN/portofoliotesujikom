<x-app-layout title="Tamu Hadir">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">{{ __('Riwayat Kehadiran & Souvenir') }}</h2>
    </x-slot>

    <div class="min-h-screen font-sans" x-data="{ previewModalOpen:false, previewImageUrl:'', previewGuestName:'' }">

        {{-- HERO --}}
        <div class="relative overflow-hidden rounded-2xl mb-6" style="background:linear-gradient(135deg,#1e3a5f,#1d4ed8,#2563eb)">
            <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 20% 50%,white 1px,transparent 1px);background-size:40px 40px"></div>
            <div class="relative px-5 py-6 flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <span class="text-white/80 text-xs font-semibold uppercase tracking-widest">Rekap Kehadiran</span>
                    </div>
                    <h1 class="text-2xl font-black text-white">TAMU HADIR & SOUVENIR</h1>
                    <p class="text-blue-200 text-sm font-medium mt-0.5">
                        <span class="uppercase font-bold">{{ Auth::user()->name }}</span>
                        @if(Auth::user()->event_date) · {{ Auth::user()->event_date ?? '1 NOVEMBER 2025' }}@endif
                        @if(Auth::user()->event_location) · {{ Auth::user()->event_location ?? 'CASA EUNOIA' }}@endif
                    </p>
                </div>
                <div class="hidden sm:flex flex-col items-end gap-2">
                    <a href="{{ route('attendance.pdf') }}" target="_blank"
                       class="flex items-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white text-xs font-bold rounded-xl border border-white/30 transition whitespace-nowrap backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download PDF
                    </a>
                </div>
            </div>
        </div>

        {{-- MOBILE DOWNLOAD BUTTON --}}
        <div class="sm:hidden mb-4">
            <a href="{{ route('attendance.pdf') }}" target="_blank"
               class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download PDF Rekapan
            </a>
        </div>

        {{-- STATS SUMMARY --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4 text-center">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <p class="text-3xl font-black text-blue-600 dark:text-blue-400">{{ $total_invitation_entered }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mt-1 uppercase tracking-wide">Undangan Masuk</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4 text-center">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ $total_people_entered }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mt-1 uppercase tracking-wide">Orang Masuk</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4 text-center">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12z"/></svg>
                </div>
                <p class="text-3xl font-black text-purple-600 dark:text-purple-400">{{ $total_souvenir_taken }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mt-1 uppercase tracking-wide">Souvenir Diambil</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4 text-center">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <p class="text-3xl font-black text-amber-600 dark:text-amber-400">{{ $grand_total_activity }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mt-1 uppercase tracking-wide">Grand Total</p>
            </div>
        </div>

        {{-- DETAIL TABLE --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between border-b border-gray-100 dark:border-gray-700" style="background:linear-gradient(135deg,#1d4ed8,#2563eb)">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-wide">Detail Riwayat Scan Kehadiran</h2>
                </div>
                <span class="text-xs font-bold text-blue-100 bg-white/20 px-3 py-1 rounded-full">{{ $guests->count() }} data</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="py-3 px-3 text-center text-xs font-bold text-gray-400 uppercase w-12">No</th>
                            <th class="py-3 px-3 text-center text-xs font-bold text-gray-400 uppercase w-14">Foto</th>
                            <th class="py-3 px-4 text-left text-xs font-bold text-gray-400 uppercase">Nama Lengkap</th>
                            <th class="py-3 px-3 text-center text-xs font-bold text-gray-400 uppercase w-28">Jenis</th>
                            <th class="py-3 px-3 text-center text-xs font-bold text-gray-400 uppercase w-20">Pax</th>
                            <th class="py-3 px-3 text-center text-xs font-bold text-gray-400 uppercase w-36">Waktu Scan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($guests as $index => $guest)
                        <tr class="hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-colors">
                            <td class="py-3 px-3 text-center"><span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400 text-xs font-bold">{{ $index + 1 }}</span></td>
                            <td class="py-3 px-3 text-center">
                                @if($guest->photo_path)
                                    <img src="{{ asset('uploads/guests/' . $guest->photo_path) }}"
                                         class="w-10 h-10 object-cover rounded-full mx-auto border-2 border-blue-200 dark:border-blue-700 cursor-pointer hover:scale-110 transition shadow-sm"
                                         @click="previewModalOpen=true;previewImageUrl=$el.src;previewGuestName='{{ $guest->name }}'">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto text-gray-400 dark:text-gray-500 text-xs font-black uppercase">{{ substr($guest->name, 0, 1) }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4"><p class="text-sm font-bold text-gray-900 dark:text-white uppercase">{{ $guest->name }}</p></td>
                            <td class="py-3 px-3 text-center"><span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400">Kehadiran</span></td>
                            <td class="py-3 px-3 text-center"><span class="text-lg font-black text-gray-900 dark:text-white">{{ $guest->actual_pax ?? 0 }}</span></td>
                            <td class="py-3 px-3 text-center">
                                @if($guest->check_in_at)
                                    <p class="font-mono text-xs font-bold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($guest->check_in_at)->timezone('Asia/Jakarta')->format('H:i:s') }}</p>
                                    <p class="font-mono text-[10px] text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($guest->check_in_at)->timezone('Asia/Jakarta')->format('d/m/Y') }}</p>
                                @else
                                    <span class="text-gray-300 dark:text-gray-600">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center"><svg class="w-8 h-8 text-blue-300 dark:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></div>
                                <p class="text-gray-400 dark:text-gray-500 text-sm">Belum ada data scan masuk.</p>
                            </div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL PREVIEW FOTO --}}
        <div x-show="previewModalOpen" class="fixed inset-0 z-[60] overflow-y-auto" style="display:none;">
            <div class="flex items-center justify-center min-h-screen px-4" @click="previewModalOpen=false">
                <div class="fixed inset-0 bg-black/90"></div>
                <div class="relative max-w-3xl w-full z-10" @click.stop>
                    <button @click="previewModalOpen=false" class="absolute -top-10 right-0 text-white hover:text-gray-300 transition"><svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    <img :src="previewImageUrl" class="w-full h-auto max-h-[80vh] object-contain rounded-2xl shadow-2xl border-4 border-white/20">
                    <div class="mt-4 text-center"><p class="text-white text-lg font-bold uppercase tracking-widest bg-black/50 inline-block px-4 py-2 rounded-xl" x-text="previewGuestName"></p></div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>