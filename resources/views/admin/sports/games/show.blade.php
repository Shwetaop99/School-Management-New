@extends('layouts.app')

@section('title', 'Sports Event Notice | Admin')
@section('page-title', 'Sports Event Notice')

@section('content')

@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Carbon;

    /*
    |--------------------------------------------------------------------------
    | SCHOOL INFORMATION
    |--------------------------------------------------------------------------
    */

    $school = DB::table('school_settings')->first();

    $schoolName = $school?->school_name ?: 'Gurukul Vidyalaya';

    $schoolLogo = null;

    if (!empty($school?->school_logo)) {
        $logoPath = $school->school_logo;

        if (Str::startsWith($logoPath, ['http://', 'https://'])) {
            $schoolLogo = $logoPath;
        } else {
            $cleanPath = ltrim($logoPath, '/');

            if (Str::startsWith($cleanPath, 'storage/')) {
                $schoolLogo = asset($cleanPath);
            } elseif (file_exists(public_path($cleanPath))) {
                $schoolLogo = asset($cleanPath);
            } elseif (Storage::disk('public')->exists($cleanPath)) {
                $schoolLogo = Storage::disk('public')->url($cleanPath);
            } else {
                $schoolLogo = asset('images/gurukullogo.png');
            }
        }
    } else {
        $schoolLogo = asset('images/gurukullogo.png');
    }

    /*
    |--------------------------------------------------------------------------
    | EVENT INFORMATION
    |--------------------------------------------------------------------------
    */

    $noticeNumber = 'SP-' . str_pad($game->id, 4, '0', STR_PAD_LEFT);

    try {
        $eventDate = !empty($game->event_date)
            ? Carbon::parse($game->event_date)->format('d M Y')
            : 'To be announced';
    } catch (\Throwable $e) {
        $eventDate = $game->event_date ?? 'To be announced';
    }

    try {
        $noticeDate = !empty($game->created_at)
            ? Carbon::parse($game->created_at)->format('d M Y')
            : now()->format('d M Y');
    } catch (\Throwable $e) {
        $noticeDate = now()->format('d M Y');
    }

    try {
        $startTime = !empty($game->start_time)
            ? Carbon::parse($game->start_time)->format('h:i A')
            : null;

        $endTime = !empty($game->end_time)
            ? Carbon::parse($game->end_time)->format('h:i A')
            : null;

        if ($startTime && $endTime) {
            $eventTime = $startTime . ' - ' . $endTime;
        } elseif ($startTime) {
            $eventTime = $startTime;
        } elseif ($endTime) {
            $eventTime = $endTime;
        } else {
            $eventTime = 'To be announced';
        }
    } catch (\Throwable $e) {
        $eventTime = 'To be announced';
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

    $className = trim((string) ($game->class ?? ''));
    $sectionName = trim((string) ($game->section ?? ''));

    if ($className !== '' && $sectionName !== '') {
        $participants = 'Class ' . $className . ' - Section ' . $sectionName;
    } elseif ($className !== '') {
        $participants = 'Class ' . $className;
    } elseif ($sectionName !== '') {
        $participants = 'Section ' . $sectionName;
    } else {
        $participants = 'All Students';
    }

    $academicYear = $game->academic_year
        ?? $game->year
        ?? '—';

    $organizer = $game->organizer
        ?? $game->organized_by
        ?? $game->conducted_by
        ?? 'Sports Department';

    $eventTitle = $game->title
        ?? $game->event_name
        ?? 'Sports Event';

    $description = $game->description ?? null;

    /*
    |--------------------------------------------------------------------------
    | SPORT-SPECIFIC RULES
    |--------------------------------------------------------------------------
    */

    $sportRules = [
        'cricket' => [
            'All participants must report to the ground before the scheduled match time.',
            'Players must wear appropriate sports attire and proper footwear.',
            'The match shall be conducted according to the rules announced by the referee or umpire.',
            'Players must respect the umpire, teammates, opponents, and decisions made during the match.',
            'Unsafe behaviour, arguments, or deliberate damage to equipment will not be tolerated.',
        ],

        'football' => [
            'Players must wear appropriate sports attire and suitable footwear.',
            'The match shall follow the rules explained by the referee before the event.',
            'Dangerous tackles, pushing, and intentional misconduct are prohibited.',
            'Players must respect the referee and maintain sportsmanship throughout the match.',
            'Participants must report on time and follow all safety instructions.',
        ],

        'basketball' => [
            'Participants must wear suitable sports attire and non-marking sports shoes.',
            'Teams must report to the court before the scheduled match time.',
            'The referee’s decisions must be respected by all players.',
            'Fouls, aggressive conduct, and unsportsmanlike behaviour will be addressed according to the rules.',
            'Players must use school equipment responsibly and follow court safety instructions.',
        ],

        'volleyball' => [
            'Players must report to the volleyball court before the scheduled match.',
            'Teams must follow the rotation and scoring rules explained by the referee.',
            'Players must avoid unsafe contact and follow all instructions from the event officials.',
            'Respect for teammates, opponents, and referees is compulsory.',
            'Appropriate sports attire and safe footwear must be worn.',
        ],

        'badminton' => [
            'Participants must wear suitable sports attire and court shoes.',
            'Players must follow the scoring and service rules explained by the officials.',
            'Participants must maintain discipline and respect the opponent and referee.',
            'Rackets, shuttlecocks, and other equipment must be handled carefully.',
            'Players must be present at their scheduled match time.',
        ],

        'athletics' => [
            'Participants must report to the designated track or field area on time.',
            'All athletes must follow the instructions given by the event officials.',
            'The starting signal and designated lanes or event areas must be respected.',
            'Unsafe conduct or interference with another participant is prohibited.',
            'Students must warm up appropriately and follow all safety precautions.',
        ],

        'table tennis' => [
            'Players must follow the match format and scoring rules announced by the officials.',
            'Participants must report to the playing area before their scheduled match.',
            'Players must handle tables, bats, and balls carefully.',
            'Respectful conduct towards opponents and officials is compulsory.',
            'Players must maintain discipline and accept the officials’ decisions.',
        ],

        'chess' => [
            'Participants must report to the designated playing area on time.',
            'Players must follow the tournament format and rules explained by the organizer.',
            'Silence must be maintained in the playing area.',
            'Mobile phones, outside assistance, and unfair practices are prohibited during play.',
            'Players must respect their opponents and the decisions of the tournament officials.',
        ],
    ];

    $normalizedSport = strtolower(trim((string) $sportName));
    $currentRules = null;

    foreach ($sportRules as $sportKey => $rules) {
        if (Str::contains($normalizedSport, $sportKey)) {
            $currentRules = $rules;
            break;
        }
    }

    if (!$currentRules) {
        $currentRules = [
            'All participants must report to the designated venue before the scheduled event time.',
            'Students must wear appropriate sports attire and follow the instructions of the event organizers.',
            'Discipline, teamwork, fair play, and respect for fellow participants are compulsory.',
            'School sports equipment must be handled responsibly.',
            'Participants must follow all safety instructions issued by teachers and event officials.',
        ];
    }
@endphp

<style>
/* =========================================================
   SPORTS EVENT NOTICE
   SCHOOL DIARY DESIGN SYSTEM
   Matching the Scholarship Examination Details page
========================================================= */

.sports-notice-page {
    --sn-ink: #263b35;
    --sn-ink-2: #1d302b;
    --sn-muted: #778078;
    --sn-text: #37443d;
    --sn-line: #e5dfd0;
    --sn-paper: #ffffff;
    --sn-cream: #f8f6ef;
    --sn-green: #3a9d6b;
    --sn-green-dark: #28764e;
    --sn-green-soft: #eaf5ed;
    --sn-amber: #d58a0b;
    --sn-amber-soft: #fff5df;
    --sn-orange: #e4572e;
    --sn-orange-soft: #fff0e9;
    --sn-blue: #2b5d8a;
    --sn-blue-soft: #edf4fa;
    --sn-cyan: #268da0;
    --sn-violet: #7a4fb5;
    --sn-radius: 10px;

    position: relative;
    padding: 26px;
    color: var(--sn-text);
    font-family: "Public Sans", "Segoe UI", Arial, sans-serif;
    font-size: 14px;
    line-height: 1.65;
    background:
        radial-gradient(
            circle at 4% 0%,
            rgba(58, 157, 107, 0.08),
            transparent 28%
        ),
        radial-gradient(
            circle at 100% 12%,
            rgba(213, 138, 11, 0.07),
            transparent 25%
        ),
        var(--sn-cream);
    border-radius: 12px;
}

.sports-notice-page *,
.sports-notice-page *::before,
.sports-notice-page *::after {
    box-sizing: border-box;
}

.sports-notice-page button,
.sports-notice-page a {
    font-family: inherit;
}

.sports-notice-page a {
    text-decoration: none;
}

.sports-notice-page .sn-container {
    width: 100%;
    max-width: 1250px;
    margin: 0 auto;
}

/* =========================================================
   PAGE HEADER
========================================================= */

.sports-notice-page .sn-page-header {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 24px 26px;
    margin-bottom: 22px;
    background: var(--sn-paper);
    border: 1px solid var(--sn-line);
    border-radius: var(--sn-radius);
    box-shadow: 0 5px 18px rgba(38, 59, 53, 0.035);
    overflow: hidden;
}

.sports-notice-page .sn-page-header::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background: var(--sn-orange);
}

.sports-notice-page .sn-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
}

.sports-notice-page .sn-header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    color: var(--sn-green-dark);
    background: var(--sn-green-soft);
    border: 1px solid #d8ebdd;
    border-radius: 10px;
    font-size: 22px;
}

.sports-notice-page .sn-eyebrow {
    margin: 0 0 3px;
    color: var(--sn-green-dark);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.7px;
    text-transform: uppercase;
}

.sports-notice-page .sn-page-title {
    margin: 0;
    color: var(--sn-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(23px, 3vw, 30px);
    font-weight: 700;
    line-height: 1.25;
    letter-spacing: -0.45px;
}

.sports-notice-page .sn-page-subtitle {
    margin: 6px 0 0;
    color: var(--sn-muted);
    font-size: 12px;
}

.sports-notice-page .sn-header-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
}

.sports-notice-page .sn-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 39px;
    padding: 9px 15px;
    border: 1px solid var(--sn-line);
    border-radius: 7px;
    background: #fff;
    color: var(--sn-ink);
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.2s ease;
}

.sports-notice-page .sn-btn:hover {
    transform: translateY(-1px);
    background: var(--sn-cream);
}

.sports-notice-page .sn-btn-primary {
    color: #fff;
    background: var(--sn-green-dark);
    border-color: var(--sn-green-dark);
}

.sports-notice-page .sn-btn-primary:hover {
    color: #fff;
    background: var(--sn-ink-2);
}

.sports-notice-page .sn-btn-orange {
    color: #fff;
    background: var(--sn-orange);
    border-color: var(--sn-orange);
}

.sports-notice-page .sn-btn-orange:hover {
    color: #fff;
    background: #ca4524;
}

/* =========================================================
   PAPER NOTICE BANNER
========================================================= */

.sports-notice-page .sn-notice-banner {
    position: relative;
    padding: 28px;
    margin-bottom: 22px;
    background:
        linear-gradient(
            120deg,
            rgba(58, 157, 107, 0.045),
            transparent 55%
        ),
        var(--sn-paper);
    border: 1px solid var(--sn-line);
    border-radius: var(--sn-radius);
    box-shadow: 0 5px 18px rgba(38, 59, 53, 0.035);
    overflow: hidden;
}

.sports-notice-page .sn-notice-banner::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: radial-gradient(
        circle,
        rgba(213, 138, 11, 0.11),
        transparent 72%
    );
    pointer-events: none;
}

.sports-notice-page .sn-notice-topline {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding-bottom: 17px;
    border-bottom: 1px dashed var(--sn-line);
}

.sports-notice-page .sn-notice-reference {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--sn-muted);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.sports-notice-page .sn-reference-value {
    padding: 5px 10px;
    color: var(--sn-green-dark);
    background: var(--sn-green-soft);
    border: 1px solid #d7eadc;
    border-radius: 5px;
    font-size: 11px;
    font-weight: 800;
}

.sports-notice-page .sn-notice-date {
    color: var(--sn-muted);
    font-size: 12px;
}

.sports-notice-page .sn-notice-heading {
    position: relative;
    z-index: 1;
    margin: 22px 0 13px;
    color: var(--sn-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(27px, 4vw, 38px);
    font-weight: 700;
    line-height: 1.2;
    overflow-wrap: anywhere;
}

.sports-notice-page .sn-notice-description {
    max-width: 850px;
    margin: 0;
    color: var(--sn-muted);
    font-size: 13px;
    line-height: 1.8;
}

.sports-notice-page .sn-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 18px;
}

.sports-notice-page .sn-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 11px;
    border: 1px solid transparent;
    border-radius: 5px;
    font-size: 11px;
    font-weight: 800;
}

.sports-notice-page .sn-badge-green {
    color: var(--sn-green-dark);
    background: var(--sn-green-soft);
    border-color: #d6e9dc;
}

.sports-notice-page .sn-badge-orange {
    color: #bd4929;
    background: var(--sn-orange-soft);
    border-color: #f6d7ca;
}

.sports-notice-page .sn-badge-blue {
    color: var(--sn-blue);
    background: var(--sn-blue-soft);
    border-color: #dce8f3;
}

/* =========================================================
   GENERAL PAPER CARDS
========================================================= */

.sports-notice-page .sn-card {
    margin-bottom: 22px;
    background: var(--sn-paper);
    border: 1px solid var(--sn-line);
    border-radius: var(--sn-radius);
    box-shadow: 0 5px 18px rgba(38, 59, 53, 0.03);
    overflow: hidden;
}

.sports-notice-page .sn-card-header {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 19px 23px;
    border-bottom: 1px dashed var(--sn-line);
}

.sports-notice-page .sn-card-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    color: var(--sn-green-dark);
    background: var(--sn-green-soft);
    border: 1px solid #d8ebdd;
    border-radius: 8px;
    font-size: 16px;
}

.sports-notice-page .sn-card-icon.orange {
    color: #bd4929;
    background: var(--sn-orange-soft);
    border-color: #f6d7ca;
}

.sports-notice-page .sn-card-icon.amber {
    color: #9a6507;
    background: var(--sn-amber-soft);
    border-color: #f1e2bd;
}

.sports-notice-page .sn-card-icon.blue {
    color: var(--sn-blue);
    background: var(--sn-blue-soft);
    border-color: #dce8f3;
}

.sports-notice-page .sn-card-heading {
    margin: 0;
    color: var(--sn-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 20px;
    font-weight: 700;
    line-height: 1.3;
}

.sports-notice-page .sn-card-subtitle {
    margin: 4px 0 0;
    color: var(--sn-muted);
    font-size: 11px;
}

.sports-notice-page .sn-card-body {
    padding: 23px;
}

/* =========================================================
   EVENT INFORMATION GRID
========================================================= */

.sports-notice-page .sn-details-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 13px;
}

.sports-notice-page .sn-detail-item {
    min-width: 0;
    padding: 17px;
    background: #fcfbf7;
    border: 1px dashed #e3dece;
    border-radius: 8px;
    transition: background 0.2s ease, border-color 0.2s ease;
}

.sports-notice-page .sn-detail-item:hover {
    background: #f5f8f1;
    border-color: #c9ddcb;
}

.sports-notice-page .sn-detail-label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    color: var(--sn-muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.85px;
    text-transform: uppercase;
}

.sports-notice-page .sn-detail-label i {
    color: var(--sn-green);
    font-size: 12px;
}

.sports-notice-page .sn-detail-value {
    margin: 0;
    color: var(--sn-ink);
    font-size: 14px;
    font-weight: 750;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.sports-notice-page .sn-detail-hint {
    margin: 5px 0 0;
    color: var(--sn-muted);
    font-size: 10px;
}

/* =========================================================
   SECONDARY INFORMATION
========================================================= */

.sports-notice-page .sn-secondary-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 13px;
}

.sports-notice-page .sn-secondary-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    min-width: 0;
    padding: 16px;
    background: var(--sn-green-soft);
    border: 1px dashed #d2e5d5;
    border-radius: 8px;
}

.sports-notice-page .sn-secondary-item:nth-child(2) {
    background: var(--sn-amber-soft);
    border-color: #efdfb8;
}

.sports-notice-page .sn-secondary-item:nth-child(3) {
    background: var(--sn-blue-soft);
    border-color: #d9e5f0;
}

.sports-notice-page .sn-secondary-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 34px;
    width: 34px;
    height: 34px;
    color: var(--sn-green-dark);
    background: rgba(255, 255, 255, 0.72);
    border-radius: 7px;
}

.sports-notice-page .sn-secondary-item:nth-child(2) .sn-secondary-icon {
    color: #9a6507;
}

.sports-notice-page .sn-secondary-item:nth-child(3) .sn-secondary-icon {
    color: var(--sn-blue);
}

.sports-notice-page .sn-secondary-content {
    min-width: 0;
}

.sports-notice-page .sn-secondary-label {
    display: block;
    margin-bottom: 4px;
    color: var(--sn-muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.65px;
    text-transform: uppercase;
}

.sports-notice-page .sn-secondary-value {
    display: block;
    color: var(--sn-ink);
    font-size: 13px;
    font-weight: 750;
    overflow-wrap: anywhere;
}

/* =========================================================
   DESCRIPTION PANEL
========================================================= */

.sports-notice-page .sn-description-box {
    padding: 19px 21px;
    background: #fcfbf7;
    border-left: 4px solid var(--sn-green);
    border-radius: 0 8px 8px 0;
    color: var(--sn-text);
    font-size: 13px;
    line-height: 1.9;
    overflow-wrap: anywhere;
}

.sports-notice-page .sn-description-box p:last-child {
    margin-bottom: 0;
}

/* =========================================================
   RULES LIST
========================================================= */

.sports-notice-page .sn-rules-list {
    display: grid;
    gap: 11px;
    padding: 0;
    margin: 0;
    list-style: none;
    counter-reset: sn-rule;
}

.sports-notice-page .sn-rules-list li {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 13px;
    padding: 14px 16px;
    background: #fcfbf7;
    border: 1px solid #eee8db;
    border-radius: 8px;
    color: var(--sn-text);
    font-size: 12px;
    line-height: 1.8;
    counter-increment: sn-rule;
}

.sports-notice-page .sn-rules-list li::before {
    content: counter(sn-rule, decimal-leading-zero);
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 32px;
    width: 32px;
    height: 32px;
    color: var(--sn-green-dark);
    background: var(--sn-green-soft);
    border: 1px solid #d7e9db;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 900;
}

.sports-notice-page .sn-rules-list li span {
    padding-top: 4px;
}

/* =========================================================
   IMPORTANT NOTICE
========================================================= */

.sports-notice-page .sn-important-notice {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 21px;
    margin-bottom: 22px;
    background: var(--sn-amber-soft);
    border: 1px solid #f0dfb8;
    border-left: 5px solid var(--sn-amber);
    border-radius: 9px;
}

.sports-notice-page .sn-important-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 39px;
    width: 39px;
    height: 39px;
    color: #95620b;
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid #eeddb5;
    border-radius: 8px;
    font-size: 17px;
}

.sports-notice-page .sn-important-content {
    min-width: 0;
}

.sports-notice-page .sn-important-title {
    margin: 0 0 6px;
    color: #85570a;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 18px;
    font-weight: 700;
}

.sports-notice-page .sn-important-text {
    margin: 0;
    color: #705b35;
    font-size: 12px;
    line-height: 1.8;
}

/* =========================================================
   SCREEN FOOTER
========================================================= */

.sports-notice-page .sn-screen-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 17px 20px;
    background: var(--sn-ink-2);
    border-radius: 9px;
    color: #fff;
}

.sports-notice-page .sn-footer-school {
    font-family: Georgia, "Times New Roman", serif;
    font-size: 16px;
    font-weight: 700;
}

.sports-notice-page .sn-footer-caption {
    margin-top: 3px;
    color: #c6d3c9;
    font-size: 10px;
}

.sports-notice-page .sn-footer-reference {
    color: #d9e5d9;
    font-size: 11px;
    font-weight: 700;
    text-align: right;
}

/* =========================================================
   PRINT NOTICE - HIDDEN ON SCREEN
========================================================= */

.sports-notice-page .print-notice {
    display: none;
}

/* =========================================================
   RESPONSIVE DESIGN
========================================================= */

@media (max-width: 992px) {
    .sports-notice-page {
        padding: 18px;
    }

    .sports-notice-page .sn-details-grid,
    .sports-notice-page .sn-secondary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .sports-notice-page .sn-page-header {
        align-items: flex-start;
    }
}

@media (max-width: 640px) {
    .sports-notice-page {
        padding: 10px;
    }

    .sports-notice-page .sn-page-header {
        flex-direction: column;
        padding: 19px;
    }

    .sports-notice-page .sn-header-left {
        align-items: flex-start;
    }

    .sports-notice-page .sn-header-actions {
        justify-content: flex-start;
        width: 100%;
    }

    .sports-notice-page .sn-btn {
        flex: 1 1 auto;
    }

    .sports-notice-page .sn-notice-banner {
        padding: 20px;
    }

    .sports-notice-page .sn-notice-topline {
        align-items: flex-start;
        flex-direction: column;
    }

    .sports-notice-page .sn-card-header {
        padding: 16px;
    }

    .sports-notice-page .sn-card-body {
        padding: 16px;
    }

    .sports-notice-page .sn-details-grid,
    .sports-notice-page .sn-secondary-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .sports-notice-page .sn-screen-footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .sports-notice-page .sn-footer-reference {
        text-align: left;
    }
}

/* =========================================================
   PRINT STYLES
========================================================= */

@media print {
    @page {
        size: A4 portrait;
        margin: 12mm;
    }

    html,
    body {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
        color: #222 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    body * {
        visibility: hidden !important;
    }

    .sports-notice-page,
    .sports-notice-page .print-notice,
    .sports-notice-page .print-notice * {
        visibility: visible !important;
    }

    .sports-notice-page {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        background: #fff !important;
        border: none !important;
        border-radius: 0 !important;
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 10pt !important;
        line-height: 1.5 !important;
    }

    .sports-notice-page .sn-container,
    .sports-notice-page .sn-page-header,
    .sports-notice-page .sn-notice-banner,
    .sports-notice-page .sn-card,
    .sports-notice-page .sn-important-notice,
    .sports-notice-page .sn-screen-footer {
        display: none !important;
    }

    .sports-notice-page .print-notice {
        display: block !important;
        position: relative !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        color: #222 !important;
        background: #fff !important;
        font-family: Arial, Helvetica, sans-serif !important;
    }

    .sports-notice-page .print-school-header {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 15px !important;
        padding: 0 0 14px !important;
        border-bottom: 2px solid #263b35 !important;
        text-align: center !important;
    }

    .sports-notice-page .print-school-logo {
        display: block !important;
        width: 66px !important;
        height: 66px !important;
        object-fit: contain !important;
    }

    .sports-notice-page .print-school-name {
        margin: 0 !important;
        color: #263b35 !important;
        font-family: Georgia, "Times New Roman", serif !important;
        font-size: 21pt !important;
        font-weight: 700 !important;
        line-height: 1.3 !important;
    }

    .sports-notice-page .print-school-caption {
        margin: 4px 0 0 !important;
        color: #555 !important;
        font-size: 9pt !important;
    }

    .sports-notice-page .print-notice-meta {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 12px !important;
        padding: 12px 0 !important;
        border-bottom: 1px solid #d9d9d9 !important;
        font-size: 9pt !important;
    }

    .sports-notice-page .print-notice-title {
        margin: 18px 0 7px !important;
        color: #263b35 !important;
        font-family: Georgia, "Times New Roman", serif !important;
        font-size: 20pt !important;
        font-weight: 700 !important;
        line-height: 1.3 !important;
        text-align: center !important;
        overflow-wrap: anywhere !important;
    }

    .sports-notice-page .print-notice-subtitle {
        margin: 0 0 18px !important;
        color: #555 !important;
        font-size: 10pt !important;
        text-align: center !important;
    }

    .sports-notice-page .print-section-title {
        margin: 17px 0 9px !important;
        padding-bottom: 6px !important;
        color: #263b35 !important;
        border-bottom: 1px solid #d6d6d6 !important;
        font-family: Georgia, "Times New Roman", serif !important;
        font-size: 12pt !important;
        font-weight: 700 !important;
    }

    .sports-notice-page .print-details-table {
        width: 100% !important;
        border-collapse: collapse !important;
        table-layout: fixed !important;
        font-size: 9.5pt !important;
    }

    .sports-notice-page .print-details-table th,
    .sports-notice-page .print-details-table td {
        padding: 8px 9px !important;
        border: 1px solid #d8d8d8 !important;
        text-align: left !important;
        vertical-align: top !important;
        overflow-wrap: anywhere !important;
    }

    .sports-notice-page .print-details-table th {
        width: 28% !important;
        color: #263b35 !important;
        background: #f2f5f0 !important;
        font-weight: 700 !important;
    }

    .sports-notice-page .print-description {
        margin: 0 !important;
        color: #333 !important;
        font-size: 9.5pt !important;
        line-height: 1.75 !important;
        overflow-wrap: anywhere !important;
    }

    .sports-notice-page .print-rules {
        margin: 0 !important;
        padding-left: 23px !important;
        font-size: 9.5pt !important;
    }

    .sports-notice-page .print-rules li {
        padding: 0 0 6px 3px !important;
        line-height: 1.65 !important;
        break-inside: avoid !important;
    }

    .sports-notice-page .print-important {
        margin-top: 17px !important;
        padding: 12px 14px !important;
        border: 1px solid #d7c99e !important;
        border-left: 4px solid #d58a0b !important;
        background: #fff9e9 !important;
        color: #4e432e !important;
        font-size: 9.5pt !important;
        line-height: 1.65 !important;
    }

    .sports-notice-page .print-important strong {
        display: block !important;
        margin-bottom: 4px !important;
        color: #85570a !important;
    }

    .sports-notice-page .print-signature-area {
        display: flex !important;
        justify-content: space-between !important;
        gap: 30px !important;
        margin-top: 42px !important;
        page-break-inside: avoid !important;
    }

    .sports-notice-page .print-signature {
        width: 45% !important;
        padding-top: 8px !important;
        border-top: 1px solid #777 !important;
        color: #333 !important;
        font-size: 9pt !important;
        text-align: center !important;
    }

    .sports-notice-page .print-footer {
        margin-top: 23px !important;
        padding-top: 9px !important;
        border-top: 1px solid #d8d8d8 !important;
        color: #666 !important;
        font-size: 8pt !important;
        text-align: center !important;
    }

    .sports-notice-page .print-notice p,
    .sports-notice-page .print-notice li,
    .sports-notice-page .print-notice table {
        color: #222 !important;
    }

    .sports-notice-page .print-section,
    .sports-notice-page .print-details-table tr {
        break-inside: avoid !important;
    }
}
</style>

<div class="sports-notice-page">
    <div class="sn-container">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}

        <div class="sn-page-header">
            <div class="sn-header-left">
                <div class="sn-header-icon">
                    <i class="fas fa-futbol"></i>
                </div>

                <div>
                    <p class="sn-eyebrow">School Diary · Sports Department</p>

                    <h1 class="sn-page-title">
                        Sports Event Notice
                    </h1>

                    <p class="sn-page-subtitle">
                        Official event details, participant information and rules.
                    </p>
                </div>
            </div>

            <div class="sn-header-actions">
                <a href="{{ url()->previous() }}" class="sn-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>

                <button
                    type="button"
                    class="sn-btn sn-btn-primary"
                    onclick="window.print()"
                >
                    <i class="fas fa-print"></i>
                    Print Notice
                </button>
            </div>
        </div>

        {{-- =====================================================
             EVENT NOTICE BANNER
        ====================================================== --}}

        <div class="sn-notice-banner">
            <div class="sn-notice-topline">
                <div class="sn-notice-reference">
                    <i class="fas fa-file-alt"></i>
                    NOTICE REFERENCE

                    <span class="sn-reference-value">
                        {{ $noticeNumber }}
                    </span>
                </div>

                <div class="sn-notice-date">
                    <i class="far fa-calendar-alt"></i>
                    Notice Date: {{ $noticeDate }}
                </div>
            </div>

            <h2 class="sn-notice-heading">
                {{ $eventTitle }}
            </h2>

            <p class="sn-notice-description">
                Official sports event notice issued by the Sports Department.
                Students are requested to review the event details and follow
                the instructions provided below.
            </p>

            <div class="sn-badges">
                <span class="sn-badge sn-badge-green">
                    <i class="fas fa-running"></i>
                    {{ $sportName }}
                </span>

                <span class="sn-badge sn-badge-orange">
                    <i class="fas fa-trophy"></i>
                    {{ $eventType }}
                </span>

                <span class="sn-badge sn-badge-blue">
                    <i class="fas fa-calendar-check"></i>
                    {{ $eventDate }}
                </span>
            </div>
        </div>

        {{-- =====================================================
             EVENT DETAILS
        ====================================================== --}}

        <div class="sn-card">
            <div class="sn-card-header">
                <div class="sn-card-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>

                <div>
                    <h3 class="sn-card-heading">
                        Event Information
                    </h3>

                    <p class="sn-card-subtitle">
                        Important information about the scheduled sports event.
                    </p>
                </div>
            </div>

            <div class="sn-card-body">
                <div class="sn-details-grid">

                    <div class="sn-detail-item">
                        <div class="sn-detail-label">
                            <i class="fas fa-futbol"></i>
                            Sport / Game
                        </div>

                        <p class="sn-detail-value">
                            {{ $sportName }}
                        </p>

                        <p class="sn-detail-hint">
                            Sport associated with this event
                        </p>
                    </div>

                    <div class="sn-detail-item">
                        <div class="sn-detail-label">
                            <i class="fas fa-trophy"></i>
                            Event Type
                        </div>

                        <p class="sn-detail-value">
                            {{ $eventType }}
                        </p>

                        <p class="sn-detail-hint">
                            Category of the event
                        </p>
                    </div>

                    <div class="sn-detail-item">
                        <div class="sn-detail-label">
                            <i class="far fa-calendar-alt"></i>
                            Event Date
                        </div>

                        <p class="sn-detail-value">
                            {{ $eventDate }}
                        </p>

                        <p class="sn-detail-hint">
                            Scheduled event date
                        </p>
                    </div>

                    <div class="sn-detail-item">
                        <div class="sn-detail-label">
                            <i class="far fa-clock"></i>
                            Event Time
                        </div>

                        <p class="sn-detail-value">
                            {{ $eventTime }}
                        </p>

                        <p class="sn-detail-hint">
                            Start and end time, if available
                        </p>
                    </div>

                    <div class="sn-detail-item">
                        <div class="sn-detail-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Venue
                        </div>

                        <p class="sn-detail-value">
                            {{ $venue }}
                        </p>

                        <p class="sn-detail-hint">
                            Event location
                        </p>
                    </div>

                    <div class="sn-detail-item">
                        <div class="sn-detail-label">
                            <i class="fas fa-users"></i>
                            Participants
                        </div>

                        <p class="sn-detail-value">
                            {{ $participants }}
                        </p>

                        <p class="sn-detail-hint">
                            Eligible students
                        </p>
                    </div>

                </div>
            </div>
        </div>

        {{-- =====================================================
             ADDITIONAL INFORMATION
        ====================================================== --}}

        <div class="sn-card">
            <div class="sn-card-header">
                <div class="sn-card-icon orange">
                    <i class="fas fa-info-circle"></i>
                </div>

                <div>
                    <h3 class="sn-card-heading">
                        Additional Information
                    </h3>

                    <p class="sn-card-subtitle">
                        Academic year, event organizer and notice details.
                    </p>
                </div>
            </div>

            <div class="sn-card-body">
                <div class="sn-secondary-grid">

                    <div class="sn-secondary-item">
                        <div class="sn-secondary-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>

                        <div class="sn-secondary-content">
                            <span class="sn-secondary-label">
                                Academic Year
                            </span>

                            <span class="sn-secondary-value">
                                {{ $academicYear }}
                            </span>
                        </div>
                    </div>

                    <div class="sn-secondary-item">
                        <div class="sn-secondary-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>

                        <div class="sn-secondary-content">
                            <span class="sn-secondary-label">
                                Event Organizer
                            </span>

                            <span class="sn-secondary-value">
                                {{ $organizer }}
                            </span>
                        </div>
                    </div>

                    <div class="sn-secondary-item">
                        <div class="sn-secondary-icon">
                            <i class="far fa-calendar-check"></i>
                        </div>

                        <div class="sn-secondary-content">
                            <span class="sn-secondary-label">
                                Notice Issued On
                            </span>

                            <span class="sn-secondary-value">
                                {{ $noticeDate }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- =====================================================
             EVENT DESCRIPTION
        ====================================================== --}}

        @if(!empty($description))
            <div class="sn-card">
                <div class="sn-card-header">
                    <div class="sn-card-icon blue">
                        <i class="fas fa-align-left"></i>
                    </div>

                    <div>
                        <h3 class="sn-card-heading">
                            About the Event
                        </h3>

                        <p class="sn-card-subtitle">
                            Description and additional event instructions.
                        </p>
                    </div>
                </div>

                <div class="sn-card-body">
                    <div class="sn-description-box">
                        {!! nl2br(e($description)) !!}
                    </div>
                </div>
            </div>
        @endif

        {{-- =====================================================
             SPORT-SPECIFIC RULES
        ====================================================== --}}

        <div class="sn-card">
            <div class="sn-card-header">
                <div class="sn-card-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>

                <div>
                    <h3 class="sn-card-heading">
                        Rules & Regulations
                    </h3>

                    <p class="sn-card-subtitle">
                        Instructions for {{ $sportName }} participants.
                    </p>
                </div>
            </div>

            <div class="sn-card-body">
                <ol class="sn-rules-list">
                    @foreach($currentRules as $rule)
                        <li>
                            <span>{{ $rule }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>

        {{-- =====================================================
             IMPORTANT NOTICE
        ====================================================== --}}

        <div class="sn-important-notice">
            <div class="sn-important-icon">
                <i class="fas fa-exclamation"></i>
            </div>

            <div class="sn-important-content">
                <h3 class="sn-important-title">
                    Important Notice for Students
                </h3>

                <p class="sn-important-text">
                    All participants must report to the designated venue on time,
                    maintain discipline, follow the instructions of teachers and
                    event officials, and demonstrate fair play. Students should
                    bring the necessary sports equipment and wear suitable sports
                    attire. Any changes to the event schedule will be communicated
                    by the Sports Department.
                </p>
            </div>
        </div>

        {{-- =====================================================
             SCREEN FOOTER
        ====================================================== --}}

        <div class="sn-screen-footer">
            <div>
                <div class="sn-footer-school">
                    {{ $schoolName }}
                </div>

                <div class="sn-footer-caption">
                    Sports Department · Promoting discipline, teamwork and fair play
                </div>
            </div>

            <div class="sn-footer-reference">
                Notice No. {{ $noticeNumber }}
            </div>
        </div>

    </div>

    {{-- =====================================================
         PRINT-ONLY SPORTS NOTICE
    ====================================================== --}}

    <div class="print-notice">

        {{-- School Header --}}

        <div class="print-school-header">
            @if(!empty($schoolLogo))
                <img
                    src="{{ $schoolLogo }}"
                    alt="School Logo"
                    class="print-school-logo"
                >
            @endif

            <div>
                <h1 class="print-school-name">
                    {{ $schoolName }}
                </h1>

                <p class="print-school-caption">
                    Official Sports Department Notice
                </p>
            </div>
        </div>

        {{-- Notice Reference --}}

        <div class="print-notice-meta">
            <div>
                <strong>Notice No.:</strong>
                {{ $noticeNumber }}
            </div>

            <div>
                <strong>Notice Date:</strong>
                {{ $noticeDate }}
            </div>
        </div>

        {{-- Event Title --}}

        <h2 class="print-notice-title">
            {{ $eventTitle }}
        </h2>

        <p class="print-notice-subtitle">
            {{ $sportName }} · {{ $eventType }}
        </p>

        {{-- Event Details Table --}}

        <div class="print-section">
            <h3 class="print-section-title">
                Event Information
            </h3>

            <table class="print-details-table">
                <tbody>
                    <tr>
                        <th>Sport / Game</th>
                        <td>{{ $sportName }}</td>
                    </tr>

                    <tr>
                        <th>Event Type</th>
                        <td>{{ $eventType }}</td>
                    </tr>

                    <tr>
                        <th>Event Date</th>
                        <td>{{ $eventDate }}</td>
                    </tr>

                    <tr>
                        <th>Event Time</th>
                        <td>{{ $eventTime }}</td>
                    </tr>

                    <tr>
                        <th>Venue</th>
                        <td>{{ $venue }}</td>
                    </tr>

                    <tr>
                        <th>Participants</th>
                        <td>{{ $participants }}</td>
                    </tr>

                    <tr>
                        <th>Academic Year</th>
                        <td>{{ $academicYear }}</td>
                    </tr>

                    <tr>
                        <th>Organizer</th>
                        <td>{{ $organizer }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Optional Description --}}

        @if(!empty($description))
            <div class="print-section">
                <h3 class="print-section-title">
                    About the Event
                </h3>

                <p class="print-description">
                    {!! nl2br(e($description)) !!}
                </p>
            </div>
        @endif

        {{-- Rules --}}

        <div class="print-section">
            <h3 class="print-section-title">
                Rules & Regulations
            </h3>

            <ol class="print-rules">
                @foreach($currentRules as $rule)
                    <li>{{ $rule }}</li>
                @endforeach
            </ol>
        </div>

        {{-- Important Notice --}}

        <div class="print-important">
            <strong>Important Notice for Students</strong>

            All participants must report to the designated venue on time,
            maintain discipline, follow the instructions of teachers and event
            officials, and demonstrate fair play. Students should bring the
            necessary sports equipment and wear suitable sports attire.
            Any changes to the event schedule will be communicated by the
            Sports Department.
        </div>

        {{-- Signatures --}}

        <div class="print-signature-area">
            <div class="print-signature">
                Sports Teacher / Coordinator
            </div>

            <div class="print-signature">
                Principal
            </div>
        </div>

        {{-- Print Footer --}}

        <div class="print-footer">
            {{ $schoolName }} · Sports Department · Notice No. {{ $noticeNumber }}
        </div>

    </div>
</div>

@endsection
