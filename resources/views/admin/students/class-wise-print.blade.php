<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Student Class Wise List
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 25px;
            background: #e9ecef;
            font-family: Arial, Helvetica, sans-serif;
            color: #212529;
        }

        .print-page {
            width: 100%;
            max-width: 1100px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
        }

        /* Header */

        .school-header {
            text-align: center;
            border-bottom: 2px solid #212529;
            padding-bottom: 16px;
            margin-bottom: 18px;
        }

        .school-name {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .report-title {
            margin: 6px 0 0;
            font-size: 18px;
            font-weight: 700;
        }

        .report-subtitle {
            margin-top: 5px;
            font-size: 12px;
            color: #666;
        }

        /* Filter information */

        .report-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 18px;
        }

        .selection-info {
            font-size: 12px;
            line-height: 1.8;
        }

        .selection-info strong {
            color: #111;
        }

        .print-date {
            text-align: right;
            font-size: 11px;
            color: #666;
            white-space: nowrap;
        }

        /* Summary */

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .summary-box {
            border: 1px solid #dcdfe3;
            padding: 10px 12px;
            text-align: center;
        }

        .summary-label {
            display: block;
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 4px;
        }

        .summary-value {
            display: block;
            font-size: 20px;
            font-weight: 800;
        }

        /* Table */

        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .student-table th {
            background: #f1f3f5;
            border: 1px solid #adb5bd;
            padding: 9px 7px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            text-align: left;
            white-space: nowrap;
        }

        .student-table td {
            border: 1px solid #ced4da;
            padding: 8px 7px;
            font-size: 10px;
            vertical-align: middle;
        }

        .student-table th:first-child,
        .student-table td:first-child {
            text-align: center;
            width: 45px;
        }

        .student-table th:nth-child(2),
        .student-table td:nth-child(2) {
            text-align: center;
            width: 65px;
        }

        .student-table th:nth-child(5),
        .student-table td:nth-child(5) {
            text-align: center;
            width: 65px;
        }

        .student-table th:nth-child(6),
        .student-table td:nth-child(6) {
            text-align: center;
            width: 70px;
        }

        .student-name {
            font-weight: 700;
        }

        .marathi-name {
            margin-top: 2px;
            font-size: 9px;
            color: #666;
        }

        /* Footer */

        .report-footer {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px solid #ced4da;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #666;
        }

        /* Buttons */

        .print-actions {
            max-width: 1100px;
            margin: 0 auto 15px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .print-actions button {
            border: 0;
            padding: 9px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-print {
            background: #212529;
            color: #fff;
        }

        .btn-close {
            background: #fff;
            color: #212529;
            border: 1px solid #ced4da !important;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            border: 1px solid #dee2e6;
            color: #666;
            font-size: 13px;
        }

        /* Print */

        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        @media print {

            body {
                padding: 0;
                background: #fff;
            }

            .print-page {
                max-width: none;
                padding: 0;
            }

            .print-actions {
                display: none !important;
            }

            .school-header {
                margin-top: 0;
            }

            .student-table {
                page-break-inside: auto;
            }

            .student-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .student-table thead {
                display: table-header-group;
            }

            .summary-grid {
                page-break-inside: avoid;
            }

            .report-footer {
                page-break-inside: avoid;
            }
        }

        @media(max-width: 768px) {

            body {
                padding: 10px;
            }

            .print-page {
                padding: 15px;
            }

            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .report-info {
                flex-direction: column;
            }

            .print-date {
                text-align: left;
            }

            .student-table {
                min-width: 850px;
            }

            .table-wrapper {
                overflow-x: auto;
            }
        }

    </style>

</head>

<body>

    <div class="print-actions">

        <button type="button"
                class="btn-print"
                onclick="window.print()">
            Print List
        </button>

        <button type="button"
                class="btn-close"
                onclick="window.close()">
            Close
        </button>

    </div>

    <div class="print-page">

        {{-- School Header --}}

        <div class="school-header">

            <h1 class="school-name">
                {{ config('app.name', 'School Management System') }}
            </h1>

            <div class="report-title">
                Student Class Wise List
            </div>

            <div class="report-subtitle">
                Student Management Report
            </div>

        </div>


        {{-- Selected Filters --}}

        <div class="report-info">

            <div class="selection-info">

                <div>
                    <strong>Academic Year:</strong>

                    {{ $academicYear ?: 'All Academic Years' }}
                </div>

                <div>

                    <strong>Class:</strong>

                    @if($selectedClass)
                        Class {{ $selectedClass }}
                    @else
                        All Classes
                    @endif

                    @if($selectedSection)
                        &nbsp; | &nbsp;

                        <strong>Section:</strong>
                        {{ $selectedSection }}
                    @endif

                </div>

                @if($selectedGender)

                    <div>

                        <strong>Gender:</strong>
                        {{ $selectedGender }}

                    </div>

                @endif

                @if($selectedStatus)

                    <div>

                        <strong>Status:</strong>
                        {{ ucfirst($selectedStatus) }}

                    </div>

                @endif

            </div>

            <div class="print-date">

                Printed On:
                {{ now()->format('d-m-Y h:i A') }}

            </div>

        </div>


        {{-- Student Table --}}

        @if($students->count())

            <div class="table-wrapper">

                <table class="student-table">

                    <thead>

                        <tr>

                            <th>
                                No.
                            </th>

                            <th>
                                Roll No.
                            </th>

                            <th>
                                Student Name
                            </th>

                            <th>
                                Student ID
                            </th>

                            <th>
                                Class
                            </th>

                            <th>
                                Section
                            </th>

                            <th>
                                Gender
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($students as $index => $student)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $student->roll_number ?? '-' }}
                                </td>

                                <td>

                                    <div class="student-name">

                                        {{ trim(
                                            ($student->first_name ?? '') . ' ' .
                                            ($student->middle_name ?? '') . ' ' .
                                            ($student->last_name ?? '')
                                        ) }}

                                    </div>

                                    @if(!empty($student->marathi_name))

                                        <div class="marathi-name">
                                            {{ $student->marathi_name }}
                                        </div>

                                    @endif

                                </td>

                                <td>
                                    {{ $student->student_id ?? '-' }}
                                </td>

                                <td>
                                    {{ $student->class ?? '-' }}
                                </td>

                                <td>
                                    {{ $student->section ?? '-' }}
                                </td>

                                <td>
                                    {{ $student->gender ?? '-' }}
                                </td>

                                <td>
                                    {{ ucfirst($student->status ?? 'Inactive') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-state">

                No students found for the selected filters.

            </div>

        @endif


        {{-- Footer --}}

        <div class="report-footer">

            <div>
                Total Records:
                <strong>{{ $totalStudents }}</strong>
            </div>

            <div>
                School Management System
            </div>

        </div>

    </div>

</body>

</html>