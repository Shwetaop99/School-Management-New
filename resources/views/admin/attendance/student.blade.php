@extends('layouts.app')

@section('title', 'Student Attendance')

@section('content')

<style>
    .attendance-page {
        padding: 30px;
        background: #f4f7fb;
        min-height: calc(100vh - 70px);
    }

    /* Header */
    .attendance-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
    }

    .attendance-title h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #172554;
    }

    .attendance-title p {
        margin: 7px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .attendance-date {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 16px;
        color: #475569;
        font-size: 14px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.05);
    }

    /* Statistics */
    .attendance-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 25px;
    }

    .attendance-stat {
        position: relative;
        overflow: hidden;
        min-height: 135px;
        padding: 22px;
        border-radius: 16px;
        color: #ffffff;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.10);
    }

    .attendance-stat::after {
        content: "";
        position: absolute;
        width: 100px;
        height: 100px;
        right: -25px;
        top: -30px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.10);
    }

    .stat-blue {
        background: linear-gradient(135deg, #1769d1, #2185e5);
    }

    .stat-green {
        background: linear-gradient(135deg, #159957, #21b66f);
    }

    .stat-red {
        background: linear-gradient(135deg, #e5484d, #f45b63);
    }

    .stat-purple {
        background: linear-gradient(135deg, #6741d9, #805ad5);
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: rgba(255, 255, 255, 0.17);
        font-size: 21px;
        margin-bottom: 14px;
    }

    .stat-number {
        font-size: 27px;
        font-weight: 700;
        line-height: 1;
    }

    .stat-label {
        margin-top: 7px;
        font-size: 13px;
        opacity: 0.92;
    }

    /* Main card */
    .attendance-card {
        background: #ffffff;
        border: 1px solid #e5eaf1;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .attendance-card-header {
        padding: 22px 25px;
        border-bottom: 1px solid #edf1f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .attendance-card-title h2 {
        margin: 0;
        font-size: 19px;
        color: #172554;
    }

    .attendance-card-title p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .mark-button {
        border: none;
        background: #1769d1;
        color: #ffffff;
        padding: 11px 17px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: 0.2s;
    }

    .mark-button:hover {
        background: #125bb8;
        transform: translateY(-1px);
    }

    /* Filters */
    .attendance-filters {
        padding: 20px 25px;
        display: grid;
        grid-template-columns: 180px 180px 1fr;
        gap: 14px;
        background: #fafbfd;
        border-bottom: 1px solid #edf1f5;
    }

    .filter-group {
        position: relative;
    }

    .filter-group label {
        display: block;
        margin-bottom: 6px;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
    }

    .filter-group select,
    .filter-group input {
        width: 100%;
        height: 42px;
        border: 1px solid #dbe3ed;
        border-radius: 9px;
        background: #ffffff;
        padding: 0 12px;
        color: #334155;
        outline: none;
        font-size: 13px;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        border-color: #1769d1;
        box-shadow: 0 0 0 3px rgba(23, 105, 209, 0.08);
    }

    /* Table */
    .attendance-table-wrapper {
        overflow-x: auto;
    }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
    }

    .attendance-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        text-align: left;
        padding: 15px 25px;
        border-bottom: 1px solid #edf1f5;
    }

    .attendance-table td {
        padding: 16px 25px;
        color: #334155;
        font-size: 13px;
        border-bottom: 1px solid #f0f3f7;
    }

    .attendance-table tbody tr:hover {
        background: #fafcff;
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .student-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #e5efff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
    }

    .student-name {
        color: #172554;
        font-weight: 600;
    }

    .student-email {
        margin-top: 3px;
        color: #94a3b8;
        font-size: 11px;
    }

    .status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-present {
        color: #15803d;
        background: #dcfce7;
    }

    .status-absent {
        color: #dc2626;
        background: #fee2e2;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .empty-state {
        text-align: center;
        padding: 55px 20px;
        color: #94a3b8;
    }

    .empty-icon {
        font-size: 35px;
        margin-bottom: 10px;
    }

    .empty-state h3 {
        margin: 0 0 6px;
        color: #475569;
        font-size: 16px;
    }

    .empty-state p {
        margin: 0;
        font-size: 13px;
    }

    /* Responsive */
    @media (max-width: 1000px) {
        .attendance-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .attendance-filters {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 650px) {
        .attendance-page {
            padding: 20px 15px;
        }

        .attendance-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 15px;
        }

        .attendance-stats {
            grid-template-columns: 1fr;
        }

        .attendance-filters {
            grid-template-columns: 1fr;
        }

        .attendance-card-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 15px;
        }
    }
</style>


<div class="attendance-page">

    {{-- Page Header --}}
    <div class="attendance-header">

        <div class="attendance-title">
            <h1>Student Attendance</h1>
            <p>Track and manage daily student attendance.</p>
        </div>

        <div class="attendance-date">
            📅 {{ now()->format('d M Y') }}
        </div>

    </div>


    {{-- Statistics --}}
    <div class="attendance-stats">

        <div class="attendance-stat stat-blue">
            <div class="stat-icon">👥</div>
            <div class="stat-number">0</div>
            <div class="stat-label">Total Students</div>
        </div>

        <div class="attendance-stat stat-green">
            <div class="stat-icon">✓</div>
            <div class="stat-number">0</div>
            <div class="stat-label">Present Today</div>
        </div>

        <div class="attendance-stat stat-red">
            <div class="stat-icon">✕</div>
            <div class="stat-number">0</div>
            <div class="stat-label">Absent Today</div>
        </div>

        <div class="attendance-stat stat-purple">
            <div class="stat-icon">%</div>
            <div class="stat-number">0%</div>
            <div class="stat-label">Attendance Rate</div>
        </div>

    </div>


    {{-- Attendance Card --}}
    <div class="attendance-card">

        <div class="attendance-card-header">

            <div class="attendance-card-title">
                <h2>Today's Attendance</h2>
                <p>View the attendance status of students.</p>
            </div>

            <a href="#" class="mark-button">
                + Mark Attendance
            </a>

        </div>


        {{-- Filters --}}
        <div class="attendance-filters">

            <div class="filter-group">
                <label>Class</label>

                <select>
                    <option value="">All Classes</option>
                    <option>Class 1</option>
                    <option>Class 2</option>
                    <option>Class 3</option>
                    <option>Class 4</option>
                    <option>Class 5</option>
                    <option>Class 6</option>
                    <option>Class 7</option>
                    <option>Class 8</option>
                    <option>Class 9</option>
                    <option>Class 10</option>
                </select>
            </div>


            <div class="filter-group">
                <label>Division</label>

                <select>
                    <option value="">All Divisions</option>
                    <option>A</option>
                    <option>B</option>
                    <option>C</option>
                    <option>D</option>
                </select>
            </div>


            <div class="filter-group">
                <label>Search Student</label>

                <input
                    type="text"
                    placeholder="Search by student name or roll number..."
                >
            </div>

        </div>


        {{-- Student Table --}}
        <div class="attendance-table-wrapper">

            <table class="attendance-table">

                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Roll No.</th>
                        <th>Class</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    {{-- Students will be loaded dynamically later --}}

                    <tr>
                        <td colspan="4">

                            <div class="empty-state">

                                <div class="empty-icon">
                                    👨‍🎓
                                </div>

                                <h3>No students available</h3>

                            </div>

                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection