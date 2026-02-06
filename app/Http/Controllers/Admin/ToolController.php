<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ToolController extends Controller
{
    // --- KEŞ TƏMİZLƏMƏ ---

    public function cacheIndex()
    {
        return view('admin.tools.cache');
    }

    public function cacheClear($type)
    {
        switch ($type) {
            case 'application':
                Artisan::call('cache:clear');
                $message = 'Tətbiq keşi təmizləndi.';
                break;
            case 'route':
                Artisan::call('route:clear');
                $message = 'Route (Link) keşi təmizləndi.';
                break;
            case 'config':
                Artisan::call('config:clear');
                $message = 'Konfiqurasiya keşi təmizləndi.';
                break;
            case 'view':
                Artisan::call('view:clear');
                $message = 'Görüntü (View) keşi təmizləndi.';
                break;
            case 'optimize':
                Artisan::call('optimize:clear');
                $message = 'Bütün sistem keşi və optimizasiyalar təmizləndi.';
                break;
            default:
                return redirect()->back()->with('error', 'Yanlış əməliyyat.');
        }

        return redirect()->back()->with('success', $message);
    }

    // --- SİSTEM QULLUĞU (Maintenance) ---

    public function maintenance()
    {
        $systemInfo = [
            'php' => phpversion(),
            'laravel' => app()->version(),
            'server_ip' => request()->server('SERVER_ADDR'),
            'server_software' => request()->server('SERVER_SOFTWARE'),
            'database_size' => $this->getDatabaseSize(),
            'log_size' => $this->getLogSize(),
            'timezone' => config('app.timezone'),
            'time' => now()->format('d.m.Y H:i:s'),
        ];

        return view('admin.tools.maintenance', compact('systemInfo'));
    }

    public function maintenanceAction($action)
    {
        try {
            switch ($action) {
                case 'storage_link':
                    // Symlink varsa silirik, sonra yenidən yaradırıq
                    if (File::exists(public_path('storage'))) {
                        File::delete(public_path('storage'));
                    }
                    Artisan::call('storage:link');
                    return redirect()->back()->with('success', 'Fayl sistemi əlaqələndirildi (Storage Link). Şəkillər görünmürdüsə, indi yoxlayın.');

                case 'clear_logs':
                    $logPath = storage_path('logs/laravel.log');
                    if (File::exists($logPath)) {
                        File::put($logPath, ''); // Faylı boşalt
                        return redirect()->back()->with('success', 'Sistem logları təmizləndi.');
                    }
                    return redirect()->back()->with('info', 'Log faylı onsuz da boşdur.');

                case 'migrate':
                    Artisan::call('migrate', ['--force' => true]);
                    return redirect()->back()->with('success', 'Verilənlər bazası miqrasiyaları yoxlanıldı və yeniləndi.');

                case 'clear_sessions':
                    // Köhnə sessiya fayllarını təmizləmək (əgər file driver istifadə olunursa)
                    // Database driver üçün SQL truncate etmək olar
                    $files = File::files(storage_path('framework/sessions'));
                    foreach ($files as $file) {
                        if ($file->getFilename() !== '.gitignore') {
                            File::delete($file);
                        }
                    }
                    return redirect()->back()->with('success', 'Bütün istifadəçi sessiyaları sıfırlandı (Hamı çıxış etdi).');

                default:
                    return redirect()->back()->with('error', 'Yanlış əməliyyat.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Xəta baş verdi: ' . $e->getMessage());
        }
    }

    // Köməkçi: Log faylının ölçüsü
    private function getLogSize()
    {
        $path = storage_path('logs/laravel.log');
        if (File::exists($path)) {
            $bytes = File::size($path);
            if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
            if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
            return $bytes . ' bytes';
        }
        return '0 KB';
    }

    // Köməkçi: Baza ölçüsü (MySQL üçün)
    private function getDatabaseSize()
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $result = DB::select("SELECT table_schema 'db_name', ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) 'size_mb' FROM information_schema.TABLES WHERE table_schema = ? GROUP BY table_schema", [$dbName]);
            return ($result[0]->size_mb ?? '0') . ' MB';
        } catch (\Exception $e) {
            return 'Bilinmir';
        }
    }
}
