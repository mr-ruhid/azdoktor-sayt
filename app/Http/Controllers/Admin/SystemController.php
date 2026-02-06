<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class SystemController extends Controller
{
    // --- YENİLƏMƏ SİSTEMİ ---

    public function updateIndex()
    {
        return view('admin.system.update');
    }

    public function updateStore(Request $request)
    {
        $request->validate([
            'update_file' => 'required|mimes:zip|max:102400', // Maks 100MB
        ]);

        $file = $request->file('update_file');
        $zipPath = $file->getRealPath();

        $zip = new ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            try {
                $extractPath = base_path();
                $zip->extractTo($extractPath);
                $zip->close();

                Artisan::call('migrate', ['--force' => true]);
                Artisan::call('optimize:clear');
                Artisan::call('view:clear');
                Artisan::call('config:clear');

                return redirect()->back()->with('success', 'Sistem uğurla yeniləndi.');

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Yeniləmə zamanı xəta baş verdi: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'ZIP faylı açıla bilmədi.');
        }
    }

    // --- EHTİYAT NÜSXƏ (BACKUP) SİSTEMİ ---

    public function backupsIndex()
    {
        $path = storage_path('app/backups');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $files = File::files($path);
        $backups = [];

        foreach ($files as $file) {
            $backups[] = [
                'name' => $file->getFilename(),
                'size' => number_format($file->getSize() / 1048576, 2) . ' MB',
                'date' => date('d.m.Y H:i:s', $file->getMTime()),
                'path' => $file->getPathname()
            ];
        }

        usort($backups, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return view('admin.system.backups', compact('backups'));
    }

    public function backupStore(Request $request)
    {
        // Zaman aşımını artırırıq (Tam backup üçün)
        set_time_limit(300); // 5 dəqiqə
        ini_set('memory_limit', '512M');

        try {
            $type = $request->input('type', 'db'); // 'db' və ya 'full'
            $path = storage_path('app/backups');

            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            // 1. Öncə hər iki halda da DB backup alırıq (temp olaraq)
            $dbFilename = 'db-backup-' . date('Y-m-d-H-i-s') . '.sql';
            $dbFullPath = $path . '/' . $dbFilename;
            $this->backupDatabaseTables($dbFullPath);

            if ($type === 'db') {
                return redirect()->back()->with('success', 'Verilənlər bazası nüsxəsi yaradıldı: ' . $dbFilename);
            }

            if ($type === 'full') {
                $zipFilename = 'full-backup-' . date('Y-m-d-H-i-s') . '.zip';
                $zipFullPath = $path . '/' . $zipFilename;

                $zip = new ZipArchive();
                if ($zip->open($zipFullPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

                    // a) DB faylını zip-in içinə atırıq
                    $zip->addFile($dbFullPath, 'database.sql');

                    // b) Bütün sayt fayllarını zip-ə atırıq
                    $rootPath = base_path();
                    $files = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($rootPath),
                        RecursiveIteratorIterator::LEAVES_ONLY
                    );

                    foreach ($files as $name => $file) {
                        if (!$file->isDir()) {
                            $filePath = $file->getRealPath();
                            $relativePath = substr($filePath, strlen($rootPath) + 1);

                            // Bəzi qovluqları istisna edirik (recursion və ya lazımsız fayllar)
                            if (
                                str_contains($filePath, 'storage/app/backups') || // Backup qovluğunu təkrar zip-ləmə
                                str_contains($filePath, '.git') ||
                                str_contains($filePath, 'node_modules')
                            ) {
                                continue;
                            }

                            $zip->addFile($filePath, $relativePath);
                        }
                    }
                    $zip->close();

                    // Temp DB faylını silirik, çünki zip-in içində var
                    File::delete($dbFullPath);

                    return redirect()->back()->with('success', 'Tam sistem nüsxəsi yaradıldı: ' . $zipFilename);
                } else {
                    return redirect()->back()->with('error', 'Zip faylı yaradıla bilmədi.');
                }
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Backup xətası: ' . $e->getMessage());
        }
    }

    public function backupDownload($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        if (File::exists($path)) {
            return response()->download($path);
        }
        return redirect()->back()->with('error', 'Fayl tapılmadı.');
    }

    public function backupDestroy($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        if (File::exists($path)) {
            File::delete($path);
            return redirect()->back()->with('success', 'Ehtiyat nüsxə silindi.');
        }
        return redirect()->back()->with('error', 'Fayl tapılmadı.');
    }

    // Restore (Geri Yükıəmə) - Yalnız SQL üçün
    public function backupRestore($filename)
    {
        // Zaman aşımını artırırıq
        set_time_limit(300);

        if (!str_ends_with($filename, '.sql')) {
            return redirect()->back()->with('error', 'Yalnız .sql faylları geri yüklənə bilər.');
        }

        $path = storage_path('app/backups/' . $filename);

        if (File::exists($path)) {
            try {
                // SQL faylını oxuyuruq
                $sql = File::get($path);

                // Bazanı təmizləyib yenidən yazmaq üçün
                // DB::unprepared böyük sorğuları icra etmək üçündür
                DB::unprepared($sql);

                // Cache təmizlə
                Artisan::call('optimize:clear');

                return redirect()->back()->with('success', 'Verilənlər bazası uğurla geri yükləndi.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Restore xətası: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Fayl tapılmadı.');
    }

    // Custom SQL Dump Funksiyası
    private function backupDatabaseTables($filePath)
    {
        $tables = DB::select('SHOW TABLES');
        $return = "";

        // Foreign Key yoxlamasını söndürürük (import xətalarını önləmək üçün)
        $return .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $tableName = reset($table);
            $result = DB::select("SELECT * FROM `$tableName`");

            $return .= "DROP TABLE IF EXISTS `$tableName`;\n";
            $row2 = DB::select("SHOW CREATE TABLE `$tableName`");
            $createTable = (array) $row2[0];
            $return .= "\n\n" . $createTable['Create Table'] . ";\n\n";

            foreach ($result as $row) {
                $return .= "INSERT INTO `$tableName` VALUES(";
                $rowArray = (array) $row;
                $values = [];
                foreach ($rowArray as $key => $value) {
                    if (is_null($value)) {
                        $values[] = "NULL";
                    } else {
                        $value = addslashes($value);
                        $value = str_replace("\n", "\\n", $value);
                        $values[] = '"' . $value . '"';
                    }
                }
                $return .= implode(',', $values);
                $return .= ");\n";
            }
            $return .= "\n\n\n";
        }

        $return .= "SET FOREIGN_KEY_CHECKS=1;\n";

        File::put($filePath, $return);
    }
}
