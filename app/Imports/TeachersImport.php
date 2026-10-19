<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;

class TeachersImport implements ToCollection, WithHeadingRow
{
    public Collection $importedRows;

    public function __construct()
    {
        $this->importedRows = collect();
    }

    /**
     * Normalize Excel column headings.
     */
    public function headingRow(): int
    {
        return 1;
    }

    public function collection(Collection $rows): void
    {
        $this->importedRows = $rows->map(function ($item) {
            $row = collect($item)->toArray();

            // Normalize column headings and trim cell values.
            $normalized = [];

            foreach ($row as $key => $value) {
                $key = strtolower(trim((string) $key));
                $key = preg_replace('/[^a-z0-9]+/', '_', $key);
                $key = trim($key, '_');

                if (is_string($value)) {
                    $value = trim($value);
                }

                $normalized[$key] = $value;
            }

            // Support common alternative column headings.
            $aliases = [
                'first_name' => [
                    'firstname',
                    'first',
                    'teacher_first_name',
                ],
                'last_name' => [
                    'lastname',
                    'last',
                    'teacher_last_name',
                ],
                'email' => [
                    'email_address',
                    'teacher_email',
                ],
                'phone' => [
                    'mobile',
                    'mobile_number',
                    'phone_number',
                    'contact_number',
                ],
                'gender' => [
                    'sex',
                    'teacher_gender',
                ],
                'date_of_birth' => [
                    'dob',
                    'birth_date',
                ],
                'joining_date' => [
                    'date_of_joining',
                    'join_date',
                ],
                'qualification' => [
                    'education',
                    'highest_qualification',
                ],
                'subject' => [
                    'teaching_subject',
                    'subject_name',
                ],
                'status' => [
                    'teacher_status',
                ],
            ];

            foreach ($aliases as $target => $alternatives) {
                if (
                    !isset($normalized[$target]) ||
                    $normalized[$target] === ''
                ) {
                    foreach ($alternatives as $alias) {
                        if (
                            isset($normalized[$alias]) &&
                            $normalized[$alias] !== ''
                        ) {
                            $normalized[$target] = $normalized[$alias];
                            break;
                        }
                    }
                }
            }

            // Normalize gender values for the controller validation.
            if (isset($normalized['gender'])) {
                $gender = strtolower(trim((string) $normalized['gender']));

                $genderMap = [
                    'male' => 'Male',
                    'm' => 'Male',
                    'man' => 'Male',
                    'female' => 'Female',
                    'f' => 'Female',
                    'woman' => 'Female',
                    'other' => 'Other',
                    'others' => 'Other',
                ];

                if ($gender === '') {
                    $normalized['gender'] = null;
                } elseif (isset($genderMap[$gender])) {
                    $normalized['gender'] = $genderMap[$gender];
                }
            }

            // Normalize status.
            if (isset($normalized['status'])) {
                $status = strtolower(trim((string) $normalized['status']));

                $statusMap = [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ];

                if ($status === '') {
                    $normalized['status'] = 'Active';
                } elseif (isset($statusMap[$status])) {
                    $normalized['status'] = $statusMap[$status];
                }
            }

            // Blank optional fields should be null.
            foreach ([
                'phone',
                'date_of_birth',
                'gender',
                'qualification',
                'joining_date',
                'address',
                'subject',
            ] as $field) {
                if (
                    isset($normalized[$field]) &&
                    trim((string) $normalized[$field]) === ''
                ) {
                    $normalized[$field] = null;
                }
            }

            return $normalized;
        });
    }
}
