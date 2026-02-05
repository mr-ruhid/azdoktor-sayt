<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiCredential;

class ApiCredentialSeeder extends Seeder
{
    public function run()
    {
        $apis = [
            // --- Ödəniş Sistemləri ---
            [
                'name' => 'E-point (Azərbaycan)',
                'slug' => 'epoint',
                'category' => 'payment',
                'logo' => 'fas fa-credit-card',
                'credentials' => ['public_key' => '', 'private_key' => '', 'merchant_id' => ''],
                'status' => false,
            ],
            [
                'name' => 'GoldenPay (Azərbaycan)',
                'slug' => 'goldenpay',
                'category' => 'payment',
                'logo' => 'fas fa-money-bill-wave',
                'credentials' => ['auth_key' => '', 'merchant_name' => ''],
                'status' => false,
            ],
            [
                'name' => 'Cryptomus (Kripto)',
                'slug' => 'cryptomus',
                'category' => 'payment',
                'logo' => 'fab fa-bitcoin',
                'credentials' => ['merchant_id' => '', 'payment_key' => ''],
                'status' => false,
            ],
            [
                'name' => 'Payoneer',
                'slug' => 'payoneer',
                'category' => 'payment',
                'logo' => 'fas fa-wallet',
                'credentials' => ['client_id' => '', 'client_secret' => ''],
                'status' => false,
            ],

            // --- Xəritə və Naviqasiya ---
            [
                'name' => 'Google Maps API',
                'slug' => 'google_maps',
                'category' => 'map',
                'logo' => 'fas fa-map-marked-alt',
                'credentials' => ['api_key' => ''],
                'status' => false,
            ],
            [
                'name' => 'Yandex Maps API',
                'slug' => 'yandex_maps',
                'category' => 'map',
                'logo' => 'fas fa-map-pin',
                'credentials' => ['api_key' => ''],
                'status' => false,
            ],

            // --- Sosial Giriş (Auth) ---
            [
                'name' => 'Google Login',
                'slug' => 'google_login',
                'category' => 'auth',
                'logo' => 'fab fa-google',
                'credentials' => ['client_id' => '', 'client_secret' => '', 'redirect_uri' => ''],
                'status' => false,
            ],
            [
                'name' => 'Facebook Login',
                'slug' => 'facebook_login',
                'category' => 'auth',
                'logo' => 'fab fa-facebook',
                'credentials' => ['app_id' => '', 'app_secret' => '', 'redirect_uri' => ''],
                'status' => false,
            ],
            [
                'name' => 'Yandex Login',
                'slug' => 'yandex_login',
                'category' => 'auth',
                'logo' => 'fab fa-yandex',
                'credentials' => ['client_id' => '', 'client_secret' => '', 'redirect_uri' => ''],
                'status' => false,
            ],

            // --- Təhlükəsizlik və Digər ---
            [
                'name' => 'Google reCAPTCHA v3',
                'slug' => 'recaptcha',
                'category' => 'security',
                'logo' => 'fas fa-shield-alt',
                'credentials' => ['site_key' => '', 'secret_key' => ''],
                'status' => false,
            ],
            [
                'name' => 'Google Analytics 4',
                'slug' => 'analytics',
                'category' => 'other',
                'logo' => 'fas fa-chart-line',
                'credentials' => ['measurement_id' => ''],
                'status' => false,
            ],
            [
                'name' => 'WhatsApp Business API',
                'slug' => 'whatsapp',
                'category' => 'other',
                'logo' => 'fab fa-whatsapp',
                'credentials' => ['phone_number_id' => '', 'access_token' => ''],
                'status' => false,
            ],
        ];

        foreach ($apis as $api) {
            ApiCredential::firstOrCreate(['slug' => $api['slug']], $api);
        }
    }
}
