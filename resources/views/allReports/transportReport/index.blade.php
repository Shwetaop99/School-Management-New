@extends('layouts.app')

@section('title', 'Transport Reports | Admin')
@section('page-title', 'Transport Reports')

@section('content')
@php
    $routeCount = \App\Models\Transport\TransportRoute::count();
    $vehicleCount = \App\Models\Transport\TransportVehicle::count();

    $studentCount = \App\Models\Transport\TransportRecord::where('transport_status', 'active')
        ->whereNotNull('student_id')
        ->distinct('student_id')
        ->count('student_id');
@endphp

<div class="transport-simple">
    <div class="container-fluid py-4">

        {{-- TOP BAR --}}
        <div class="top-bar">
            <a href="{{ route('admin.reports.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Back to Reports
            </a>
        </div>

        {{-- HERO SECTION --}}
        <section class="hero-section">
            <div class="hero-left">
                <div class="hero-icon">
                    <i class="fas fa-bus"></i>
                </div>

                <div class="hero-text">
                    <div class="hero-breadcrumb">ADMINISTRATION / REPORTS</div>
                    <h2>Transport Reports</h2>
                    <p>
                        Manage and view complete transport reports for routes,
                        vehicles, drivers and students using school transport.
                    </p>
                </div>
            </div>

            <div class="hero-right">
                <div class="hero-handwritten">Route. Ride. Report.</div>
                <p>
                    All your school transport information organized
                    in one place.
                </p>
                <div class="hero-note">
                    <i class="fas fa-chart-line"></i>
                    Transport Reporting Dashboard
                </div>
            </div>
        </section>

        {{-- EXACTLY FOUR REPORT CARDS --}}
        <div class="report-section-heading">
            <div>
                <h3>Transport Report Categories</h3>
                <p>Select a category to view its detailed reports.</p>
            </div>
            <span class="report-category-count">04 REPORTS</span>
        </div>

        <div class="report-grid">

            {{-- ROUTES REPORT --}}
            <a href="{{ route('admin.reports.transport.routes') }}"
               class="report-card">

                <div class="card-top">
                    <div class="report-icon route-icon">
                        <i class="fas fa-route"></i>
                    </div>

                    <span class="card-arrow">
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </span>
                </div>

                <div class="report-card-content">
                    <span class="card-label">TRANSPORT MANAGEMENT</span>
                    <h3>Routes Report</h3>

                    <p>
                        View all registered school transport routes,
                        route numbers, starting points, destinations,
                        stops, assigned vehicles and drivers.
                    </p>
                </div>

                <div class="card-stat">
                    <div>
                        <strong>{{ number_format($routeCount) }}</strong>
                        <span>Registered Routes</span>
                    </div>
                    <i class="fas fa-map-location-dot"></i>
                </div>

                <div class="card-footer">
                    <span>View Routes Report</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            {{-- VEHICLES REPORT --}}
            <a href="{{ route('admin.reports.transport.vehicles') }}"
               class="report-card">

                <div class="card-top">
                    <div class="report-icon vehicle-icon">
                        <i class="fas fa-bus"></i>
                    </div>

                    <span class="card-arrow">
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </span>
                </div>

                <div class="report-card-content">
                    <span class="card-label">FLEET MANAGEMENT</span>
                    <h3>Vehicle Summary Report</h3>

                    <p>
                        View vehicle-wise transport assignments, active and inactive students, transport types and total transport fees.
                    </p>
                </div>

                <div class="card-stat">
                    <div>
                        <strong>{{ number_format($vehicleCount) }}</strong>
                        <span>Registered Vehicles</span>
                    </div>
                    <i class="fas fa-truck"></i>
                </div>

                <div class="card-footer">
                    <span>View Vehicle Summary</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            {{-- STUDENT TRANSPORT REPORT --}}
            <a href="{{ route('admin.reports.transport.students') }}"
               class="report-card">

                <div class="card-top">
                    <div class="report-icon student-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>

                    <span class="card-arrow">
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </span>
                </div>

                <div class="report-card-content">
                    <span class="card-label">STUDENT MANAGEMENT</span>
                    <h3>Student Transport Report</h3>

                    <p>
                        Select a class, division and academic year
                        to view students who have applied for school
                        transport and access their individual reports.
                    </p>
                </div>

                <div class="card-stat">
                    <div>
                        <strong>{{ number_format($studentCount) }}</strong>
                        <span>Active Transport Students</span>
                    </div>
                    <i class="fas fa-users"></i>
                </div>

                <div class="card-footer">
                    <span>View Student Transport Report</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>

        </div>

        {{-- INFORMATION FOOTER --}}
        <div class="transport-footer">
            <div class="footer-icon">
                <i class="fas fa-circle-info"></i>
            </div>
            <div>
                <strong>Transport Reporting</strong>
                <p>
                    Open any report category to view detailed information.
                    Individual reports will provide Print, PDF and Excel options.
                </p>
            </div>
        </div>

    </div>
</div>

<style>
.transport-simple {
    --bg: #f7f6f0;
    --paper: #fffefa;
    --ink: #263b35;
    --text: #39483e;
    --muted: #7b857b;
    --line: #e5e1d5;
    --sage: #527f70;
    --dark: #344f43;

    min-height: calc(100vh - 70px);
    background: var(--bg);
    color: var(--text);
    font-family: "Public Sans", Inter, Arial, sans-serif;
}

.transport-simple *,
.transport-simple *::before,
.transport-simple *::after {
    box-sizing: border-box;
}

.transport-simple .container-fluid {
    max-width: 1550px;
    padding-left: 25px;
    padding-right: 25px;
}

/* Top bar */
.transport-simple .top-bar {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.transport-simple .btn-back {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 10px 16px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 8px;
    color: var(--ink);
    font-size: 13px;
    font-weight: 650;
    text-decoration: none;
    transition: .2s ease;
}

.transport-simple .btn-back:hover {
    background: #f1eee4;
    color: var(--dark);
}

/* Hero */
.transport-simple .hero-section {
    display: grid;
    grid-template-columns: 1.45fr 1fr;
    overflow: hidden;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 15px;
    margin-bottom: 32px;
    box-shadow: 0 5px 20px rgba(38, 59, 53, .035);
}

.transport-simple .hero-left {
    display: flex;
    align-items: center;
    gap: 22px;
    padding: 36px;
    background: #fffefa;
}

.transport-simple .hero-icon {
    width: 68px;
    height: 68px;
    min-width: 68px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    background: #f1ead8;
    border: 1px solid #e5dcc7;
    color: #527f70;
    font-size: 27px;
}

.transport-simple .hero-breadcrumb {
    margin-bottom: 9px;
    color: #a87543;
    font-size: 10px;
    font-weight: 750;
    letter-spacing: 1.7px;
}

.transport-simple .hero-text h2 {
    margin: 0 0 10px;
    color: var(--ink);
    font-size: 27px;
    font-weight: 750;
}

.transport-simple .hero-text p {
    max-width: 520px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.8;
}

.transport-simple .hero-right {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 28px;
    text-align: center;
    background: linear-gradient(135deg, #f1ead8, #e8dfcd);
    border-left: 1px solid #e5dcc7;
}

.transport-simple .hero-handwritten {
    margin-bottom: 10px;
    color: #344f43;
    font-family: Georgia, serif;
    font-size: 25px;
    font-style: italic;
}

.transport-simple .hero-right p {
    max-width: 310px;
    margin: 0;
    color: #716b5d;
    font-size: 13px;
    line-height: 1.7;
}

.transport-simple .hero-note {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 18px;
    padding: 9px 15px;
    background: rgba(255,255,255,.65);
    border: 1px solid #e1d7c3;
    border-radius: 30px;
    color: #526c5c;
    font-size: 11px;
    font-weight: 700;
}

/* Section heading */
.transport-simple .report-section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 18px;
}

.transport-simple .report-section-heading h3 {
    margin: 0 0 6px;
    color: var(--ink);
    font-size: 19px;
    font-weight: 750;
}

.transport-simple .report-section-heading p {
    margin: 0;
    color: var(--muted);
    font-size: 12px;
}

.transport-simple .report-category-count {
    padding: 8px 13px;
    border: 1px solid #e1dacb;
    border-radius: 20px;
    background: #f3eee3;
    color: #806c4b;
    font-size: 10px;
    font-weight: 750;
    letter-spacing: .8px;
    white-space: nowrap;
}

/* Exactly four report cards */
.transport-simple .report-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 20px;
}

.transport-simple .report-card {
    display: flex;
    flex-direction: column;
    min-width: 0;
    min-height: 340px;
    padding: 23px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 13px;
    color: var(--text);
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(38, 59, 53, .025);
    transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
}

.transport-simple .report-card:hover {
    transform: translateY(-4px);
    border-color: #b7c7b9;
    box-shadow: 0 10px 25px rgba(38, 59, 53, .08);
    color: var(--text);
}

.transport-simple .card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 23px;
}

.transport-simple .report-icon {
    width: 55px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 13px;
    font-size: 23px;
}

.transport-simple .route-icon {
    background: #edf1e8;
    border: 1px solid #dce5d7;
    color: #527f70;
}

.transport-simple .vehicle-icon {
    background: #f1ead8;
    border: 1px solid #e5dcc7;
    color: #a87543;
}

.transport-simple .student-icon {
    background: #eaf0f2;
    border: 1px solid #d9e4e8;
    color: #426b8a;
}

.transport-simple .card-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 33px;
    height: 33px;
    border: 1px solid var(--line);
    border-radius: 50%;
    color: #829086;
    font-size: 12px;
    transition: .2s ease;
}

.transport-simple .report-card:hover .card-arrow {
    background: var(--dark);
    border-color: var(--dark);
    color: #fff;
}

.transport-simple .report-card-content {
    flex: 1;
}

.transport-simple .card-label {
    display: block;
    margin-bottom: 9px;
    color: #9a805c;
    font-size: 9px;
    font-weight: 750;
    letter-spacing: 1.2px;
}

.transport-simple .report-card-content h3 {
    margin: 0 0 11px;
    color: var(--ink);
    font-size: 19px;
    font-weight: 750;
}

.transport-simple .report-card-content p {
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.85;
}

.transport-simple .card-stat {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-top: 22px;
    padding: 14px;
    background: #f7f6f0;
    border: 1px solid #eeeadf;
    border-radius: 9px;
}

.transport-simple .card-stat div {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.transport-simple .card-stat strong {
    color: var(--ink);
    font-size: 22px;
    font-weight: 750;
}

.transport-simple .card-stat span {
    color: var(--muted);
    font-size: 10px;
}

.transport-simple .card-stat > i {
    color: #84988a;
    font-size: 20px;
}

.transport-simple .card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-top: 18px;
    padding-top: 15px;
    border-top: 1px solid var(--line);
    color: #527f70;
    font-size: 12px;
    font-weight: 750;
}

.transport-simple .card-footer i {
    transition: transform .2s ease;
}

.transport-simple .report-card:hover .card-footer i {
    transform: translateX(5px);
}

/* Footer information */
.transport-simple .transport-footer {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-top: 25px;
    padding: 17px 19px;
    background: #f1eee4;
    border: 1px solid #e6e1d4;
    border-radius: 10px;
}

.transport-simple .footer-icon {
    color: #718b77;
    font-size: 17px;
    padding-top: 2px;
}

.transport-simple .transport-footer strong {
    display: block;
    margin-bottom: 5px;
    color: var(--ink);
    font-size: 12px;
}

.transport-simple .transport-footer p {
    margin: 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

/* Responsive */
@media (max-width: 1050px) {
    .transport-simple .report-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .transport-simple .hero-section {
        grid-template-columns: 1fr;
    }

    .transport-simple .hero-right {
        border-left: none;
        border-top: 1px solid #e5dcc7;
    }
}

@media (max-width: 650px) {
    .transport-simple .container-fluid {
        padding-left: 13px;
        padding-right: 13px;
    }

    .transport-simple .report-grid {
        grid-template-columns: 1fr;
    }

    .transport-simple .hero-left {
        align-items: flex-start;
        padding: 24px 18px;
        gap: 14px;
    }

    .transport-simple .hero-icon {
        width: 49px;
        height: 49px;
        min-width: 49px;
        font-size: 20px;
    }

    .transport-simple .hero-text h2 {
        font-size: 21px;
    }

    .transport-simple .hero-right {
        padding: 24px 15px;
    }

    .transport-simple .report-section-heading {
        align-items: flex-start;
    }

    .transport-simple .report-card {
        min-height: 315px;
    }
}
</style>
@endsection