<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackupDBController extends Controller
{
    public function backup()
    {
        // Define backup file name and path
        $fileName = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        $storagePath = storage_path('app/backup');
        $filePath = $storagePath . '/' . $fileName;

        // Ensure backup directory exists
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        // Get DB connection config
        $connection = config('database.default');
        $db = config("database.connections.$connection.database");
        $user = config("database.connections.$connection.username");
        $pass = config("database.connections.$connection.password");
        $host = config("database.connections.$connection.host");

        // Build the mysqldump command (for MySQL)
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg($user),
            escapeshellarg($pass),
            escapeshellarg($host),
            escapeshellarg($db),
            escapeshellarg($filePath)
        );

        // Execute the command
        $result = null;
        $output = null;
        exec($command . ' 2>&1', $output, $result);

        if ($result !== 0) {
            return back()->with('error', 'Database backup failed. Please check server permissions and configuration.');
        }

        // Return the backup file as a download response
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
