<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';

    protected $description = 'Create a database backup';

    public function handle(): int
    {
        $backupPath = storage_path('app/backups');

        // Create backup directory if it doesn't exist
        if (!File::isDirectory($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $filename = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
        $filepath = $backupPath . '/' . $filename;

        try {
            $database = config('database.connections.' . config('database.default'));
            $host = $database['host'] ?? '127.0.0.1';
            $port = $database['port'] ?? 3306;
            $databaseName = $database['database'] ?? '';
            $username = $database['username'] ?? '';
            $password = $database['password'] ?? '';

            if (empty($databaseName)) {
                $this->error('Database name not configured.');
                return self::FAILURE;
            }

            // Build mysqldump command
            $command = sprintf(
                'mysqldump -h %s -P %s -u %s %s > %s 2>&1',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($databaseName),
                escapeshellarg($filepath)
            );

            // Add password if set
            if (!empty($password)) {
                $command = sprintf(
                    'mysqldump -h %s -P %s -u %s --password=%s %s > %s 2>&1',
                    escapeshellarg($host),
                    escapeshellarg($port),
                    escapeshellarg($username),
                    escapeshellarg($password),
                    escapeshellarg($databaseName),
                    escapeshellarg($filepath)
                );
            }

            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                $this->error('Backup failed: ' . implode("\n", $output));
                return self::FAILURE;
            }

            // Compress the backup
            $gzFilepath = $filepath . '.gz';
            $fileContent = file_get_contents($filepath);
            $gzContent = gzencode($fileContent);
            file_put_contents($gzFilepath, $gzContent);
            unlink($filepath);

            // Keep only last 30 backups
            $backups = glob($backupPath . '/backup_*.sql.gz');
            if (count($backups) > 30) {
                usort($backups, fn ($a, $b) => filemtime($a) - filemtime($b));
                $toDelete = array_slice($backups, 0, count($backups) - 30);
                foreach ($toDelete as $old) {
                    unlink($old);
                }
            }

            $this->info("Backup created successfully: {$gzFilepath}");
            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Backup failed: {$e->getMessage()}");
            return self::FAILURE;
        }
    }
}
