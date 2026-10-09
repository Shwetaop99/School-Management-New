@extends('layouts.app')

@section('title', 'Sports Event Notice | Admin')

@php
    use Illuminate\Support\Str;

    /*
    |--------------------------------------------------------------------------
    | SCHOOL SETTINGS — PRINT ONLY
    |--------------------------------------------------------------------------
    */

    $school = DB::table('school_settings')->first();

    $schoolName = $school?->school_name ?: 'Gurukul Vidyalaya';

    $schoolLogo = null;

    if ($school?->logo) {
        $logo = ltrim(trim($school->logo), '/');

        if (filter_var($logo, FILTER_VALIDATE_URL)) {
            $schoolLogo = $logo;
        } elseif (Str::startsWith($logo, ['storage/', 'images/', 'uploads/'])) {
            $schoolLogo = asset($logo);
        } else {
            $schoolLogo = asset('storage/' . $logo);
        }
    }

    $schoolLogo = $schoolLogo ?: asset('images/gurukullogo.png');


    /*
    |--------------------------------------------------------------------------
    | EVENT DATA
    |--------------------------------------------------------------------------
    */

    $noticeNumber = 'SP-' . str_pad($game->id, 4, '0', STR_PAD_LEFT);

    $eventDate = $game->event_date
        ? \Carbon\Carbon::parse($game->event_date)->format('d M Y')
        : '—';

    $noticeDate = $game->created_at
        ? \Carbon\Carbon::parse($game->created_at)->format('d M Y')
        : now()->format('d M Y');

    $eventTime = '—';

    if (!empty($game->start_time)) {
        try {
            $eventTime = \Carbon\Carbon::parse($game->start_time)->format('h:i A');

            if (!empty($game->end_time)) {
                $eventTime .= ' - ' . \Carbon\Carbon::parse($game->end_time)->format('h:i A');
            }
        } catch (\Throwable $e) {
            $eventTime = $game->start_time;

            if (!empty($game->end_time)) {
                $eventTime .= ' - ' . $game->end_time;
            }
        }
    }


    $eventType = $game->event_type
        ?? $game->type
        ?? 'Sports Event';


    $sportName = $game->sport_name
        ?? $game->sport
        ?? $game->game_name
        ?? 'Sports';


    $venue = $game->venue
        ?? $game->location
        ?? 'School Ground';


    /*
    |--------------------------------------------------------------------------
    | PARTICIPANTS
    |--------------------------------------------------------------------------
    |
    | Participants are based on the existing class and section fields
    | from the sports_games table.
    |
    | Examples:
    |   Class 5 - Section A
    |   Class 8
    |   Section B
    |   All Students
    |
    */

    $participantClass = trim((string) ($game->class ?? ''));
    $participantSection = trim((string) ($game->section ?? ''));

    if ($participantClass && $participantSection) {

        $participants = 'Class ' . $participantClass
            . ' - Section ' . $participantSection;

    } elseif ($participantClass) {

        $participants = 'Class ' . $participantClass;

    } elseif ($participantSection) {

        $participants = 'Section ' . $participantSection;

    } else {

        $participants = 'All Students';

    }


    /*
    |--------------------------------------------------------------------------
    | OTHER EVENT DATA
    |--------------------------------------------------------------------------
    */

    $academicYear = $game->academic_year
        ?? $game->year
        ?? '—';


    $organizer = $game->organizer
        ?? $game->organized_by
        ?? $game->conducted_by
        ?? 'Sports Department';


    /*
    |--------------------------------------------------------------------------
    | SPORT RULES
    |--------------------------------------------------------------------------
    */

    $sportRules = [

        'Cricket' => [
            'Players must report to the venue at least 15 minutes before the scheduled time.',
            'All players must follow the instructions given by the Sports Department and officials.',
            'Players must wear proper sports uniform and appropriate footwear.',
            'Any form of indiscipline or misconduct will result in disqualification.',
            'The decision of the appointed officials shall be final.',
        ],

        'Football' => [
            'Players must report to the venue at least 15 minutes before the scheduled time.',
            'All players must wear proper sports uniform and suitable football footwear.',
            'Players must follow all safety instructions given by the Sports Department.',
            'Rough play, misconduct or violation of rules may lead to disqualification.',
            'The decision of the appointed officials shall be final.',
        ],

        'Basketball' => [
            'Participants must report to the venue before the scheduled reporting time.',
            'Proper sports uniform and footwear are compulsory.',
            'Players must maintain discipline throughout the event.',
            'Unsafe or inappropriate conduct will not be tolerated.',
            'The decision of the appointed officials shall be final.',
        ],

        'Volleyball' => [
            'Participants must report to the venue at least 15 minutes before the event.',
            'Proper sports uniform and footwear are compulsory.',
            'Players must follow the instructions of the officials and Sports Department.',
            'Any misconduct may result in removal from the event.',
            'The decision of the appointed officials shall be final.',
        ],

        'Badminton' => [
            'Participants must report before the scheduled match time.',
            'Players must carry and use appropriate sports equipment.',
            'Proper sports attire and footwear are compulsory.',
            'Participants must maintain discipline and sportsmanship.',
            'The decision of the appointed officials shall be final.',
        ],

        'Athletics' => [
            'Participants must report to the venue before the scheduled reporting time.',
            'Proper sports uniform and suitable footwear are compulsory.',
            'Participants must follow all safety instructions.',
            'Any unfair practice or misconduct may lead to disqualification.',
            'The decision of the appointed officials shall be final.',
        ],

        'Table Tennis' => [
            'Participants must report before the scheduled match time.',
            'Proper sports attire is compulsory.',
            'Participants must maintain discipline and sportsmanship.',
            'All equipment must be handled carefully.',
            'The decision of the appointed officials shall be final.',
        ],

        'Chess' => [
            'Participants must report to the venue before the scheduled start time.',
            'Participants must maintain complete silence during the game.',
            'Any form of unfair assistance is strictly prohibited.',
            'Players must maintain discipline throughout the event.',
            'The decision of the appointed officials shall be final.',
        ],

    ];


    $currentRules = $sportRules[$sportName] ?? [

        'Participants must report to the venue before the scheduled event time.',
        'All participants must wear proper sports uniform and appropriate footwear.',
        'Participants must follow the instructions given by the Sports Department and event officials.',
        'Participants must maintain discipline and sportsmanship throughout the event.',
        'The decision of the appointed officials shall be final.',

    ];
@endphp


@section('content')

<style>

/* =========================================================
   SPORTS EVENT NOTICE PAGE
========================================================= */

.sports-event-notice-page {
    min-height: calc(100vh - 70px);
    padding: 30px;

    background:
        radial-gradient(
            circle at 5% 0%,
            rgba(23,105,209,.08),
            transparent 28%
        ),
        radial-gradient(
            circle at 100% 10%,
            rgba(21,156,199,.08),
            transparent 30%
        ),
        #f4f7fb;

    color: #172033;
}


/* =========================================================
   PAGE HEADER
========================================================= */

.event-page-header {
    max-width: 1180px;
    margin: 0 auto 20px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;
}

.event-page-heading {
    display: flex;
    align-items: center;
    gap: 13px;
}

.event-page-icon {
    width: 46px;
    height: 46px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 13px;

    background:
        linear-gradient(
            135deg,
            #1769d1,
            #159cc7
        );

    color: #fff;

    font-size: 17px;

    box-shadow:
        0 8px 20px
        rgba(23,105,209,.20);
}

.event-page-eyebrow {
    margin-bottom: 3px;

    color: #1769d1;

    font-size: 9px;
    font-weight: 800;

    letter-spacing: .15em;

    text-transform: uppercase;
}

.event-page-heading h1 {
    margin: 0;

    color: #172033;

    font-size: 21px;
    font-weight: 800;

    letter-spacing: -.025em;
}

.event-page-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.dashboard-secondary-btn,
.dashboard-primary-btn {

    height: 40px;

    padding: 0 15px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 7px;

    border-radius: 9px;

    text-decoration: none;

    font-size: 11.5px;
    font-weight: 700;

    cursor: pointer;

    transition: .2s ease;
}

.dashboard-secondary-btn {

    color: #475569;

    background: #fff;

    border: 1px solid #dce4ed;
}

.dashboard-secondary-btn:hover {

    color: #1769d1;

    background: #f8fbff;

    border-color: #b9d2ef;
}

.dashboard-primary-btn {

    color: #fff;

    border: 0;

    background:
        linear-gradient(
            135deg,
            #1769d1,
            #159cc7
        );

    box-shadow:
        0 6px 16px
        rgba(23,105,209,.20);
}

.dashboard-primary-btn:hover {

    color: #fff;

    transform: translateY(-1px);

    box-shadow:
        0 9px 21px
        rgba(23,105,209,.26);
}


/* =========================================================
   MAIN NOTICE CARD
========================================================= */

.notice-card {

    position: relative;

    max-width: 1180px;

    margin: 0 auto;

    overflow: hidden;

    background: #fff;

    border: 1px solid #dfe6ee;

    border-radius: 18px;

    box-shadow:
        0 18px 45px
        rgba(15,23,42,.08);
}

.notice-card::before {

    content: "";

    position: absolute;

    top: 0;
    left: 0;
    right: 0;

    height: 4px;

    background:
        linear-gradient(
            90deg,
            #1769d1,
            #159cc7,
            #20b8a6
        );
}


/* =========================================================
   PROFESSIONAL NOTICE HEADER
========================================================= */

.notice-top {

    min-height: 86px;

    padding: 20px 30px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    border-bottom: 1px solid #e8edf3;

    background:
        linear-gradient(
            180deg,
            #ffffff 0%,
            #fafdff 100%
        );
}

.notice-top-left {

    display: flex;
    align-items: center;

    gap: 12px;
}

.notice-document-icon {

    width: 42px;
    height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background:
        linear-gradient(
            135deg,
            #edf5ff,
            #e8f8fb
        );

    color: #1769d1;

    font-size: 15px;

    border: 1px solid #dcecff;
}

.notice-top-title {

    margin: 0;

    color: #172033;

    font-size: 14px;

    font-weight: 800;

    letter-spacing: -.01em;
}

.notice-top-subtitle {

    margin-top: 4px;

    color: #94a3b8;

    font-size: 10px;

    font-weight: 500;
}

.notice-reference {

    min-width: 135px;

    padding-left: 18px;

    text-align: right;

    border-left: 1px solid #e2e8f0;
}

.notice-reference-label {

    color: #94a3b8;

    font-size: 8px;

    font-weight: 800;

    letter-spacing: .13em;

    text-transform: uppercase;
}

.notice-reference-number {

    margin-top: 4px;

    color: #1769d1;

    font-size: 13px;

    font-weight: 850;
}


/* =========================================================
   EVENT HERO
========================================================= */

.event-hero {

    position: relative;

    padding: 30px;

    display: grid;

    grid-template-columns: 76px 1fr;

    gap: 19px;

    align-items: center;

    border-bottom: 1px solid #edf1f5;

    background:
        linear-gradient(
            135deg,
            #f6faff 0%,
            #ffffff 58%,
            #fbfeff 100%
        );
}

.event-hero::after {

    content: "";

    position: absolute;

    width: 150px;
    height: 150px;

    right: -55px;
    top: -75px;

    border-radius: 50%;

    background:
        rgba(23,105,209,.035);

    pointer-events: none;
}

.sport-icon {

    width: 76px;
    height: 76px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 18px;

    color: #fff;

    font-size: 27px;

    background:
        linear-gradient(
            145deg,
            #1769d1,
            #159cc7
        );

    box-shadow:
        0 11px 25px
        rgba(23,105,209,.22);
}

.event-hero-label {

    margin-bottom: 6px;

    color: #1769d1;

    font-size: 9px;

    font-weight: 850;

    letter-spacing: .15em;

    text-transform: uppercase;
}

.event-hero h2 {

    margin: 0;

    color: #111827;

    font-size: 27px;

    line-height: 1.2;

    font-weight: 850;

    letter-spacing: -.035em;
}

.event-hero-meta {

    margin-top: 10px;

    display: flex;

    flex-wrap: wrap;

    align-items: center;

    gap: 7px;
}

.event-pill {

    padding: 6px 10px;

    display: inline-flex;

    align-items: center;

    gap: 5px;

    border-radius: 7px;

    background: #f1f5f9;

    color: #475569;

    font-size: 9px;

    font-weight: 750;

    border: 1px solid #e4eaf0;
}

.event-pill.primary {

    background: #edf5ff;

    color: #1769d1;

    border-color: #d8eaff;
}


/* =========================================================
   NOTICE CONTENT
========================================================= */

.notice-content {

    padding: 28px 30px 32px;
}

.section-title {

    margin-bottom: 12px;

    display: flex;

    align-items: center;

    gap: 8px;

    color: #172033;

    font-size: 12px;

    font-weight: 850;

    letter-spacing: -.01em;
}

.section-title i {

    width: 25px;
    height: 25px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 7px;

    background: #edf5ff;

    color: #1769d1;

    font-size: 10px;
}


/* =========================================================
   EVENT INFORMATION
========================================================= */

.event-information {

    display: grid;

    grid-template-columns:
        repeat(6, 1fr);

    border: 1px solid #dfe7ef;

    border-radius: 12px;

    overflow: hidden;

    background: #fff;
}

.info-item {

    min-height: 82px;

    padding: 14px 13px;

    background: #fff;

    border-right: 1px solid #e1e8f0;

    transition: .18s ease;
}

.info-item:last-child {

    border-right: 0;
}

.info-item:hover {

    background: #fafdff;
}


/* =========================================================
   PARTICIPANT INFO
========================================================= */

.info-item.participant-info {

    background:
        linear-gradient(
            145deg,
            #f4fbff,
            #f8fdff
        );
}

.info-item.participant-info:hover {

    background:
        linear-gradient(
            145deg,
            #eef9ff,
            #f6fdff
        );
}

.info-label {

    margin-bottom: 7px;

    color: #94a3b8;

    font-size: 7.5px;

    font-weight: 850;

    letter-spacing: .08em;

    text-transform: uppercase;
}

.info-value {

    display: flex;

    align-items: center;

    gap: 7px;

    color: #172033;

    font-size: 11px;

    font-weight: 750;

    line-height: 1.4;
}

.info-value i {

    color: #1769d1;

    font-size: 11px;
}

.participant-info .info-value {

    color: #1769d1;

    font-size: 11.5px;

    font-weight: 850;

    line-height: 1.5;
}

.participant-info .info-value i {

    color: #159cc7;

    flex-shrink: 0;
}


/* =========================================================
   SECONDARY INFORMATION
========================================================= */

.secondary-information {

    margin-top: 11px;

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 11px;
}

.secondary-item {

    min-height: 68px;

    padding: 13px 15px;

    border: 1px solid #e1e8ef;

    border-radius: 10px;

    background:
        linear-gradient(
            145deg,
            #fbfcfe,
            #f8fafc
        );
}

.secondary-item-label {

    margin-bottom: 6px;

    color: #94a3b8;

    font-size: 8px;

    font-weight: 850;

    letter-spacing: .08em;

    text-transform: uppercase;
}

.secondary-item-value {

    color: #334155;

    font-size: 11px;

    font-weight: 750;
}


/* =========================================================
   DESCRIPTION
========================================================= */

.description-section {

    margin-top: 26px;
}

.description-box {

    padding: 16px 17px;

    border: 1px solid #dfe7ef;

    border-left:
        3px solid #1769d1;

    border-radius: 0 10px 10px 0;

    background:
        linear-gradient(
            90deg,
            #f7fbff,
            #fbfdff
        );

    color: #475569;

    font-size: 11.5px;

    line-height: 1.75;
}


/* =========================================================
   RULES
========================================================= */

.rules-section {

    margin-top: 26px;
}

.rules-list {

    display: grid;

    gap: 8px;
}

.rule-item {

    display: flex;

    align-items: flex-start;

    gap: 11px;

    padding: 10px 12px;

    border: 1px solid #e3e9f0;

    border-radius: 9px;

    background: #fff;

    transition: .18s ease;
}

.rule-item:hover {

    border-color: #c9dced;

    background: #fbfdff;

    transform: translateX(2px);
}

.rule-number {

    width: 25px;
    height: 25px;

    flex: 0 0 25px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 7px;

    background: #edf5ff;

    color: #1769d1;

    font-size: 9px;

    font-weight: 850;
}

.rule-text {

    padding-top: 2px;

    color: #475569;

    font-size: 11px;

    line-height: 1.6;
}


/* =========================================================
   IMPORTANT NOTICE
========================================================= */

.important-notice {

    margin-top: 25px;

    padding: 15px 16px;

    display: flex;

    align-items: flex-start;

    gap: 11px;

    border: 1px solid #efd9aa;

    border-radius: 10px;

    background:
        linear-gradient(
            135deg,
            #fffaf1,
            #fffdf8
        );
}

.important-icon {

    width: 29px;
    height: 29px;

    flex: 0 0 29px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 8px;

    background: #fff0d0;

    color: #b87300;

    font-size: 11px;
}

.important-title {

    margin-bottom: 4px;

    color: #936000;

    font-size: 9px;

    font-weight: 850;

    letter-spacing: .08em;

    text-transform: uppercase;
}

.important-text {

    color: #795b22;

    font-size: 11px;

    line-height: 1.6;
}


/* =========================================================
   NOTICE FOOTER
========================================================= */

.notice-bottom {

    padding: 17px 30px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    border-top: 1px solid #e3e9f0;

    background:
        linear-gradient(
            180deg,
            #fbfcfe,
            #f7f9fb
        );
}

.notice-bottom-left {

    color: #64748b;

    font-size: 9.5px;

    line-height: 1.6;
}

.notice-bottom-left strong {

    color: #334155;

    font-weight: 800;
}

.notice-bottom-right {

    text-align: right;

    color: #94a3b8;

    font-size: 8.5px;

    line-height: 1.7;
}


/* =========================================================
   PRINT NOTICE
========================================================= */

.print-notice {

    display: none;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .event-information {

        grid-template-columns:
            repeat(3, 1fr);
    }

    .info-item:nth-child(3) {

        border-right: 0;
    }

    .info-item:nth-child(-n+3) {

        border-bottom:
            1px solid #e1e8f0;
    }

    .info-item:nth-child(4) {

        border-bottom:
            0;
    }

    .info-item:nth-child(5) {

        border-bottom:
            0;
    }
}

@media (max-width: 900px) {

    .event-information {

        grid-template-columns:
            repeat(2, 1fr);
    }

    .info-item {

        border-right:
            1px solid #e1e8f0;
    }

    .info-item:nth-child(2n) {

        border-right: 0;
    }

    .info-item:nth-child(-n+4) {

        border-bottom:
            1px solid #e1e8f0;
    }

    .secondary-information {

        grid-template-columns: 1fr;
    }
}

@media (max-width: 700px) {

    .sports-event-notice-page {

        padding: 15px;
    }

    .event-page-header {

        align-items: flex-start;

        flex-direction: column;
    }

    .event-page-actions {

        width: 100%;
    }

    .event-page-actions a,
    .event-page-actions button {

        flex: 1;
    }

    .notice-top {

        align-items: flex-start;

        flex-direction: column;

        padding: 20px;
    }

    .notice-reference {

        width: 100%;

        padding-left: 0;

        padding-top: 10px;

        text-align: left;

        border-left: 0;

        border-top: 1px solid #e2e8f0;
    }

    .event-hero {

        grid-template-columns: 1fr;

        padding: 24px 20px;
    }

    .sport-icon {

        width: 58px;
        height: 58px;

        border-radius: 13px;

        font-size: 21px;
    }

    .event-hero h2 {

        font-size: 22px;
    }

    .notice-content {

        padding: 22px 20px 25px;
    }

    .event-information {

        grid-template-columns: 1fr;
    }

    .info-item {

        border-right: 0 !important;

        border-bottom:
            1px solid #e1e8f0;
    }

    .info-item:last-child {

        border-bottom: 0;
    }

    .notice-bottom {

        padding: 17px 20px;

        align-items: flex-start;

        flex-direction: column;
    }

    .notice-bottom-right {

        text-align: left;
    }
}


/* =========================================================
   PRINT
========================================================= */

@media print {

    @page {

        size: A4 portrait;

        margin: 10mm;
    }

    html,
    body {

        width: 210mm;

        min-height: 297mm;

        margin: 0 !important;

        padding: 0 !important;

        background: #fff !important;
    }

    body * {

        visibility: hidden !important;
    }

    .print-notice,
    .print-notice * {

        visibility: visible !important;
    }

    .print-notice {

        display: block !important;

        position: absolute !important;

        top: 0 !important;

        left: 0 !important;

        width: 190mm !important;

        min-height: 277mm !important;

        margin: 0 !important;

        padding: 0 !important;

        background: #fff !important;

        color: #111827 !important;

        font-family:
            Arial,
            Helvetica,
            sans-serif !important;
    }

    .print-header {

        display: flex;

        align-items: center;

        justify-content: center;

        gap: 15px;

        padding-bottom: 12px;

        border-bottom: 2px solid #172033;
    }

    .print-school-logo {

        width: 62px;
        height: 62px;

        display: flex;

        align-items: center;
        justify-content: center;
    }

    .print-school-logo img {

        width: 62px;
        height: 62px;

        object-fit: contain;
    }

    .print-school-details {

        text-align: center;
    }

    .print-school-name {

        color: #111827;

        font-size: 18px;

        font-weight: 800;

        text-transform: uppercase;

        line-height: 1.25;
    }

    .print-department {

        margin-top: 3px;

        color: #475569;

        font-size: 10px;

        font-weight: 700;

        letter-spacing: .12em;

        text-transform: uppercase;
    }

    .print-official {

        margin-top: 4px;

        color: #1769d1;

        font-size: 9px;

        font-weight: 800;

        letter-spacing: .15em;

        text-transform: uppercase;
    }

    .print-notice-meta {

        display: flex;

        justify-content: space-between;

        padding: 10px 0;

        border-bottom: 1px solid #cbd5e1;

        color: #475569;

        font-size: 9px;
    }

    .print-notice-meta strong {

        color: #172033;
    }

    .print-event-heading {

        padding: 17px 0 14px;

        text-align: center;
    }

    .print-event-heading h1 {

        margin: 0;

        color: #111827;

        font-size: 21px;

        font-weight: 800;

        text-transform: uppercase;

        letter-spacing: .02em;
    }

    .print-event-heading p {

        margin: 5px 0 0;

        color: #64748b;

        font-size: 10px;
    }

    .print-details-table {

        width: 100%;

        border-collapse: collapse;

        margin-bottom: 15px;
    }

    .print-details-table td {

        width: 50%;

        padding: 8px 9px;

        border: 1px solid #cbd5e1;

        vertical-align: top;
    }

    .print-label {

        display: block;

        margin-bottom: 3px;

        color: #64748b;

        font-size: 8px;

        font-weight: 800;

        text-transform: uppercase;

        letter-spacing: .06em;
    }

    .print-value {

        color: #172033;

        font-size: 10px;

        font-weight: 700;
    }

    .print-section-title {

        margin: 15px 0 7px;

        padding-bottom: 5px;

        border-bottom: 1px solid #172033;

        color: #172033;

        font-size: 11px;

        font-weight: 800;

        text-transform: uppercase;

        letter-spacing: .06em;
    }

    .print-description {

        color: #334155;

        font-size: 10px;

        line-height: 1.65;
    }

    .print-rules {

        margin: 0;

        padding-left: 20px;
    }

    .print-rules li {

        margin-bottom: 5px;

        padding-left: 3px;

        color: #334155;

        font-size: 9.5px;

        line-height: 1.55;
    }

    .print-important {

        margin-top: 15px;

        padding: 9px 11px;

        border: 1px solid #d6b36a;

        background: #fffaf0;
    }

    .print-important strong {

        display: block;

        margin-bottom: 3px;

        color: #8a5a00;

        font-size: 9px;

        text-transform: uppercase;
    }

    .print-important span {

        color: #5f4a25;

        font-size: 9.5px;

        line-height: 1.5;
    }

    .print-signature-area {

        margin-top: 32px;

        display: flex;

        justify-content: flex-end;
    }

    .print-signature {

        width: 55mm;

        text-align: center;
    }

    .print-signature-line {

        margin-bottom: 5px;

        border-top: 1px solid #172033;
    }

    .print-signature strong {

        display: block;

        color: #172033;

        font-size: 9px;
    }

    .print-signature span {

        display: block;

        margin-top: 2px;

        color: #64748b;

        font-size: 8px;
    }

    .print-footer {

        position: fixed;

        bottom: 0;

        left: 0;

        right: 0;

        padding-top: 7px;

        border-top: 1px solid #cbd5e1;

        text-align: center;

        color: #64748b;

        font-size: 7.5px;
    }

    .print-rules,
    .print-important,
    .print-details-table {

        break-inside: avoid;
    }
}

</style>


<div class="sports-event-notice-page">


    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="event-page-header">

        <div class="event-page-heading">

            <div class="event-page-icon">

                <i class="fas fa-bullhorn"></i>

            </div>

            <div>

                <div class="event-page-eyebrow">
                    Sports Management
                </div>

                <h1>
                    Sports Event Notice
                </h1>

            </div>

        </div>


        <div class="event-page-actions">

            <a href="{{ url()->previous() }}"
               class="dashboard-secondary-btn">

                <i class="fas fa-arrow-left"></i>

                Back to Games

            </a>


            <button type="button"
                    onclick="window.print()"
                    class="dashboard-primary-btn">

                <i class="fas fa-print"></i>

                Print Notice

            </button>

        </div>

    </div>


    {{-- =====================================================
         PROFESSIONAL SCREEN NOTICE
         SCHOOL NAME / LOGO REMOVED FROM SCREEN
    ====================================================== --}}

    <div class="notice-card">


        {{-- NOTICE TOP --}}

        <div class="notice-top">

            <div class="notice-top-left">

                <div class="notice-document-icon">

                    <i class="fas fa-file-lines"></i>

                </div>

                <div>

                    <div class="notice-top-title">
                        Sports Event Notice
                    </div>

                    <div class="notice-top-subtitle">
                        Official Event Information &amp; Guidelines
                    </div>

                </div>

            </div>


            <div class="notice-reference">

                <div class="notice-reference-label">
                    Notice Reference
                </div>

                <div class="notice-reference-number">
                    {{ $noticeNumber }}
                </div>

            </div>

        </div>


        {{-- EVENT HERO --}}

        <div class="event-hero">


            <div class="sport-icon">

                <i class="fas fa-trophy"></i>

            </div>


            <div>

                <div class="event-hero-label">
                    Sports Event Announcement
                </div>

                <h2>
                    {{ $game->title ?? $game->event_name ?? 'Sports Event' }}
                </h2>


                <div class="event-hero-meta">

                    <div class="event-pill primary">

                        <i class="fas fa-medal"></i>

                        {{ $sportName }}

                    </div>


                    <div class="event-pill">

                        <i class="fas fa-tag"></i>

                        {{ $eventType }}

                    </div>


                    <div class="event-pill">

                        <i class="fas fa-hashtag"></i>

                        Event #{{ $game->id }}

                    </div>

                </div>

            </div>

        </div>


        {{-- NOTICE CONTENT --}}

        <div class="notice-content">


            {{-- EVENT INFORMATION --}}

            <div class="section-title">

                <i class="fas fa-calendar-days"></i>

                Event Information

            </div>


            <div class="event-information">


                <div class="info-item">

                    <div class="info-label">
                        Sport
                    </div>

                    <div class="info-value">

                        <i class="fas fa-medal"></i>

                        {{ $sportName }}

                    </div>

                </div>


                <div class="info-item">

                    <div class="info-label">
                        Event Type
                    </div>

                    <div class="info-value">

                        <i class="fas fa-tag"></i>

                        {{ $eventType }}

                    </div>

                </div>


                <div class="info-item">

                    <div class="info-label">
                        Event Date
                    </div>

                    <div class="info-value">

                        <i class="far fa-calendar"></i>

                        {{ $eventDate }}

                    </div>

                </div>


                <div class="info-item">

                    <div class="info-label">
                        Time
                    </div>

                    <div class="info-value">

                        <i class="far fa-clock"></i>

                        {{ $eventTime }}

                    </div>

                </div>


                <div class="info-item">

                    <div class="info-label">
                        Venue
                    </div>

                    <div class="info-value">

                        <i class="fas fa-location-dot"></i>

                        {{ $venue }}

                    </div>

                </div>


                {{-- PARTICIPANTS --}}

                <div class="info-item participant-info">

                    <div class="info-label">
                        Participants
                    </div>

                    <div class="info-value">

                        <i class="fas fa-users"></i>

                        {{ $participants }}

                    </div>

                </div>


            </div>


            {{-- SECONDARY INFORMATION --}}

            <div class="secondary-information">


                <div class="secondary-item">

                    <div class="secondary-item-label">
                        Academic Year
                    </div>

                    <div class="secondary-item-value">
                        {{ $academicYear }}
                    </div>

                </div>


                <div class="secondary-item">

                    <div class="secondary-item-label">
                        Organizer
                    </div>

                    <div class="secondary-item-value">
                        {{ $organizer }}
                    </div>

                </div>


                <div class="secondary-item">

                    <div class="secondary-item-label">
                        Notice Date
                    </div>

                    <div class="secondary-item-value">
                        {{ $noticeDate }}
                    </div>

                </div>


            </div>


            {{-- DESCRIPTION --}}

            @if(!empty($game->description))

                <div class="description-section">

                    <div class="section-title">

                        <i class="fas fa-align-left"></i>

                        Event Description

                    </div>


                    <div class="description-box">

                        {{ $game->description }}

                    </div>

                </div>

            @endif


            {{-- RULES --}}

            <div class="rules-section">

                <div class="section-title">

                    <i class="fas fa-list-check"></i>

                    Participation Guidelines &amp; Rules

                </div>


                <div class="rules-list">

                    @foreach($currentRules as $index => $rule)

                        <div class="rule-item">

                            <div class="rule-number">
                                {{ $index + 1 }}
                            </div>

                            <div class="rule-text">
                                {{ $rule }}
                            </div>

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- IMPORTANT NOTICE --}}

            <div class="important-notice">

                <div class="important-icon">

                    <i class="fas fa-triangle-exclamation"></i>

                </div>

                <div>

                    <div class="important-title">
                        Important Notice
                    </div>

                    <div class="important-text">

                        All participants are requested to report to
                        the venue before the scheduled event time and
                        follow the instructions of the Sports Department
                        and appointed officials.

                    </div>

                </div>

            </div>


        </div>


        {{-- NOTICE FOOTER --}}

        <div class="notice-bottom">

            <div class="notice-bottom-left">

                <strong>
                    Official Sports Event Notice
                </strong>

                <br>

                Please follow all event instructions and maintain
                proper discipline throughout the event.

            </div>


            <div class="notice-bottom-right">

                Notice No. {{ $noticeNumber }}

                <br>

                Event ID #{{ $game->id }}

            </div>

        </div>


    </div>


    {{-- =====================================================
         PRINT-ONLY NOTICE
         SCHOOL NAME + LOGO REMAIN HERE
    ====================================================== --}}

    <div class="print-notice">


        {{-- PRINT HEADER --}}

        <div class="print-header">

            <div class="print-school-logo">

                <img src="{{ $schoolLogo }}"
                     alt="{{ $schoolName }} Logo">

            </div>

            <div class="print-school-details">

                <div class="print-school-name">
                    {{ $schoolName }}
                </div>

                <div class="print-department">
                    Sports Department
                </div>

                <div class="print-official">
                    Official School Notice
                </div>

            </div>

        </div>


        {{-- PRINT META --}}

        <div class="print-notice-meta">

            <div>

                <strong>
                    Notice No:
                </strong>

                {{ $noticeNumber }}

            </div>


            <div>

                <strong>
                    Date:
                </strong>

                {{ $noticeDate }}

            </div>

        </div>


        {{-- PRINT TITLE --}}

        <div class="print-event-heading">

            <h1>
                {{ $game->title ?? $game->event_name ?? 'Sports Event' }}
            </h1>

            <p>
                Official Sports Event Notice
            </p>

        </div>


        {{-- PRINT EVENT DETAILS --}}

        <table class="print-details-table">

            <tr>

                <td>

                    <span class="print-label">
                        Sport
                    </span>

                    <span class="print-value">
                        {{ $sportName }}
                    </span>

                </td>


                <td>

                    <span class="print-label">
                        Event Type
                    </span>

                    <span class="print-value">
                        {{ $eventType }}
                    </span>

                </td>

            </tr>


            <tr>

                <td>

                    <span class="print-label">
                        Event Date
                    </span>

                    <span class="print-value">
                        {{ $eventDate }}
                    </span>

                </td>


                <td>

                    <span class="print-label">
                        Time
                    </span>

                    <span class="print-value">
                        {{ $eventTime }}
                    </span>

                </td>

            </tr>


            <tr>

                <td>

                    <span class="print-label">
                        Venue
                    </span>

                    <span class="print-value">
                        {{ $venue }}
                    </span>

                </td>


                <td>

                    <span class="print-label">
                        Participants
                    </span>

                    <span class="print-value">
                        {{ $participants }}
                    </span>

                </td>

            </tr>


            <tr>

                <td>

                    <span class="print-label">
                        Academic Year
                    </span>

                    <span class="print-value">
                        {{ $academicYear }}
                    </span>

                </td>


                <td>

                    <span class="print-label">
                        Organizer
                    </span>

                    <span class="print-value">
                        {{ $organizer }}
                    </span>

                </td>

            </tr>

        </table>


        {{-- DESCRIPTION --}}

        @if(!empty($game->description))

            <div class="print-section-title">
                Event Description
            </div>

            <div class="print-description">
                {{ $game->description }}
            </div>

        @endif


        {{-- RULES --}}

        <div class="print-section-title">
            Participation Guidelines &amp; Rules
        </div>


        <ol class="print-rules">

            @foreach($currentRules as $rule)

                <li>
                    {{ $rule }}
                </li>

            @endforeach

        </ol>


        {{-- IMPORTANT NOTICE --}}

        <div class="print-important">

            <strong>
                Important Notice
            </strong>

            <span>

                All participants are requested to report to the venue
                before the scheduled event time and follow the instructions
                of the Sports Department and appointed officials.

            </span>

        </div>


        {{-- SIGNATURE --}}

        <div class="print-signature-area">

            <div class="print-signature">

                <div class="print-signature-line"></div>

                <strong>
                    Sports Department
                </strong>

                <span>
                    {{ $schoolName }}
                </span>

            </div>

        </div>


        {{-- PRINT FOOTER --}}

        <div class="print-footer">

            {{ $schoolName }}

            &nbsp; | &nbsp;

            Sports Department

            &nbsp; | &nbsp;

            Notice No. {{ $noticeNumber }}

            &nbsp; | &nbsp;

            Event ID #{{ $game->id }}

        </div>


    </div>


</div>

@endsection