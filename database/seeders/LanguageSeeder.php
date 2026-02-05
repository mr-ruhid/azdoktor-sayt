<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run()
    {
        Language::create([
            'name' => 'Azərbaycan',
            'code' => 'az',
            'direction' => 'ltr',
            'is_default' => true,
            'status' => true,
        ]);

        Language::create([
            'name' => 'English',
            'code' => 'en',
            'direction' => 'ltr',
            'is_default' => false,
            'status' => true,
        ]);
    }
}
