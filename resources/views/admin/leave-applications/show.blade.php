@extends('layouts.app')

@section('title', 'Leave Details')
@section('page-title', 'Leave Details')

@section('content')

<div style="
    padding:25px;
    background:#f4f7fb;
    min-height:calc(100vh - 80px);
">

    <div style="
        max-width:1000px;
        margin:0 auto;
        background:white;
        border-radius:14px;
        padding:30px;
        box-shadow:0 4px 15px rgba(0,0,0,0.06);
    ">

        <!-- Header -->
        <div style="
            margin-bottom:25px;
        ">

            <h2 style="
                margin:0;
                color:#1e293b;
            ">
                Leave Details
            </h2>

            <p style="
                margin:6px 0 0;
                color:#64748b;
            ">
                View leave application information
            </p>

        </div>


        <!-- Leave Information -->
        <div style="
            display:grid;
            grid-template-columns:repeat(2, 1fr);
            gap:20px;
        ">

            <!-- Teacher -->
            <div style="
                padding:18px;
                background:#f8fafc;
                border-radius:10px;
            ">

                <small style="color:#64748b;">
                    Teacher
                </small>

                <div style="
                    margin-top:6px;
                    font-weight:600;
                    color:#1e293b;
                ">
                    {{ $leaveApplication->teacher->first_name ?? '' }}
                    {{ $leaveApplication->teacher->last_name ?? '' }}
                </div>

            </div>


            <!-- Leave Type -->
            <div style="
                padding:18px;
                background:#f8fafc;
                border-radius:10px;
            ">

                <small style="color:#64748b;">
                    Leave Type
                </small>

                <div style="
                    margin-top:6px;
                    font-weight:600;
                    color:#1e293b;
                ">
                    {{ $leaveApplication->leave_type }}
                </div>

            </div>


            <!-- From Date -->
            <div style="
                padding:18px;
                background:#f8fafc;
                border-radius:10px;
            ">

                <small style="color:#64748b;">
                    From Date
                </small>

                <div style="
                    margin-top:6px;
                    font-weight:600;
                    color:#1e293b;
                ">
                    {{ $leaveApplication->from_date->format('d M Y') }}
                </div>

            </div>


            <!-- To Date -->
            <div style="
                padding:18px;
                background:#f8fafc;
                border-radius:10px;
            ">

                <small style="color:#64748b;">
                    To Date
                </small>

                <div style="
                    margin-top:6px;
                    font-weight:600;
                    color:#1e293b;
                ">
                    {{ $leaveApplication->to_date->format('d M Y') }}
                </div>

            </div>


            <!-- Total Days -->
            <div style="
                padding:18px;
                background:#f8fafc;
                border-radius:10px;
            ">

                <small style="color:#64748b;">
                    Total Days
                </small>

                <div style="
                    margin-top:6px;
                    font-size:20px;
                    font-weight:700;
                    color:#147cf5;
                ">
                    {{ $leaveApplication->total_days }} Days
                </div>

            </div>

        </div>


        <!-- Reason -->
        <div style="
            margin-top:25px;
        ">

            <h4 style="
                margin-bottom:8px;
                color:#1e293b;
            ">
                Reason
            </h4>

            <div style="
                background:#f8fafc;
                padding:15px;
                border-radius:10px;
                color:#475569;
                line-height:1.6;
            ">
                {{ $leaveApplication->reason ?? 'No reason provided.' }}
            </div>

        </div>


        <!-- Actions -->
        <div style="
            margin-top:30px;
            display:flex;
            gap:12px;
        ">

            <a href="{{ route('admin.leave-applications.edit', $leaveApplication) }}"
               style="
                   background:#147cf5;
                   color:white;
                   padding:10px 20px;
                   border-radius:8px;
                   text-decoration:none;
                   font-weight:600;
               ">
                ✏️ Edit
            </a>

            <a href="{{ route('admin.leave-applications.index') }}"
               style="
                   background:#e2e8f0;
                   color:#334155;
                   padding:10px 20px;
                   border-radius:8px;
                   text-decoration:none;
                   font-weight:600;
               ">
                ← Back
            </a>

        </div>

    </div>

</div>

@endsection
