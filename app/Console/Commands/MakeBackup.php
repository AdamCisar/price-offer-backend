<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use ZipArchive;
use Carbon\Carbon;

class MakeBackup extends Command
{
    protected $signature = 'backup:make {--files=true} {--db=true}';
    protected $description = 'Vytvorí backup DB a vybraných súborov.';

    public function handle()
    {
        $now = Carbon::now()->format('Y-m-d_H-i-s');
        $tmpDir = storage_path("app/backups/tmp_{$now}");
        @mkdir($tmpDir, 0755, true);

        // 1) DB dump
        if ($this->option('db')) {
            $dbFilename = "db_{$now}.sql";
            $dbFilepath = "{$tmpDir}/{$dbFilename}";

            $dbConnection = config('database.default');
            $dbConfig = config("database.connections.{$dbConnection}");

            // MySQL example
            $user = escapeshellarg($dbConfig['username']);
            $pass = $dbConfig['password'] !== null ? '-p' . escapeshellarg($dbConfig['password']) : '';
            $host = escapeshellarg($dbConfig['host']);
            $port = isset($dbConfig['port']) ? '-P ' . escapeshellarg($dbConfig['port']) : '';
            $db = escapeshellarg($dbConfig['database']);

            $mysqldump = env('DB_DUMP_PATH', '/usr/bin/mysqldump');

            $cmd = [
                $mysqldump,
                "-h", $dbConfig['host'],
                "-u", $dbConfig['username'],
            ];

            if (!empty($dbConfig['password'])) {
                $cmd[] = "-p{$dbConfig['password']}";
            }
            if (!empty($dbConfig['port'])) {
                $cmd[] = "-P{$dbConfig['port']}";
            }
            $cmd[] = $dbConfig['database'];
            $process = new Process($cmd);
            $process->setTimeout(300);
            $process->run();

            if (!$process->isSuccessful()) {
                $this->error('Chyba pri mysqldump: ' . $process->getErrorOutput());
                // cleanup tmp
                $this->deleteFolder($tmpDir);
                return 1;
            }
            file_put_contents($dbFilepath, $process->getOutput());
            $this->info("DB dump vytvorený: {$dbFilepath}");
        }

        // 2) Files (napr. public/uploads a storage/app)
        if ($this->option('files')) {
            // skopíruj alebo zabal vybrané adresáre
            $filesToBackup = [
                public_path('uploads'),
                storage_path('app/public')
            ];

            foreach ($filesToBackup as $src) {
                if (!is_dir($src)) continue;
                $dest = "{$tmpDir}/" . basename($src);
                $this->recurseCopy($src, $dest);
            }
        }

        // 3) Zip všetko
        $zipName = "backup_{$now}.zip";
        $zipPath = storage_path('app/backups/') . $zipName;
        @mkdir(dirname($zipPath), 0755, true);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            $this->error('Nepodarilo sa vytvoriť ZIP.');
            $this->deleteFolder($tmpDir);
            return 1;
        }

        $this->addFolderToZip($tmpDir, $zip);
        $zip->close();

        // 4) cleanup temp
        $this->deleteFolder($tmpDir);

        $this->info("Backup uložený: {$zipPath}");

        // 5) retention - odstrániť staré zálohy
        $this->cleanupOldBackups();

        return 0;
    }

    protected function recurseCopy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst, 0755, true);
        while(false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $s = $src . '/' . $file;
                $d = $dst . '/' . $file;
                if (is_dir($s)) {
                    $this->recurseCopy($s, $d);
                } else {
                    copy($s, $d);
                }
            }
        }
        closedir($dir);
    }

    protected function addFolderToZip($folder, ZipArchive $zip, $base = '') {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folder),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = $base . substr($filePath, strlen($folder) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    protected function deleteFolder($folder)
    {
        if (!is_dir($folder)) return;
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folder, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }
        rmdir($folder);
    }

    protected function cleanupOldBackups()
    {
        $path = storage_path('app/backups');
        $days = (int) env('BACKUP_RETENTION_DAYS', 14);
        if (!is_dir($path)) return;
        foreach (new \DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot()) continue;
            if ($fileInfo->isFile()) {
                $age = (time() - $fileInfo->getMTime()) / 86400;
                if ($age > $days) {
                    unlink($fileInfo->getRealPath());
                }
            }
        }
    }
}
