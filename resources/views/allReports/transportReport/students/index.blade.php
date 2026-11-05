@extends('layouts.app')

@section('title', 'Student Transport Report | Admin')
@section('page-title', 'Student Transport Report')

@section('content')
<div class="student-transport-report">
    <div class="container-fluid py-4">

        <div class="topbar">
            <div>
                <div class="eyebrow">TRANSPORT MANAGEMENT / REPORTS</div>
                <h2>Student Transport Report</h2>
                <p>Class-wise overview of students registered for school transport.</p>
            </div>

            <div class="top-actions">
                <a href="{{ route('admin.reports.transport') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Transport Reports
                </a>
                <button type="button" class="btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <span>Total Transport Students</span>
                    <strong>{{ number_format($totalTransportStudents) }}</strong>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon green">
                    <i class="fas fa-bus"></i>
                </div>
                <div>
                    <span>Total Transport Records</span>
                    <strong>{{ number_format($totalTransportRecords) }}</strong>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon blue">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <span>Active Transport Students</span>
                    <strong>{{ number_format($activeTransportStudents) }}</strong>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon amber">
                    <i class="fas fa-school"></i>
                </div>
                <div>
                    <span>Classes with Applicants</span>
                    <strong>{{ number_format($classCards->count()) }}</strong>
                </div>
            </div>
        </div>

        <div class="report-note">
            <i class="fas fa-info-circle"></i>
            <span>
                Select a class to view its transport applicants. You can then filter
                students by section and academic year. Only students with transport
                records are included in this report.
            </span>
        </div>

        <div class="section-heading">
            <div>
                <h3>Class-wise Transport Register</h3>
                <p>Select a class to explore its registered transport students.</p>
            </div>
            <span class="record-count">{{ $classCards->count() }} Classes</span>
        </div>

        @if($classCards->isNotEmpty())
            <div class="class-grid">
                @foreach($classCards as $class)
                    <a
                        href="{{ route('admin.reports.transport.students.class', [
                            'class' => $class->class_value
                        ]) }}"
                        class="class-card"
                    >
                        <div class="class-card-top">
                            <div class="class-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <span class="class-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>

                        <div class="class-name">{{ $class->class_name }}</div>

                        <div class="class-description">
                            Student transport register
                        </div>

                        <div class="class-stats">
                            <div>
                                <strong>{{ number_format($class->student_count) }}</strong>
                                <span>Students</span>
                            </div>
                            <div>
                                <strong>{{ number_format($class->section_count) }}</strong>
                                <span>Sections</span>
                            </div>
                        </div>

                        <div class="academic-years">
                            <i class="fas fa-calendar-alt"></i>
                            @foreach($class->academic_years as $year)
                                <span>{{ $year }}</span>
                            @endforeach
                        </div>

                        <div class="class-card-footer">
                            View transport students
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-bus-alt"></i>
                <strong>No Transport Students Found</strong>
                <span>No students have registered for transport yet.</span>
            </div>
        @endif

        <div class="footer-note">
            <i class="fas fa-shield-alt"></i>
            Student transport information is displayed from the school's transport records.
        </div>

    </div>
</div>

<style>
.student-transport-report {
    --page: #f7f6f0;
    --paper: #fffefa;
    --ink: #263b35;
    --dark: #1d302b;
    --text: #39483e;
    --muted: #7b857b;
    --line: #e5e1d5;
    --sage: #527f70;
    --blue: #426b8a;
    --amber: #a87543;
    --green: #43835d;

    background: var(--page);
    color: var(--text);
    min-height: 100vh;
    font-family: 'Public Sans', 'Inter', sans-serif;
}

.student-transport-report .container-fluid {
    max-width: 1650px;
}

.student-transport-report .topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
}

.student-transport-report .eyebrow {
    color: var(--amber);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
    margin-bottom: 7px;
}

.student-transport-report h2 {
    color: var(--ink);
    font-family: Georgia, serif;
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 6px;
}

.student-transport-report .topbar p {
    color: var(--muted);
    font-size: 13px;
    margin: 0;
}

.student-transport-report .top-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
}

.student-transport-report .btn-primary,
.student-transport-report .btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 15px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: .2s ease;
}

.student-transport-report .btn-primary {
    background: #344f43;
    border: 1px solid #344f43;
    color: white;
}

.student-transport-report .btn-primary:hover {
    background: #20352c;
    color: white;
}

.student-transport-report .btn-secondary {
    background: var(--paper);
    border: 1px solid #d4cfc2;
    color: var(--ink);
}

.student-transport-report .btn-secondary:hover {
    background: #f1ead8;
    color: var(--ink);
}

.student-transport-report .summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 19px;
}

.student-transport-report .summary-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 19px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(38, 59, 53, .035);
}

.student-transport-report .summary-icon {
    width: 45px;
    height: 45px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #f3ead8;
    color: var(--amber);
    font-size: 17px;
}

.student-transport-report .summary-icon.green {
    background: #e8f2e9;
    color: #43835d;
}

.student-transport-report .summary-icon.blue {
    background: #eaf0f4;
    color: #426b8a;
}

.student-transport-report .summary-icon.amber {
    background: #f5eee2;
    color: #a87543;
}

.student-transport-report .summary-card span {
    display: block;
    color: var(--muted);
    font-size: 11px;
    margin-bottom: 5px;
}

.student-transport-report .summary-card strong {
    display: block;
    color: var(--ink);
    font-size: 23px;
    font-weight: 800;
}

.student-transport-report .report-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 14px 16px;
    margin-bottom: 25px;
    background: #f1efe6;
    border: 1px solid #e5e1d5;
    border-radius: 8px;
    color: #59685e;
    font-size: 11px;
    line-height: 1.7;
}

.student-transport-report .report-note i {
    color: var(--sage);
    margin-top: 2px;
}

.student-transport-report .section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.student-transport-report .section-heading h3 {
    color: var(--ink);
    font-family: Georgia, serif;
    font-size: 19px;
    margin: 0 0 5px;
}

.student-transport-report .section-heading p {
    color: var(--muted);
    font-size: 11px;
    margin: 0;
}

.student-transport-report .record-count {
    padding: 6px 11px;
    border-radius: 20px;
    background: #edf2ed;
    color: #527f70;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.student-transport-report .class-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 17px;
}

.student-transport-report .class-card {
    display: block;
    padding: 21px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 11px;
    text-decoration: none;
    color: var(--text);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .035);
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}

.student-transport-report .class-card:hover {
    transform: translateY(-3px);
    border-color: #b9c9bd;
    box-shadow: 0 9px 22px rgba(38, 59, 53, .09);
    color: var(--text);
}

.student-transport-report .class-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 17px;
}

.student-transport-report .class-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #f1ead8;
    color: #527f70;
    font-size: 19px;
}

.student-transport-report .class-arrow {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f5f3e9;
    color: #7b857b;
    font-size: 11px;
    transition: .2s ease;
}

.student-transport-report .class-card:hover .class-arrow {
    background: #344f43;
    color: white;
}

.student-transport-report .class-name {
    color: var(--ink);
    font-family: Georgia, serif;
    font-size: 23px;
    font-weight: 700;
}

.student-transport-report .class-description {
    color: var(--muted);
    font-size: 11px;
    margin-top: 4px;
}

.student-transport-report .class-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    padding: 15px 0;
    margin-top: 14px;
    border-top: 1px solid #eeeae1;
    border-bottom: 1px solid #eeeae1;
}

.student-transport-report .class-stats strong {
    display: block;
    color: var(--ink);
    font-size: 19px;
    font-weight: 800;
}

.student-transport-report .class-stats span {
    display: block;
    color: var(--muted);
    font-size: 10px;
    margin-top: 3px;
}

.student-transport-report .academic-years {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 7px;
    margin-top: 14px;
    color: var(--sage);
    font-size: 10px;
    font-weight: 700;
}

.student-transport-report .academic-years span {
    padding: 5px 9px;
    border-radius: 5px;
    background: #edf2ed;
}

.student-transport-report .class-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    margin-top: 15px;
    border-top: 1px solid #eeeae1;
    color: #527f70;
    font-size: 11px;
    font-weight: 800;
}

.student-transport-report .empty-state {
    padding: 48px 20px;
    text-align: center;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 10px;
    color: var(--muted);
}

.student-transport-report .empty-state i,
.student-transport-report .empty-state strong,
.student-transport-report .empty-state span {
    display: block;
}

.student-transport-report .empty-state i {
    margin-bottom: 12px;
    font-size: 29px;
    color: #a5ad9f;
}

.student-transport-report .empty-state strong {
    color: var(--ink);
    margin-bottom: 6px;
}

.student-transport-report .empty-state span {
    font-size: 11px;
}

.student-transport-report .footer-note {
    margin-top: 17px;
    color: var(--muted);
    font-size: 10px;
}

.student-transport-report .footer-note i {
    margin-right: 5px;
}

@media(max-width: 1100px) {
    .student-transport-report .summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .student-transport-report .class-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media(max-width: 600px) {
    .student-transport-report .summary-grid,
    .student-transport-report .class-grid {
        grid-template-columns: 1fr;
    }

    .student-transport-report .topbar {
        align-items: flex-start;
        flex-direction: column;
    }

    .student-transport-report .section-heading {
        align-items: flex-start;
    }
}

@media print {
    @page {
        size: A4 landscape;
        margin: 10mm;
    }

    .student-transport-report {
        background: white !important;
    }

    .student-transport-report .top-actions,
    .student-transport-report .footer-note {
        display: none !important;
    }

    .student-transport-report .summary-card,
    .student-transport-report .class-card {
        box-shadow: none !important;
        break-inside: avoid;
    }

    .student-transport-report .class-card {
        color: #263b35 !important;
    }
}
</style>
@endsection
