<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sidebar;

class SidebarSeeder extends Seeder
{
    public function run()
    {
        // 1. PC Yan Panel (Sol tərəf)
        Sidebar::firstOrCreate(
            ['type' => 'pc_sidebar'],
            [
                'name' => 'PC Yan Panel (Sidebar)',
                'settings' => [
                    'background_color' => '#ffffff',
                    'text_color' => '#333333',
                    'width' => '280px',
                    'show_language_switcher' => true,
                    'show_user_profile' => true
                ],
                'status' => true
            ]
        );

        // 2. Mobil Navbar (Yuxarı hissə)
        Sidebar::firstOrCreate(
            ['type' => 'mobile_navbar'],
            [
                'name' => 'Mobil Navbar',
                'settings' => [
                    'background_color' => '#ffffff',
                    'text_color' => '#333333',
                    'show_search' => true,
                    'sticky' => true
                ],
                'status' => true
            ]
        );
    }
}
