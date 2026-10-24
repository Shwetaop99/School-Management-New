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
    private const INDEX_ROUTE = 'admin.backup.index';
    private const RESTORE_ROUTE = 'admin.backup.restore';

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
        $backups = collect(File::files($this->ensureBackupDirectory()))
            ->filter(fn ($file) => in_array(
                strtolower($file->getExtension()),
                ['zip', 'sql', 'json'],
                true
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

    public function showRestore()
    {
        $backups = collect(File::files($this->ensureBackupDirectory()))
            ->filter(fn ($file) => strtolower($file->getExtension()) === 'zip')
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->map(fn ($file) => [
                'name' => $file->getFilename(),
                'size' => $file->getSize(),
                'date' => date('Y-m-d H:i:s', $file->getMTime()),
            ])
            ->values();

        return view('admin.backup.restore', compact('backups'));
    }

    public function create(Request $request)
    {
        @set_time_limit(0);

        try {
            $filename = $this->createBackupArchive('school_management_backup');
            return redirect()->route(self::INDEX_ROUTE)
                ->with('success', "Backup created successfully: {$filename}");
        } catch (Throwable $exception) {
            Log::error('Backup creation failed', [
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
            ]);

            return redirect()->route(self::INDEX_ROUTE)->with(
                'error',
                config('app.debug')
                    ? 'Backup failed: ' . $exception->getMessage()
                    : 'Backup failed. Check the Laravel log for details.'
            );
        }
    }

    /**
     * Creates a ZIP backup and returns its filename. Used by normal backup
     * creation and by restore as a pre-change safety snapshot.
     */
    private function createBackupArchive(string $prefix): string
    {
        $password = config('backup.password');
        if (!is_string($password) || $password === '') {
            throw new RuntimeException(
                'Backup password is not configured. Set BACKUP_PASSWORD in .env and run php artisan config:clear.'
            );
        }

        if (!class_exists(ZipArchive::class)) {
            throw new RuntimeException('The PHP ZIP extension is not installed.');
        }

        $connection = config('database.default');
        $database = config("database.connections.{$connection}");

        if (!is_array($database) || !in_array($database['driver'] ?? null, ['mysql', 'mariadb'], true)) {
            throw new RuntimeException('Automatic backup supports MySQL/MariaDB only.');
        }

        $directory = $this->ensureBackupDirectory();
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = $prefix . '_' . $timestamp . '_' . bin2hex(random_bytes(3)) . '.zip';
        $archivePath = $directory . DIRECTORY_SEPARATOR . $filename;
        $sqlTemp = tempnam(sys_get_temp_dir(), 'school_sql_');
        $jsonTemp = tempnam(sys_get_temp_dir(), 'school_json_');

        if ($sqlTemp === false || $jsonTemp === false) {
            if (is_string($sqlTemp) && is_file($sqlTemp)) @unlink($sqlTemp);
            if (is_string($jsonTemp) && is_file($jsonTemp)) @unlink($jsonTemp);
            throw new RuntimeException('Unable to create temporary export files.');
        }

        $zip = new ZipArchive();
        $opened = false;
        $success = false;

        try {
            $tableCount = $this->exportDatabase($connection, $sqlTemp, $jsonTemp);

            $result = $zip->open($archivePath, ZipArchive::CREATE | ZipArchive::EXCL);
            if ($result !== true) {
                throw new RuntimeException("Unable to create ZIP archive (code {$result}).");
            }
            $opened = true;

            if (!$zip->setPassword($password)) {
                throw new RuntimeException('Unable to configure ZIP encryption.');
            }

            $sqlEntry = "database/database_{$timestamp}.sql";
            $jsonEntry = "database/database_{$timestamp}.json";
            $this->addFileEncrypted($zip, $sqlTemp, $sqlEntry);
            $this->addFileEncrypted($zip, $jsonTemp, $jsonEntry);

            $storageCount = 0;
            $publicDirectory = storage_path('app/public');

            if (File::isDirectory($publicDirectory)) {
                $realRoot = realpath($publicDirectory);
                if ($realRoot !== false) {
                    $root = rtrim(str_replace('\\', '/', $realRoot), '/') . '/';

                    foreach (File::allFiles($publicDirectory) as $file) {
                        $absolute = $file->getRealPath();
                        if (!$absolute || !is_readable($absolute)) {
                            continue;
                        }

                        $normalized = str_replace('\\', '/', $absolute);
                        if (!str_starts_with($normalized, $root)) {
                            continue;
                        }

                        $relative = substr($normalized, strlen($root));
                        if ($relative === '' || basename($relative) === '.env' || str_contains($relative, '/.env')) {
                            continue;
                        }

                        $this->addFileEncrypted($zip, $absolute, 'storage/public/' . $relative);
                        $storageCount++;
                    }
                }
            }

            $metadata = json_encode([
                'application' => config('app.name'),
                'created_at' => now()->toIso8601String(),
                'database' => $database['database'] ?? null,
                'tables' => $tableCount,
                'storage_files' => $storageCount,
                'format' => 'zip (AES-256)',
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

            if (!$zip->addFromString('backup-info.json', $metadata) ||
                !$zip->setEncryptionName('backup-info.json', ZipArchive::EM_AES_256)) {
                throw new RuntimeException('Unable to add encrypted backup metadata.');
            }

            if (!$zip->close()) {
                $opened = false;
                throw new RuntimeException('Unable to finalize ZIP archive.');
            }
            $opened = false;

            if (!File::isFile($archivePath) || File::size($archivePath) < 1) {
                throw new RuntimeException('Created backup archive is empty.');
            }

            // Some environments have produced this exact four-byte prefix before
            // the ZIP signature. Normalize only that known prefix; do not guess
            // or strip arbitrary bytes from an archive.
            // Repair only the two exact unexpected prefixes observed.
            $prefixHandle = fopen($archivePath, 'rb');

            if ($prefixHandle === false) {
                throw new RuntimeException(
                    'Unable to inspect the generated ZIP archive.'
                );
            }

            try {
                $archivePrefix = fread($prefixHandle, 4);
            } finally {
                fclose($prefixHandle);
            }

            $skipBytes = 0;

            if ($archivePrefix === "\x0A\xEF\xBB\xBF") {
                // Leading newline followed by a UTF-8 BOM.
                $skipBytes = 4;
            } elseif ($archivePrefix === "\x0A\x50\x4B\x03") {
                // Leading newline followed by the ZIP signature.
                $skipBytes = 1;
            }

            if ($skipBytes > 0) {
                $normalizedPath = tempnam($directory, 'zipfix_');

                if ($normalizedPath === false) {
                    throw new RuntimeException(
                        'Unable to create a temporary ZIP repair file.'
                    );
                }

                $input = fopen($archivePath, 'rb');
                $output = fopen($normalizedPath, 'wb');

                if ($input === false || $output === false) {
                    if (is_resource($input)) {
                        fclose($input);
                    }

                    if (is_resource($output)) {
                        fclose($output);
                    }

                    @unlink($normalizedPath);

                    throw new RuntimeException(
                        'Unable to prepare the ZIP repair operation.'
                    );
                }

                try {
                    if (fseek($input, $skipBytes) !== 0) {
                        throw new RuntimeException(
                            'Unable to skip the unexpected ZIP prefix.'
                        );
                    }

                    $copiedBytes = stream_copy_to_stream($input, $output);

                    if (
                        $copiedBytes === false ||
                        $copiedBytes < 1 ||
                        !fflush($output)
                    ) {
                        throw new RuntimeException(
                            'Unable to write the normalized ZIP archive.'
                        );
                    }
                } catch (Throwable $repairException) {
                    @unlink($normalizedPath);
                    throw $repairException;
                } finally {
                    fclose($input);
                    fclose($output);
                }

                $originalPath = $archivePath . '.prefixed-original';

                if (File::exists($originalPath)) {
                    @unlink($normalizedPath);

                    throw new RuntimeException(
                        'A preserved original ZIP already exists; refusing to overwrite it.'
                    );
                }

                if (!rename($archivePath, $originalPath)) {
                    @unlink($normalizedPath);

                    throw new RuntimeException(
                        'Unable to preserve the original ZIP archive.'
                    );
                }

                if (!rename($normalizedPath, $archivePath)) {
                    @rename($originalPath, $archivePath);
                    @unlink($normalizedPath);

                    throw new RuntimeException(
                        'Unable to activate the normalized ZIP archive.'
                    );
                }
            }
            // Re-open and validate the final archive before calling it successful.
            $this->validateZipArchiveContents($archivePath);
            $success = true;

            return $filename;
        } finally {
            if ($opened) {
                @$zip->close();
            }
            @unlink($sqlTemp);
            @unlink($jsonTemp);

            if (!$success && File::exists($archivePath)) {
                File::delete($archivePath);
            }
        }
    }

    public function download(string $backup): BinaryFileResponse
{
    $path = $this->safeBackupPath($backup);
    abort_unless(File::isFile($path), 404, 'Backup file not found.');
    // Prevent accidental output from contaminating the download.
    while (ob_get_level() > 0) {
        if (!@ob_end_clean()) {
            break;
        }
    }

    return response()->download(
        $path,
        basename($path),
        [
            'Content-Type' => 'application/zip',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]
    );
}


    public function destroy(string $backup)
    {
        $path = $this->safeBackupPath($backup);
        abort_unless(File::isFile($path), 404, 'Backup file not found.');

        File::delete($path);

        return redirect()->route(self::INDEX_ROUTE)
            ->with('success', 'Backup deleted successfully.');
    }

    public function validateBackup(string $backup)
    {
        $path = $this->safeBackupPath($backup);
        abort_unless(File::isFile($path), 404, 'Backup file not found.');

        if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'zip') {
            return redirect()->route(self::INDEX_ROUTE)
                ->with('error', 'Only ZIP backups can be validated.');
        }

        try {
            $this->validateZipArchiveContents($path);

            return redirect()->route(self::INDEX_ROUTE)
                ->with('success', 'Backup validation passed. No database changes were made.');
        } catch (Throwable $exception) {
            Log::warning('Backup validation failed', [
                'backup' => basename($path),
                'message' => $exception->getMessage(),
            ]);

            return redirect()->route(self::INDEX_ROUTE)
                ->with('error', config('app.debug')
                    ? 'Backup validation failed: ' . $exception->getMessage()
                    : 'Backup validation failed. Check the Laravel log for details.');
        }
    }

    public function uploadRestore(Request $request)
    {
        $request->validate([
            'backup_file' => ['required', 'file', 'mimes:zip', 'max:512000'],
        ]);

        $temporaryPath = $request->file('backup_file')->getRealPath();
        if (!is_string($temporaryPath) || !is_file($temporaryPath)) {
            return back()->withErrors(['backup_file' => 'The uploaded ZIP file could not be read.']);
        }

        $destination = null;

        try {
            $this->validateZipArchiveContents($temporaryPath);

            $filename = 'uploaded_backup_' . now()->format('Y-m-d_H-i-s') . '_' . bin2hex(random_bytes(4)) . '.zip';
            $destination = $this->ensureBackupDirectory() . DIRECTORY_SEPARATOR . $filename;

            if (!copy($temporaryPath, $destination)) {
                throw new RuntimeException('The validated backup could not be saved.');
            }

            return redirect()->route(self::RESTORE_ROUTE)->with(
                'success',
                "Archive checks passed. Backup saved as {$filename}. No school data has been restored or changed."
            );
        } catch (Throwable $exception) {
            if ($destination !== null && File::exists($destination)) {
                File::delete($destination);
            }

            Log::warning('Backup upload validation failed', ['message' => $exception->getMessage()]);

            return back()->withErrors([
                'backup_file' => config('app.debug')
                    ? 'Backup validation failed: ' . $exception->getMessage()
                    : 'Backup validation failed. Check the archive and configured backup password.',
            ]);
        }
    }

    public function restoreBackup(string $backup)
    {
        abort_unless(config('backup.restore_enabled', false) === true, 403,
            'Backup restoration is currently disabled.');

        $path = $this->safeBackupPath($backup);
        abort_unless(File::isFile($path) && strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'zip',
            404, 'ZIP backup not found.');

        @set_time_limit(0);

        $workDirectory = null;
        $safetyFilename = null;
        $databaseChanged = false;
        $filesChanged = false;

        try {
            $this->validateZipArchiveContents($path);
            $workDirectory = storage_path('app/restore-work-' . bin2hex(random_bytes(8)));
            File::makeDirectory($workDirectory, 0700, true);

            $staged = $this->stageArchive($path, $workDirectory);
            $this->validateSqlHeader($staged['sql']);

            // Take and validate a complete safety snapshot before touching the current state.
            $safetyFilename = $this->createBackupArchive('pre_restore_safety');
            $safetyPath = $this->safeBackupPath($safetyFilename);
            $this->validateZipArchiveContents($safetyPath);

            // Prepare a staged copy of the current public storage tree for rollback.
            $currentPublic = storage_path('app/public');
            $rollbackPublic = $workDirectory . DIRECTORY_SEPARATOR . 'rollback-public';
            if (File::isDirectory($currentPublic)) {
                File::copyDirectory($currentPublic, $rollbackPublic);
            } else {
                File::makeDirectory($rollbackPublic, 0755, true);
            }

            $databaseChanged = true;
            $this->importSqlDump($staged['sql']);

            $filesChanged = true;
            $this->replacePublicFilesFromStage($staged['files'], $currentPublic);

            return redirect()->route(self::RESTORE_ROUTE)->with(
                'success',
                "Backup restored successfully. Safety backup created: {$safetyFilename}"
            );
        } catch (Throwable $exception) {
            Log::error('Backup restore failed', [
                'backup' => basename($path),
                'safety_backup' => $safetyFilename,
                'message' => $exception->getMessage(),
            ]);

            $rollbackMessage = '';

            if ($safetyFilename !== null) {
                try {
                    $safetyPath = $this->safeBackupPath($safetyFilename);
                    $safetyStageDir = storage_path('app/rollback-work-' . bin2hex(random_bytes(6)));
                    File::makeDirectory($safetyStageDir, 0700, true);

                    $safety = $this->stageArchive($safetyPath, $safetyStageDir);

                    if ($databaseChanged) {
                        $this->importSqlDump($safety['sql']);
                    }

                    if ($filesChanged) {
                        $this->replacePublicFilesFromStage($safety['files'], storage_path('app/public'));
                    }

                    File::deleteDirectory($safetyStageDir);
                    $rollbackMessage = ' The safety backup recovery attempt completed.';
                } catch (Throwable $rollbackException) {
                    Log::critical('Backup restore rollback failed', [
                        'safety_backup' => $safetyFilename,
                        'message' => $rollbackException->getMessage(),
                    ]);
                    $rollbackMessage = ' CRITICAL: automatic recovery also failed. Keep the safety backup and contact your administrator immediately.';
                }
            }

            return redirect()->route(self::RESTORE_ROUTE)->with(
                'error',
                (config('app.debug')
                    ? 'Restore failed: ' . $exception->getMessage()
                    : 'Restore failed. Check the Laravel log for details.')
                . $rollbackMessage
            );
        } finally {
            if ($workDirectory && File::isDirectory($workDirectory)) {
                File::deleteDirectory($workDirectory);
            }
        }
    }

    private function safeBackupPath(string $filename): string
    {
        abort_if(
            $filename !== basename($filename) ||
            !preg_match('/\A[a-zA-Z0-9._-]+\.(?:zip|sql|json)\z/i', $filename),
            404
        );

        return $this->backupDirectory() . DIRECTORY_SEPARATOR . $filename;
    }

    private function validateZipArchiveContents(string $path): void
    {
        if (!class_exists(ZipArchive::class)) {
            throw new RuntimeException('PHP ZIP support is unavailable.');
        }

        $password = config('backup.password');
        if (!is_string($password) || $password === '') {
            throw new RuntimeException('The backup password is not configured.');
        }

        $zip = new ZipArchive();
        $opened = $zip->open($path, ZipArchive::RDONLY);
        if ($opened !== true) {
            throw new RuntimeException('The file is not a readable ZIP archive.');
        }

        try {
            if (!$zip->setPassword($password)) {
                throw new RuntimeException('Unable to configure ZIP decryption.');
            }

            if ($zip->numFiles < 3 || $zip->numFiles > 100000) {
                throw new RuntimeException('The archive has an invalid number of entries.');
            }

            $sqlEntry = null;
            $jsonEntry = null;
            $metadataFound = false;
            $totalSize = 0;

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                if (!is_array($stat) || !isset($stat['name'])) {
                    throw new RuntimeException('An archive entry could not be inspected.');
                }

                $name = $stat['name'];
                if (!is_string($name) || $name === '') {
                    throw new RuntimeException('The archive contains an invalid entry name.');
                }

                if (
                    str_starts_with($name, '/') ||
                    str_contains($name, '\\') ||
                    preg_match('/\A[a-zA-Z]:/', $name) ||
                    in_array('..', explode('/', $name), true)
                ) {
                    throw new RuntimeException('The archive contains an unsafe file path.');
                }

                $size = (int) ($stat['size'] ?? 0);
                if ($size < 0) {
                    throw new RuntimeException('The archive contains an invalid file size.');
                }

                $totalSize += $size;
                if ($totalSize > 10 * 1024 * 1024 * 1024) {
                    throw new RuntimeException('The expanded archive exceeds the 10 GiB limit.');
                }

                if (preg_match('/\Adatabase\/database_[^\/]+\.sql\z/i', $name)) {
                    $sqlEntry = $name;
                } elseif (preg_match('/\Adatabase\/database_[^\/]+\.json\z/i', $name)) {
                    $jsonEntry = $name;
                } elseif ($name === 'backup-info.json') {
                    $metadataFound = true;
                }
            }

            if (!$sqlEntry || !$jsonEntry || !$metadataFound) {
                throw new RuntimeException('The archive must contain SQL, JSON, and backup-info.json entries.');
            }

            foreach ([$sqlEntry, $jsonEntry, 'backup-info.json'] as $entry) {
                $stream = $zip->getStream($entry);
                if ($stream === false) {
                    throw new RuntimeException("Archive entry could not be decrypted: {$entry}");
                }

                try {
                    if ($entry === 'backup-info.json') {
                        $metadata = stream_get_contents($stream, 1048577);
                        if (!is_string($metadata) || strlen($metadata) > 1048576) {
                            throw new RuntimeException('Backup metadata is too large or unreadable.');
                        }
                        $decoded = json_decode($metadata, true);
                        if (!is_array($decoded) || !isset($decoded['created_at'], $decoded['format'])) {
                            throw new RuntimeException('Backup metadata is invalid.');
                        }
                    } elseif (str_ends_with($entry, '.sql')) {
                        $header = fread($stream, 4096);
                        if (!is_string($header) || !str_contains($header, 'School Management database backup')) {
                            throw new RuntimeException('The SQL backup header is missing or invalid.');
                        }
                    } else {
                        // Reading the first byte forces encrypted-entry authentication.
                        fread($stream, 1);
                    }
                } finally {
                    fclose($stream);
                }
            }
        } finally {
            $zip->close();
        }
    }

    /**
     * Decrypts the archive to a private work directory. Only storage/public/*
     * files are staged for restoration; no archive path is written directly.
     */
    private function stageArchive(string $archivePath, string $workDirectory): array
    {
        $password = config('backup.password');
        $zip = new ZipArchive();

        if ($zip->open($archivePath, ZipArchive::RDONLY) !== true) {
            throw new RuntimeException('Unable to open ZIP archive for staging.');
        }

        $sqlPath = null;
        $filesRoot = $workDirectory . DIRECTORY_SEPARATOR . 'new-public';
        File::makeDirectory($filesRoot, 0755, true);

        try {
            if (!$zip->setPassword($password)) {
                throw new RuntimeException('Unable to configure ZIP decryption.');
            }

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (!is_string($name)) {
                    throw new RuntimeException('Invalid archive entry.');
                }

                if (preg_match('/\Adatabase\/database_[^\/]+\.sql\z/i', $name)) {
                    $sqlPath = $workDirectory . DIRECTORY_SEPARATOR . 'restore.sql';
                    $this->copyZipEntryToFile($zip, $name, $sqlPath);
                    continue;
                }

                if (!str_starts_with($name, 'storage/public/')) {
                    continue;
                }

                $relative = substr($name, strlen('storage/public/'));
                if ($relative === '' || str_ends_with($name, '/')) {
                    continue;
                }

                $segments = explode('/', $relative);
                if (in_array('..', $segments, true) || str_contains($relative, '\\') || str_starts_with($relative, '/')) {
                    throw new RuntimeException('Unsafe storage path in archive.');
                }

                $target = $filesRoot . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $segments);
                $parent = dirname($target);
                if (!File::isDirectory($parent)) {
                    File::makeDirectory($parent, 0755, true);
                }

                $this->copyZipEntryToFile($zip, $name, $target);
            }
        } finally {
            $zip->close();
        }

        if (!$sqlPath || !File::isFile($sqlPath)) {
            throw new RuntimeException('The archive does not contain a SQL dump.');
        }

        return ['sql' => $sqlPath, 'files' => $filesRoot];
    }

    private function copyZipEntryToFile(ZipArchive $zip, string $entry, string $destination): void
    {
        $input = $zip->getStream($entry);
        if ($input === false) {
            throw new RuntimeException("Unable to decrypt archive entry: {$entry}");
        }

        $output = fopen($destination, 'wb');
        if ($output === false) {
            fclose($input);
            throw new RuntimeException('Unable to write staged restore file.');
        }

        try {
            if (stream_copy_to_stream($input, $output) === false) {
                throw new RuntimeException('Unable to copy archive entry to staging.');
            }
        } finally {
            fclose($input);
            fclose($output);
        }
    }

    private function validateSqlHeader(string $sqlPath): void
    {
        $handle = fopen($sqlPath, 'rb');
        if ($handle === false) {
            throw new RuntimeException('Unable to read staged SQL dump.');
        }

        try {
            $header = fread($handle, 4096);
        } finally {
            fclose($handle);
        }

        if (!is_string($header) || !str_contains($header, 'School Management database backup')) {
            throw new RuntimeException('Staged SQL dump failed header validation.');
        }
    }

    /**
     * Replaces storage/app/public with the staged public files.
     * The caller first keeps a rollback copy of the existing directory.
     */
    private function replacePublicFilesFromStage(string $stagedFiles, string $destination): void
    {
        $parent = dirname($destination);
        $replacement = $parent . DIRECTORY_SEPARATOR . 'public-restore-' . bin2hex(random_bytes(5));

        if (!File::copyDirectory($stagedFiles, $replacement)) {
            throw new RuntimeException('Unable to prepare restored public files.');
        }

        $oldDirectory = $parent . DIRECTORY_SEPARATOR . 'public-old-' . bin2hex(random_bytes(5));

        try {
            if (File::isDirectory($destination) && !rename($destination, $oldDirectory)) {
                throw new RuntimeException('Unable to move current public files aside.');
            }

            if (!rename($replacement, $destination)) {
                if (File::isDirectory($oldDirectory)) {
                    @rename($oldDirectory, $destination);
                }
                throw new RuntimeException('Unable to activate restored public files.');
            }

            if (File::isDirectory($oldDirectory)) {
                File::deleteDirectory($oldDirectory);
            }
        } finally {
            if (File::isDirectory($replacement)) {
                File::deleteDirectory($replacement);
            }
        }
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

    private function makeTempFile(): string
    {
        $temp = tempnam(sys_get_temp_dir(), 'school_backup_');
        if ($temp === false) {
            throw new RuntimeException('Unable to create temporary export file.');
        }
        return $temp;
    }

    private function exportDatabase(string $connection, string $sqlPath, string $jsonPath): int
    {
        $pdo = DB::connection($connection)->getPdo();
        $tables = $pdo->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'")
            ->fetchAll(PDO::FETCH_COLUMN, 0);

        $sql = fopen($sqlPath, 'wb');
        $json = fopen($jsonPath, 'wb');

        if (!$sql || !$json) {
            if (is_resource($sql)) fclose($sql);
            if (is_resource($json)) fclose($json);
            throw new RuntimeException('Unable to open temporary export files.');
        }

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

                fwrite($json, ($firstTable ? '' : ",\n")
                    . '  ' . json_encode((string) $table, $jsonFlags) . ': [');
                $firstTable = false;

                $previous = $pdo->getAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY);
                $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);

                try {
                    $rows = $pdo->query("SELECT * FROM {$quotedTable}");
                    $firstRow = true;

                    while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
                        $columns = array_map(
                            fn ($column) => '`' . str_replace('`', '``', $column) . '`',
                            array_keys($row)
                        );
                        $values = array_map(
                            fn ($value) => $value === null ? 'NULL' : $pdo->quote((string) $value),
                            array_values($row)
                        );

                        fwrite($sql, "INSERT INTO {$quotedTable} (" . implode(', ', $columns)
                            . ') VALUES (' . implode(', ', $values) . ");\n");

                        fwrite($json, ($firstRow ? "\n" : ",\n") . '    '
                            . json_encode($row, $jsonFlags));
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

    /**
     * Executes statements from this application's own generated dump.
     * Semicolons inside quoted strings are not treated as statement endings.
     */
    private function importSqlDump(string $sqlPath): void
    {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}");

        if (!is_array($database) || !in_array($database['driver'] ?? null, ['mysql', 'mariadb'], true)) {
            throw new RuntimeException('Restore supports MySQL/MariaDB only.');
        }

        if (!is_file($sqlPath) || !is_readable($sqlPath)) {
            throw new RuntimeException('SQL dump is missing or unreadable.');
        }

        $pdo = DB::connection($connection)->getPdo();
        $handle = fopen($sqlPath, 'rb');

        if ($handle === false) {
            throw new RuntimeException('Could not open SQL dump.');
        }

        $statement = '';
        $quote = null;
        $escaped = false;
        $lineComment = false;
        $blockComment = false;

        try {
            while (($char = fgetc($handle)) !== false) {
                $position = ftell($handle);
                $peek = $position !== false ? fgetc($handle) : false;
                if ($position !== false) {
                    fseek($handle, $position);
                }

                if ($lineComment) {
                    if ($char === "\n") $lineComment = false;
                    continue;
                }

                if ($blockComment) {
                    if ($char === '*' && $peek === '/') {
                        fgetc($handle);
                        $blockComment = false;
                    }
                    continue;
                }

                if ($quote === null) {
                    if ($char === '#' || ($char === '-' && $peek === '-')) {
                        if ($char === '-') {
                            $position = ftell($handle);
                            fgetc($handle);
                            $after = fgetc($handle);
                            if ($position !== false) fseek($handle, $position);
                            if ($after !== false && !ctype_space($after)) {
                                $statement .= $char;
                                continue;
                            }
                            fgetc($handle);
                        }
                        $lineComment = true;
                        continue;
                    }

                    if ($char === '/' && $peek === '*') {
                        fgetc($handle);
                        $blockComment = true;
                        continue;
                    }

                    if (in_array($char, ["'", '"', '`'], true)) {
                        $quote = $char;
                        $statement .= $char;
                        continue;
                    }

                    if ($char === ';') {
                        $sql = trim($statement);
                        $statement = '';
                        if ($sql !== '') $pdo->exec($sql);
                        continue;
                    }
                } else {
                    if ($escaped) {
                        $escaped = false;
                    } elseif ($char === '\\' && $quote !== '`') {
                        $escaped = true;
                    } elseif ($char === $quote) {
                        if ($peek === $quote) {
                            $statement .= $char . fgetc($handle);
                            continue;
                        }
                        $quote = null;
                    }
                }

                $statement .= $char;
            }

            if ($quote !== null || $blockComment) {
                throw new RuntimeException('The SQL dump appears incomplete or malformed.');
            }

            $remaining = trim($statement);
            if ($remaining !== '') $pdo->exec($remaining);
        } finally {
            fclose($handle);
        }
    }
}
