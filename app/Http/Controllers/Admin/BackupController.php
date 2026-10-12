<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;
use RuntimeException;
use Throwable;

class BackupController extends Controller
{
    private function backupDirectory(): string
    {
        return storage_path('app/backups');
    }

    public function index()
    {
        $directory = $this->backupDirectory();

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $backups = collect(File::files($directory))
            ->filter(fn ($file) => in_array(
                strtolower($file->getExtension()),
                ['zip', 'sql', 'json']
            ))
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->map(fn ($file) => [
                'name' => $file->getFilename(),
                'size' => $file->getSize(),
                'date' => date('Y-m-d H:i:s', $file->getMTime()),
                'type' => strtoupper($file->getExtension()),
            ])
            ->values();

        return view('admin.backup.index', compact('backups'));
    }

    public function create(Request $request)
    {
        $directory = $this->backupDirectory();

        try {
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $zip = new ZipArchive();
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "school_management_backup_{$timestamp}.zip";
            $path = $directory . DIRECTORY_SEPARATOR . $filename;

            
$password = config('backup.password');

if (!is_string($password) || $password === '') {
    throw new RuntimeException(
        'Backup password is not configured. Check BACKUP_PASSWORD in .env.'
    );
}


            if ($zip->open($path, ZipArchive::CREATE | ZipArchive::EXCL) !== true) {
                throw new RuntimeException('Unable to create the backup archive.');
            }

            
if (!$zip->setPassword($password)) {
    $zip->close();
    File::delete($path);

    throw new RuntimeException('Unable to configure backup encryption.');
}


            try {
                $connection = config('database.default');
                $database = config("database.connections.{$connection}");

                if (!$database || ($database['driver'] ?? null) !== 'mysql') {
                    throw new RuntimeException(
                        'Automatic database export currently supports MySQL only.'
                    );
                }

                $pdo = \DB::connection($connection)->getPdo();
                $tables = $pdo->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);

                $sql = "-- School Management database backup\n";
                $sql .= "-- Created: " . now()->toDateTimeString() . "\n\n";
                $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

                foreach ($tables as $table) {
                    $quotedTable = '`' . str_replace('`', '``', $table) . '`';

                    $createStatement = $pdo->query(
                        "SHOW CREATE TABLE {$quotedTable}"
                    )->fetch(\PDO::FETCH_ASSOC);

                    $createSql = $createStatement['Create Table']
                        ?? array_values($createStatement)[1]
                        ?? null;

                    if (!$createSql) {
                        throw new RuntimeException("Could not export table: {$table}");
                    }

                    $sql .= "DROP TABLE IF EXISTS {$quotedTable};\n";
                    $sql .= $createSql . ";\n\n";

                    $rows = $pdo->query("SELECT * FROM {$quotedTable}");

                    while ($row = $rows->fetch(\PDO::FETCH_ASSOC)) {
                        $columns = array_map(
                            fn ($column) => '`' . str_replace('`', '``', $column) . '`',
                            array_keys($row)
                        );

                        $values = array_map(function ($value) use ($pdo) {
                            if ($value === null) {
                                return 'NULL';
                            }

                            return $pdo->quote((string) $value);
                        }, array_values($row));

                        $sql .= "INSERT INTO {$quotedTable} (" .
                            implode(', ', $columns) .
                            ") VALUES (" . implode(', ', $values) . ");\n";
                    }

                    $sql .= "\n";
                }

                $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

                
$sqlEntry = "database/database_{$timestamp}.sql";

if (!$zip->addFromString($sqlEntry, $sql) ||
    !$zip->setEncryptionName($sqlEntry, ZipArchive::EM_AES_256)) {
    throw new RuntimeException('Unable to encrypt SQL backup.');
}


                $jsonData = [];
                foreach ($tables as $table) {
                    $jsonData[$table] = $pdo->query(
                        'SELECT * FROM `' . str_replace('`', '``', $table) . '`'
                    )->fetchAll(\PDO::FETCH_ASSOC);
                }

                $zip->addFromString(
                    "database/database_{$timestamp}.json",
                    json_encode(
                        $jsonData,
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
                    )
                );

                $storageDirectories = [
                    storage_path('app/public/id-card-templates'),
                    storage_path('app/public/other-staff'),
                    storage_path('app/public'),
                ];

                $includedFiles = [];
                foreach ($storageDirectories as $storageDirectory) {
                    if (!File::isDirectory($storageDirectory)) {
                        continue;
                    }

                    foreach (File::allFiles($storageDirectory) as $file) {
                        $absolutePath = $file->getRealPath();

                        if (!$absolutePath || isset($includedFiles[$absolutePath])) {
                            continue;
                        }

                        $relative = str_replace(
                            '\\',
                            '/',
                            ltrim(str_replace(storage_path('app') . DIRECTORY_SEPARATOR, '', $absolutePath), '\\/')
                        );

                        if (str_starts_with($relative, '../') || str_contains($relative, '/.env')) {
                            continue;
                        }

                        
$entryName = 'storage/' . $relative;

if (!$zip->addFile($absolutePath, $entryName) ||
    !$zip->setEncryptionName($entryName, ZipArchive::EM_AES_256)) {
    throw new RuntimeException('Unable to encrypt storage file: ' . $entryName);
}

$includedFiles[$absolutePath] = true;

                    }
                }

                $metadata = [
                    'application' => config('app.name'),
                    'created_at' => now()->toIso8601String(),
                    'database' => $database['database'] ?? null,
                    'tables' => count($tables),
                    'storage_files' => count($includedFiles),
                    'format' => 'zip',
                ];

                $zip->addFromString(
                    'backup-info.json',
                    json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                );
            } catch (Throwable $exception) {
                $zip->close();
                File::delete($path);
                throw $exception;
            }

            if (!$zip->close() || !File::exists($path) || File::size($path) === 0) {
                File::delete($path);
                throw new RuntimeException('The backup archive could not be finalized.');
            }

            return redirect()
                ->route('admin.backup.index')
                ->with('success', "Backup created successfully: {$filename}");
        } catch (Throwable $exception) {
            Log::error('Backup creation failed', [
                'message' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('admin.backup.index')
                ->with('error', 'Backup failed. Check the Laravel log for details.');
        }
    }


public function download(string $backup): BinaryFileResponse
{
    $path = $this->safeBackupPath($backup);

    abort_unless(File::isFile($path), 404, 'Backup file not found.');

    // Remove any buffered output before sending binary data.
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    return response()->download(
        $path,
        basename($path),
        [
            'Content-Type' => 'application/zip',
            'X-Content-Type-Options' => 'nosniff',
        ]
    );
}


    public function destroy(string $backup)
    {
        $path = $this->safeBackupPath($backup);

        abort_unless(File::isFile($path), 404, 'Backup file not found.');

        File::delete($path);

        return redirect()
            ->route('admin.backup.index')
            ->with('success', 'Backup deleted successfully.');
    }

    private function safeBackupPath(string $filename): string
    {
        abort_if(
            $filename !== basename($filename) ||
            !preg_match('/\A[a-zA-Z0-9._-]+\.(zip|sql|json)\z/i', $filename),
            404
        );

        return $this->backupDirectory() . DIRECTORY_SEPARATOR . $filename;
    }
}
