<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use PDO;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Route name of the backup page. Must match routes/web.php.
     * Your Blade view uses backup.create / backup.download / backup.destroy,
     * so the index route is expected to be backup.index.
     * Verify with: php artisan route:list --name=backup
     */
    private const INDEX_ROUTE = 'backup.index';

    private function backupDirectory(): string
    {
        return storage_path('app/backups');
    }

    private function ensureBackupDirectory(): string
    {
        $directory = $this->backupDirectory();

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        return $directory;
    }

    public function index()
    {
        $directory = $this->ensureBackupDirectory();

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
        @set_time_limit(0);

        $zip        = null;
        $zipOpen    = false;
        $tempFiles  = [];
        $path       = null;
        $success    = false;

        try {
            $directory = $this->ensureBackupDirectory();

            // ---- Password -------------------------------------------------
            $password = config('backup.password');

            if (!is_string($password) || $password === '') {
                throw new RuntimeException(
                    'Backup password is not configured. Add BACKUP_PASSWORD to .env, '
                    . "set 'password' => env('BACKUP_PASSWORD') in config/backup.php, "
                    . 'then run: php artisan config:clear'
                );
            }

            // ---- Database driver check -----------------------------------
            $connection = config('database.default');
            $database   = config("database.connections.{$connection}");

            if (!$database || !in_array($database['driver'] ?? null, ['mysql', 'mariadb'], true)) {
                throw new RuntimeException(
                    'Automatic database export currently supports MySQL/MariaDB only.'
                );
            }

            if (!class_exists(ZipArchive::class)) {
                throw new RuntimeException('The PHP zip extension is not installed.');
            }

            // ---- Open archive ---------------------------------------------
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename  = "school_management_backup_{$timestamp}.zip";
            $path      = $directory . DIRECTORY_SEPARATOR . $filename;

            $zip    = new ZipArchive();
            $result = $zip->open($path, ZipArchive::CREATE | ZipArchive::EXCL);

            if ($result !== true) {
                throw new RuntimeException(
                    "Unable to create the backup archive (ZipArchive error code {$result})."
                );
            }
            $zipOpen = true;

            if (!$zip->setPassword($password)) {
                throw new RuntimeException('Unable to configure backup encryption.');
            }

            // ---- Export database to temp files (streamed, low memory) ----
            $sqlTemp  = $this->makeTempFile();
            $jsonTemp = $this->makeTempFile();
            $tempFiles[] = $sqlTemp;
            $tempFiles[] = $jsonTemp;

            $tableCount = $this->exportDatabase($connection, $sqlTemp, $jsonTemp);

            $this->addFileEncrypted($zip, $sqlTemp, "database/database_{$timestamp}.sql");
            $this->addFileEncrypted($zip, $jsonTemp, "database/database_{$timestamp}.json");

            // ---- Uploaded files from storage/app/public -------------------
            $publicDirectory = storage_path('app/public');
            $storageFiles    = 0;

            if (File::isDirectory($publicDirectory)) {
                $root = rtrim(str_replace('\\', '/', realpath($publicDirectory)), '/') . '/';

                foreach (File::allFiles($publicDirectory) as $file) {
                    $absolutePath = $file->getRealPath();

                    if (!$absolutePath || !is_readable($absolutePath)) {
                        continue;
                    }

                    $normalized = str_replace('\\', '/', $absolutePath);

                    if (!str_starts_with($normalized, $root)) {
                        continue; // symlink pointing outside the folder
                    }

                    $relative = substr($normalized, strlen($root));

                    if (str_contains($relative, '/.env') || basename($relative) === '.env') {
                        continue;
                    }

                    $this->addFileEncrypted($zip, $absolutePath, 'storage/public/' . $relative);
                    $storageFiles++;
                }
            }

            // ---- Metadata -------------------------------------------------
            $metadata = json_encode([
                'application'   => config('app.name'),
                'created_at'    => now()->toIso8601String(),
                'database'      => $database['database'] ?? null,
                'tables'        => $tableCount,
                'storage_files' => $storageFiles,
                'format'        => 'zip (AES-256)',
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            if (!$zip->addFromString('backup-info.json', $metadata)
                || !$zip->setEncryptionName('backup-info.json', ZipArchive::EM_AES_256)) {
                throw new RuntimeException('Unable to add backup metadata.');
            }

            // ---- Finalize (files are actually read & written here) -------
            $zipOpen = false;
            if (!$zip->close()) {
                throw new RuntimeException(
                    'The backup archive could not be finalized (check disk space and file permissions).'
                );
            }

            if (!File::exists($path) || File::size($path) === 0) {
                throw new RuntimeException('The backup archive is empty.');
            }

            $success = true;

            return redirect()
                ->route(self::INDEX_ROUTE)
                ->with('success', "Backup created successfully: {$filename}");
        } catch (Throwable $exception) {
            Log::error('Backup creation failed', [
                'exception' => get_class($exception),
                'message'   => $exception->getMessage(),
                'file'      => $exception->getFile(),
                'line'      => $exception->getLine(),
            ]);

            $message = config('app.debug')
                ? 'Backup failed: ' . $exception->getMessage()
                : 'Backup failed. Check the Laravel log for details.';

            return redirect()
                ->route(self::INDEX_ROUTE)
                ->with('error', $message);
        } finally {
            // Close the zip if it's still open (error path), then clean up.
            if ($zip instanceof ZipArchive && $zipOpen) {
                @$zip->close();
            }

            if (!$success && $path && File::exists($path)) {
                File::delete($path);
            }

            foreach ($tempFiles as $temp) {
                if (is_file($temp)) {
                    @unlink($temp);
                }
            }
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
                'Content-Type'           => 'application/zip',
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
            ->route(self::INDEX_ROUTE)
            ->with('success', 'Backup deleted successfully.');
    }

    // ---------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------

    private function safeBackupPath(string $filename): string
    {
        abort_if(
            $filename !== basename($filename) ||
            !preg_match('/\A[a-zA-Z0-9._-]+\.(zip|sql|json)\z/i', $filename),
            404
        );

        return $this->backupDirectory() . DIRECTORY_SEPARATOR . $filename;
    }

    private function makeTempFile(): string
    {
        $temp = tempnam(sys_get_temp_dir(), 'bk_');

        if ($temp === false) {
            throw new RuntimeException('Unable to create a temporary file for the backup.');
        }

        return $temp;
    }

    private function addFileEncrypted(ZipArchive $zip, string $absolutePath, string $entryName): void
    {
        if (!$zip->addFile($absolutePath, $entryName)) {
            throw new RuntimeException("Unable to add file to backup: {$entryName}");
        }

        if (!$zip->setEncryptionName($entryName, ZipArchive::EM_AES_256)) {
            throw new RuntimeException("Unable to encrypt file in backup: {$entryName}");
        }
    }

    /**
     * Streams every base table to a .sql file and a .json file, row by row,
     * so large databases don't exhaust PHP's memory limit.
     *
     * @return int number of tables exported
     */
    private function exportDatabase(string $connection, string $sqlPath, string $jsonPath): int
    {
        $pdo = DB::connection($connection)->getPdo();

        // Base tables only (views would break SHOW CREATE TABLE handling).
        $tables = $pdo
            ->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'")
            ->fetchAll(PDO::FETCH_COLUMN, 0);

        $sql  = fopen($sqlPath, 'wb');
        $json = fopen($jsonPath, 'wb');

        if (!$sql || !$json) {
            throw new RuntimeException('Unable to open temporary export files.');
        }

        // SUBSTITUTE avoids "Malformed UTF-8" exceptions on binary/blob columns.
        $jsonFlags = JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE;

        try {
            fwrite($sql, "-- School Management database backup\n");
            fwrite($sql, '-- Created: ' . now()->toDateTimeString() . "\n\n");
            fwrite($sql, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            fwrite($json, "{\n");
            $firstTable = true;

            foreach ($tables as $table) {
                $quotedTable = '`' . str_replace('`', '``', $table) . '`';

                $createRow = $pdo->query("SHOW CREATE TABLE {$quotedTable}")->fetch(PDO::FETCH_NUM);
                $createSql = $createRow[1] ?? null;

                if (!$createSql) {
                    throw new RuntimeException("Could not export table: {$table}");
                }

                fwrite($sql, "DROP TABLE IF EXISTS {$quotedTable};\n");
                fwrite($sql, $createSql . ";\n\n");

                fwrite(
                    $json,
                    ($firstTable ? '' : ",\n")
                    . '  ' . json_encode((string) $table, $jsonFlags) . ': ['
                );
                $firstTable = false;

                // Stream rows with an unbuffered query, then restore the setting.
                $previous = $pdo->getAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY);
                $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);

                try {
                    $rows     = $pdo->query("SELECT * FROM {$quotedTable}");
                    $firstRow = true;

                    while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
                        $columns = array_map(
                            fn ($c) => '`' . str_replace('`', '``', $c) . '`',
                            array_keys($row)
                        );

                        $values = array_map(
                            fn ($v) => $v === null ? 'NULL' : $pdo->quote((string) $v),
                            array_values($row)
                        );

                        fwrite(
                            $sql,
                            "INSERT INTO {$quotedTable} (" . implode(', ', $columns)
                            . ') VALUES (' . implode(', ', $values) . ");\n"
                        );

                        fwrite(
                            $json,
                            ($firstRow ? "\n" : ",\n") . '    ' . json_encode($row, $jsonFlags)
                        );
                        $firstRow = false;
                    }

                    $rows->closeCursor();
                } finally {
                    $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, $previous);
                }

                fwrite($json, $firstRow ? ']' : "\n  ]");
                fwrite($sql, "\n");
            }

            fwrite($json, "\n}\n");
            fwrite($sql, "SET FOREIGN_KEY_CHECKS=1;\n");
        } finally {
            fclose($sql);
            fclose($json);
        }

        return count($tables);
    }
}