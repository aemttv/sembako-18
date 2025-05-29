<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackupDBController extends Controller
{
    public function index()
    {
        $fileName = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        $storagePath = storage_path('app/backup');
        $filePath = $storagePath . '/' . $fileName;

        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $connection = config('database.default');
        $db = config("database.connections.$connection.database");
        $user = config("database.connections.$connection.username");
        $pass = config("database.connections.$connection.password");
        $host = config("database.connections.$connection.host");

        // Use full path to mysqldump if needed (especially on Windows/XAMPP)
        $mysqldump = 'mysqldump';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Adjust this path if your mysqldump.exe is elsewhere
            $mysqldump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
        }

        // Build the command, omitting --password if empty
        if (!empty($pass)) {
            $command = sprintf(
                '"%s" --user=%s --password=%s --host=%s %s > "%s"',
                $mysqldump,
                escapeshellarg($user),
                escapeshellarg($pass),
                escapeshellarg($host),
                escapeshellarg($db),
                $filePath
            );
        } else {
            $command = sprintf(
                '"%s" --user=%s --host=%s %s > "%s"',
                $mysqldump,
                escapeshellarg($user),
                escapeshellarg($host),
                escapeshellarg($db),
                $filePath
            );
        }

        $result = null;
        $output = null;
        exec($command . ' 2>&1', $output, $result);

        if ($result !== 0) {
            dd([
                'command' => $command,
                'output' => $output,
                'result' => $result,
                'file_exists' => file_exists($filePath),
            ]);
        }

        return redirect()->back()->with('success', 'Database backup created successfully! File: ' . $fileName);
    }
}
