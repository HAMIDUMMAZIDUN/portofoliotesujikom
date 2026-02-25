<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('guests')->truncate();
        $vip_guests = [
            'VIP DR. DINAN BAGJA NUGRAHA, MM.KES',
            'VIP ERWIN KURNIAWAN, S.KEP',
            'VIP LETKOL. CKE. MOGI ANGGI ANDRETI, S.S.T., S.H., M.IP',
            'VIP LETKOL. KAV. RADITYA CANDRA ANANTA, S.E DAN IBU FALYA',
            'VIP MAYOR CKE. FATONI APRIYANTO DAN IBU',
            'VIP MAYOR KAV. ARY YULIANTO DAN IBU',
            'VIP BAPAK ANDI ARIS WIJAYA DAN IBU',
            'VIP BAPAK BILLY ASMORO DAN IBU',
            'VIP KOL. INF. GURUH TJAHYONO. S.I.P., M.I.Pol DAN IBU',
            'VIP drg. ROSY WIHARDJA, M.D.Sc dan dr. KINGKY TJANDRAPRAWIRA, SP.OG',
            'VIP KAPT. KAV. SADAD DAN IBU',
        ];

        foreach ($vip_guests as $index => $name) {
            DB::table('guests')->insert([
                'name' => $name,
                'pax' => 2, 
                'is_online_invited' => $index > 6,
                'is_physical_invited' => true,   
                'server_number' => null, 
                'check_in_at' => null, 
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $server1_guests = [
            ['name' => 'Mrs. Sukarni Wongso dan Keluarga', 'pax' => 2],
            ['name' => 'Hary', 'pax' => 2],
            ['name' => 'Mr. Vedry. dan Keluarga', 'pax' => 2],
            ['name' => 'Mrs. Eca dan Keluarga', 'pax' => 2],
            ['name' => 'Mrs. Emma dan Keluarga', 'pax' => 2],
            ['name' => 'Mrs. Umi Chomsiah dan Keluarga', 'pax' => 1],
            ['name' => 'Sadam Warmanah', 'pax' => 1],
            ['name' => 'Mr. Toni Harmianto dan Keluarga', 'pax' => 2],
            ['name' => 'Mr. Ruli dan Keluarga', 'pax' => 2],
            ['name' => 'Bpk Slamet dan Ibu Sairoh', 'pax' => 1],
        ];

        foreach ($server1_guests as $guest) {
            DB::table('guests')->insert([
                'name' => $guest['name'],
                'pax' => $guest['pax'],
                'is_online_invited' => true,
                'is_physical_invited' => false,
                'server_number' => 1,
                'check_in_at' => Carbon::create(2025, 11, 1, 10, rand(0, 59), 0), 
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $present_guests = [
            ['name' => 'Ayip', 'pax' => 1, 'time' => '21:45:15'],
            ['name' => 'BIDANG II HIPMI JABAR', 'pax' => 1, 'time' => '20:42:07'],
            ['name' => 'Mr. Reza Mansyur d', 'pax' => 1, 'time' => '20:41:14'],
            ['name' => 'Maulana Dani', 'pax' => 1, 'time' => '20:20:23'],
            ['name' => 'Bapak Ridwan & Ibu Gita', 'pax' => 2, 'time' => '19:55:26'],
            ['name' => 'Ibu Uung', 'pax' => 3, 'time' => '19:15:11'],
            ['name' => 'Mr. Yusril Sini dan Keluarga', 'pax' => 1, 'time' => '19:15:03'],
            ['name' => 'Fathiyah Alfiani Zulfa', 'pax' => 2, 'time' => '18:53:58'],
            ['name' => 'Mr. Atok Budiardjo dan Keluarga', 'pax' => 1, 'time' => '18:48:37'],
            ['name' => 'Mr. Ronald', 'pax' => 2, 'time' => '18:36:20'],
            ['name' => 'Mr. Finza Albana da', 'pax' => 2, 'time' => '18:36:17'],
        ];

        foreach ($present_guests as $guest) {
            $timeParts = explode(':', $guest['time']);
            $checkInTime = Carbon::create(2025, 11, 1, $timeParts[0], $timeParts[1], $timeParts[2]);

            DB::table('guests')->insert([
                'name' => $guest['name'],
                'pax' => $guest['pax'],
                'is_online_invited' => true,
                'is_physical_invited' => true,
                'server_number' => 2, 
                'check_in_at' => $checkInTime,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}