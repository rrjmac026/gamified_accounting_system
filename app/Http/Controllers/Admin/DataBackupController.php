<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 

use App\Models\DataBackup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DataBackupController extends Controller
{
    public function index()
    {
        // Admin view: list of all backups
        $backups = DataBackup::latest()->get();
        return view('admin.backups.index', compact('backups'));
    }

    public function show(DataBackup $dataBackup)
    {
        // Optional: View individual backup details
        return view('admin.backups.show', compact('dataBackup'));
    }

    // Spatie command ni siya for auto backup "\Artisan::call('backup:run');"
    public function backupNow()
    {
        try {
            // Simulate file creation (replace with real logic or use Spatie)
            $filename = 'backup_' . now()->format('Y_m_d_His') . '.sql';
            $path = storage_path('app/backups/' . $filename);

            // Make sure the directory exists
            File::ensureDirectoryExists(storage_path('app/backups'));

            // Run simple database dump (make sure `mysqldump` is installed)
            $db = config('database.connections.mysql');
            $command = "mysqldump -u {$db['username']} -p'{$db['password']}' {$db['database']} > $path";
            exec($command);

            // Log it in your model
            DataBackup::create([
                'backup_name' => $filename,
                'file_path' => $path,
                'backup_type' => 'full',
                'file_size' => File::size($path),
                'status' => 'completed',
                'created_by' => auth()->id(),
                'backup_date' => now(),
                'retention_until' => now()->addDays(30),
            ]);

            return back()->with('success', 'Backup completed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function destroy(DataBackup $dataBackup)
    {
        // Optional: delete backup record + file
        if (file_exists($dataBackup->file_path)) {
            unlink($dataBackup->file_path);
        }

        $dataBackup->delete();
        return redirect()->back()->with('success', 'Backup deleted.');
    }
}
