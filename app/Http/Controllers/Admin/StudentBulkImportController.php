<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StudentBulkTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\StudentBulkImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;
use Throwable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class StudentBulkImportController extends Controller
{
    /**
     * Download the Excel template.
     */
    public function downloadTemplate()
    {
        return Excel::download(
            new StudentBulkTemplateExport(),
            'student_bulk_import_template.xlsx'
        );
    }

    /**
     * Import new students and update existing students.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:10240',
            ],
        ]);

        try {
            Excel::import(
                new StudentBulkImport(),
                $request->file('file')
            );

            return redirect()
                ->back()
                ->with('success', 'Student Excel import completed successfully.');
        } catch (ExcelValidationException $e) {
            $errors = [];

            foreach ($e->failures() as $failure) {
                $row = $failure->row();
                $attribute = $failure->attribute();

                foreach ($failure->errors() as $message) {
                    $errors[] = "Row {$row}, column {$attribute}: {$message}";
                }
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('import_errors', $errors);
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('import_errors', array_values($e->errors()));
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'The import failed. Check the spreadsheet and application logs, then try again.');
        }
    }
}
