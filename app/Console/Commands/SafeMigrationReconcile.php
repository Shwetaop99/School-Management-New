<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SafeMigrationReconcile extends Command
{
    protected $signature = 'migrations:safe-audit';

    protected $description = 'Safely audit pending migrations without changing the database';

    public function handle(): int
    {
        $this->info('==============================================');
        $this->info('       SAFE MIGRATION RECONCILIATION AUDIT');
        $this->info('==============================================');

        $this->newLine();

        $ran = DB::table('migrations')
            ->pluck('migration')
            ->toArray();

        $files = glob(database_path('migrations/*.php'));

        $pending = [];

        foreach ($files as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);

            if (!in_array($name, $ran, true)) {
                $pending[] = $name;
            }
        }

        $this->info('Pending migrations found: ' . count($pending));
        $this->newLine();

        foreach ($pending as $migration) {
            [$status, $reason] = $this->inspectMigration($migration);

            $this->line("Migration: {$migration}");
            $this->line("Status: {$status}");
            $this->line("Reason: {$reason}");
            $this->line(str_repeat('-', 75));
        }

        $this->newLine();

        $this->warn('AUDIT ONLY: No migrations were executed.');
        $this->warn('AUDIT ONLY: Migration history was not modified.');
        $this->warn('AUDIT ONLY: No existing data was changed.');

        return self::SUCCESS;
    }

    private function inspectMigration(string $migration): array
    {
        /*
        |--------------------------------------------------------------------------
        | TEACHER ATTENDANCE
        |--------------------------------------------------------------------------
        */

        if ($migration === '2026_10_03_042349_create_teacher_attendances_table') {
            $columns = [
                'id',
                'teacher_id',
                'attendance_date',
                'status',
                'overtime_hours',
                'remarks',
                'created_at',
                'updated_at',
            ];

            if ($this->hasColumns('teacher_attendances', $columns)) {
                return [
                    'REVIEW_REQUIRED',
                    'Table already exists. Existing status enum differs from the pending migration. Preserve existing statuses and inspect before reconciliation.',
                ];
            }

            return ['RUN_REQUIRED', 'Teacher attendance table or required columns are missing.'];
        }

        /*
        |--------------------------------------------------------------------------
        | LEAVE APPLICATIONS
        |--------------------------------------------------------------------------
        */

        if (in_array($migration, [
            '2026_10_03_055041_create_leave_applications_table',
            '2026_10_03_060303_add_leave_application_fields_to_leave_applications_table',
            '2026_10_03_060727_fix_leave_applications_columns',
            '2026_10_03_061200_actually_add_leave_application_columns',
        ], true)) {
            $columns = [
                'id',
                'teacher_id',
                'leave_type',
                'from_date',
                'to_date',
                'total_days',
                'reason',
                'status',
                'admin_remarks',
                'approved_date',
                'created_at',
                'updated_at',
            ];

            if ($this->hasColumns('leave_applications', $columns)) {
                return [
                    'ALREADY_PRESENT',
                    'Existing leave application table contains all required columns. No duplicate table or columns are needed.',
                ];
            }

            return ['REVIEW_REQUIRED', 'Some leave application columns are missing.'];
        }

        /*
        |--------------------------------------------------------------------------
        | SCHOLARSHIP APPLICATION PLACEHOLDER
        |--------------------------------------------------------------------------
        */

        if ($migration === '2026_10_03_095924_create_scholarship_exam_applications_table') {
            if (!Schema::hasTable('scholarship_exam_applications')) {
                return [
                    'SUPERSEDED',
                    'October 5 migration creates the complete scholarship applications table. The October 3 placeholder must not create an empty table first.',
                ];
            }

            return [
                'REVIEW_REQUIRED',
                'Scholarship applications table already exists. Inspect its structure before deciding.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | RESULTS TABLE
        |--------------------------------------------------------------------------
        */

        if ($migration === '2026_10_03_110127_create_results_table') {
            if (Schema::hasTable('results')) {
                return [
                    'SUPERSEDED',
                    'An existing results table uses the overall-results structure. This pending migration attempts to create an incompatible subject-results structure.',
                ];
            }

            return ['RUN_REQUIRED', 'Results table does not exist.'];
        }

        /*
        |--------------------------------------------------------------------------
        | NO-OP MIGRATION
        |--------------------------------------------------------------------------
        */

        if ($migration === '2026_10_03_121915_rename_scholarship_tables_to_merit_concession') {
            return [
                'NO_SCHEMA_CHANGE',
                'The migration has no database operations in its up() method.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | FEE MANAGEMENT
        |--------------------------------------------------------------------------
        */

        $feeTables = [
            '2026_10_03_122509_create_fee_types_table' => 'fee_types',
            '2026_10_03_123912_add_category_and_frequency_to_fee_types_table' => 'fee_types',
            '2026_10_03_125247_add_amount_to_fee_types_table' => 'fee_types',
            '2026_10_03_142658_create_fee_structures_table' => 'fee_structures',
            '2026_10_03_142731_create_fee_structure_items_table' => 'fee_structure_items',
            '2026_10_03_162437_create_student_fees_table' => 'student_fees',
            '2026_10_03_162819_create_fee_payments_table' => 'fee_payments',
        ];

        if (isset($feeTables[$migration])) {
            $table = $feeTables[$migration];

            if (!Schema::hasTable($table)) {
                return ['RUN_REQUIRED', "Fee management table {$table} is missing."];
            }

            return [
                'REVIEW_REQUIRED',
                "Table {$table} already exists. Its columns and constraints need verification.",
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | SCHOLARSHIP EXAMS
        |--------------------------------------------------------------------------
        */

        if (in_array($migration, [
            '2026_10_05_042232_create_scholarship_exams_table',
            '2026_10_05_042635_create_scholarship_exam_classes_table',
            '2026_10_05_043242_create_scholarship_exam_passed_students_table',
            '2026_10_05_055954_add_student_records_to_scholarship_exam_applications_table',
            '2026_10_05_092907_alter_status_on_scholarship_exam_applications_table',
            '2026_10_05_094545_add_result_exam_id_to_scholarship_exams_table',
        ], true)) {
            return [
                'RUN_OR_VERIFY',
                'Scholarship module migration. Its parent tables and dependent structures must be checked before execution.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | EXAM STATUS
        |--------------------------------------------------------------------------
        */

        if ($migration === '2026_10_13_171726_update_status_column_on_exams_table') {
            $type = $this->columnType('exams', 'status');

            if ($type && str_starts_with(strtolower($type), 'varchar')) {
                return [
                    'ALREADY_PRESENT',
                    'Existing exams.status column is already VARCHAR. Verify its default value before reconciliation.',
                ];
            }

            return ['RUN_REQUIRED', 'Exam status column needs inspection or modification.'];
        }

        /*
        |--------------------------------------------------------------------------
        | GOVERNMENT SCHEMES / DISTRIBUTION DATE
        |--------------------------------------------------------------------------
        */

        if (in_array($migration, [
            '2026_10_22_165219_create_government_schemes_table',
            '2026_10_23_121755_add_distribution_date_to_student_supply_kits_table',
        ], true)) {
            if (
                Schema::hasTable('student_supply_kits') &&
                Schema::hasColumn('student_supply_kits', 'distribution_date')
            ) {
                return [
                    'ALREADY_PRESENT',
                    'student_supply_kits.distribution_date already exists. The migration must not add it again.',
                ];
            }

            return ['RUN_REQUIRED', 'Distribution date column is missing.'];
        }

        /*
        |--------------------------------------------------------------------------
        | OVERALL RESULTS COLUMNS
        |--------------------------------------------------------------------------
        */

        if ($migration === '2026_10_22_215655_add_overall_result_columns_to_results_table') {
            $columns = [
                'academic_year',
                'class_name',
                'section',
                'total_marks',
                'obtained_marks',
                'percentage',
                'result_status',
                'generated_at',
            ];

            if ($this->hasColumns('results', $columns)) {
                return [
                    'ALREADY_PRESENT',
                    'All overall-result columns already exist.',
                ];
            }

            return ['RUN_REQUIRED', 'Some overall-result columns are missing.'];
        }

        /*
        |--------------------------------------------------------------------------
        | SIGNED QUANTITY
        |--------------------------------------------------------------------------
        */

        if ($migration === '2026_10_30_110121_change_quantity_to_signed_integer_in_supply_stock_transactions_table') {
            $type = $this->columnType('supply_stock_transactions', 'quantity');

            if ($type && !str_contains(strtolower($type), 'unsigned')) {
                return [
                    'ALREADY_PRESENT',
                    'Quantity is already a signed integer.',
                ];
            }

            return [
                'RUN_REQUIRED',
                'Quantity is currently unsigned or the column is missing.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | INTERNAL AND THEORY MARKS
        |--------------------------------------------------------------------------
        */

        if ($migration === '2026_10_31_102232_add_internal_theory_marks_to_exam_subjects_table') {
            if (
                Schema::hasColumn('exam_subjects', 'internal_max_marks') &&
                Schema::hasColumn('exam_subjects', 'theory_max_marks')
            ) {
                return [
                    'ALREADY_PRESENT',
                    'Both internal_max_marks and theory_max_marks already exist.',
                ];
            }

            return [
                'RUN_REQUIRED',
                'One or both exam subject marks columns are missing.',
            ];
        }

        return [
            'REVIEW_REQUIRED',
            'No automatic rule is defined for this migration. Manual inspection is required.',
        ];
    }

    private function hasColumns(string $table, array $columns): bool
    {
        if (!Schema::hasTable($table)) {
            return false;
        }

        $existing = Schema::getColumnListing($table);

        foreach ($columns as $column) {
            if (!in_array($column, $existing, true)) {
                return false;
            }
        }

        return true;
    }

    private function columnType(string $table, string $column): ?string
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
            return null;
        }

        $result = DB::selectOne(
            'SELECT COLUMN_TYPE
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = ?
             AND COLUMN_NAME = ?',
            [$table, $column]
        );

        return $result->COLUMN_TYPE ?? null;
    }
}