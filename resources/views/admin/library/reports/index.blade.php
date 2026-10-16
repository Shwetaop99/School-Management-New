@extends('layouts.app')

@section('title', 'Library Reports')
@section('page-title', 'Library Reports')

@section('content')

<style>
    .lr{max-width:1500px;margin:0 auto}
    .paper{background:#fff;border:1px solid var(--line);border-radius:10px}

    /* ---------- Header ---------- */
    .lr-head{display:flex;justify-content:space-between;align-items:flex-end;gap:16px;margin-bottom:26px;flex-wrap:wrap}
    .lr-head h1{font-family:var(--serif);font-size:26px;font-weight:700;color:var(--ink);margin-bottom:4px}
    .lr-head p{color:var(--muted);font-size:14px}
    .lr-tools{display:flex;gap:10px;flex-wrap:wrap}
    .lr-tool{--c:#2b5d8a;display:inline-flex;align-items:center;gap:8px;height:40px;padding:0 16px;border:1px solid var(--line);border-radius:8px;background:#fff;color:var(--text);font:inherit;font-size:13.5px;font-weight:500;cursor:pointer;transition:border-color .15s,background .15s,color .15s}
    .lr-tool i{color:var(--c)}
    .lr-tool:hover{border-color:var(--c);background:color-mix(in srgb,var(--c) 8%,#fff);color:var(--ink)}
    .lr-tool.pdf{--c:#e4572e}.lr-tool.xls{--c:#3a9d6b}.lr-tool.prt{--c:#1d3a33}

    /* ---------- KPI folders ---------- */
    .lr-kpis{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px;margin-bottom:22px}
    .kpi{transition:transform .22s ease,box-shadow .22s ease;position:relative;padding:22px 22px 20px;margin-top:10px;border-top:4px solid var(--c);display:flex;align-items:center;justify-content:space-between;gap:12px}
    .kpi:hover{transform:translateY(-8px);box-shadow:0 16px 28px rgba(29,58,51,.16)}
    .kpi-tab{position:absolute;top:-24px;left:16px;padding:3px 12px;background:var(--c);color:#fff;font-size:12px;font-weight:600;border-radius:7px 7px 0 0}
    .kpi-value{font-family:var(--serif);font-size:34px;font-weight:700;line-height:1;color:var(--ink)}
    .kpi-note{margin-top:6px;font-size:12.5px;color:var(--muted)}
    .kpi-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:21px;flex-shrink:0;background:color-mix(in srgb,var(--c) 14%,#fff);color:var(--c)}
    .c-blue{--c:#2b5d8a}.c-amber{--c:#d58a0b}.c-green{--c:#3a9d6b}.c-red{--c:#e4572e}.c-violet{--c:#7a4fb5}

    /* ---------- Panels ---------- */
    .panel{overflow:hidden;margin-bottom:22px}
    .panel-head{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:15px 22px;border-bottom:1px dashed #d8d1bf;flex-wrap:wrap}
    .panel-head h3{display:flex;align-items:center;gap:10px;font-family:var(--serif);font-size:17px;font-weight:700;color:var(--ink);margin:0}
    .panel-head h3 i{font-size:16px;color:var(--accent)}
    .panel-head small{display:block;margin-top:2px;font-family:'Public Sans',sans-serif;font-size:12.5px;font-weight:400;color:var(--muted)}
    .count-pill{padding:5px 12px;border-radius:999px;background:var(--accent-soft);color:var(--accent);font-size:12px;font-weight:600;white-space:nowrap}

    /* ---------- Filters ---------- */
    .lr-filters{display:grid;grid-template-columns:repeat(2,minmax(0,1fr)) minmax(0,1.5fr) minmax(0,1fr) minmax(0,1fr) auto;gap:16px;align-items:end;padding:20px 22px 22px;background:#fffdf8}
    .f-group label{display:block;margin-bottom:6px;font-size:13px;font-weight:600;color:var(--ink)}
    .f-input{width:100%;height:44px;padding:0 12px;font:inherit;font-size:13.5px;color:var(--text);background:#fff;border:1px solid var(--line);border-radius:8px;outline:none;transition:border-color .15s,box-shadow .15s}
    .f-input::placeholder{color:#a3a89f}
    .f-input:focus{border-color:var(--ink);box-shadow:0 0 0 3px rgba(242,182,50,.4)}
    .f-actions{display:flex;gap:8px}
    .f-btn{height:44px;padding:0 18px;display:inline-flex;align-items:center;justify-content:center;gap:8px;border:none;border-radius:8px;font:inherit;font-size:13.5px;font-weight:600;cursor:pointer;white-space:nowrap;text-decoration:none;transition:background .15s,transform .15s}
    .f-apply{background:var(--ink);color:#fff}
    .f-apply:hover{background:var(--ink-2);color:#fff;transform:translateY(-1px)}
    .f-apply i{color:var(--chalk)}
    .f-reset{background:#fff;border:1px solid var(--line);color:var(--muted)}
    .f-reset:hover{border-color:var(--ink);color:var(--ink)}

    /* ---------- Tables ---------- */
    .tbl-wrap{overflow-x:auto}
    .tbl{width:100%;min-width:760px;border-collapse:collapse}
    .tbl th{padding:12px 22px;background:#faf6ea;border-bottom:1px solid var(--line);color:var(--muted);font-size:12px;font-weight:600;text-align:left;white-space:nowrap}
    .tbl td{padding:14px 22px;border-bottom:1px solid #f0ebdc;color:var(--text);font-size:13px;vertical-align:middle}
    .tbl tbody tr:hover td{background:#fffcf3}
    .tbl tbody tr:last-child td{border-bottom:none}
    .tbl tfoot td{padding:14px 22px;background:#faf6ea;border-top:2px solid var(--line);font-weight:700;color:var(--ink)}
    .t-name{font-weight:600;color:var(--ink)}
    .t-sub{margin-top:2px;font-size:12px;color:var(--muted)}
    .num{font-family:var(--serif);font-weight:700;color:var(--ink)}

    .pill{display:inline-flex;align-items:center;justify-content:center;min-width:40px;padding:4px 11px;border-radius:999px;font-size:12px;font-weight:600;white-space:nowrap}
    .pill.green{background:#e3f4ea;color:#17703b}
    .pill.blue{background:var(--accent-soft);color:var(--accent)}
    .pill.red{background:#fde8e4;color:#b3321d}
    .badge-st{display:inline-flex;align-items:center;gap:6px;padding:4px 11px;border-radius:999px;font-size:12px;font-weight:600}
    .badge-st::before{content:"";width:6px;height:6px;border-radius:50%;background:currentColor}
    .st-returned{background:#e3f4ea;color:#17703b}
    .st-issued{background:var(--accent-soft);color:var(--accent)}
    .st-overdue{background:#fde8e4;color:#b3321d}
    .fine{font-weight:700;color:#b3321d}
    .muted{color:var(--muted)}

    .empty{padding:56px 20px;text-align:center}
    .empty i{display:block;font-size:34px;color:#a3b0c2;margin-bottom:10px}
    .empty strong{display:block;font-family:var(--serif);font-size:17px;color:var(--ink);margin-bottom:4px}
    .empty span{font-size:13px;color:var(--muted)}
    .lr-pages{padding:14px 22px;border-top:1px dashed #d8d1bf}

    @media (max-width:1300px){.lr-filters{grid-template-columns:repeat(3,minmax(0,1fr))}.f-actions{grid-column:1/-1;justify-content:flex-end}}
    @media (max-width:1100px){.lr-kpis{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media (max-width:650px){
        .lr-kpis,.lr-filters{grid-template-columns:1fr}
        .kpi{margin-top:18px}
        .f-actions .f-btn{flex:1}
        .lr-tools{width:100%}.lr-tool{flex:1;justify-content:center}
    }

    @media print{
        .no-print,.sidebar,.top-header,.app-footer,.sidebar-overlay,.pagination{display:none!important}
        .main-area{margin:0!important;width:100%!important}
        .main-content{padding:0!important;background:#fff!important}
        body{background:#fff!important}
        .paper,.kpi{box-shadow:none!important;transform:none!important;break-inside:avoid}
        .tbl{min-width:0}
    }
</style>

@php
    $fmt = function ($date) {
        return $date ? \Carbon\Carbon::parse($date)->format('d M Y') : '—';
    };
@endphp

<div class="lr">

    {{-- Header --}}
    <div class="lr-head">
        <div>
            <h1>Library reports</h1>
            <p>Overview of books, transactions, returns and fines.</p>
        </div>

        <div class="lr-tools no-print">
            <a href="{{ route('admin.library.reports.pdf', request()->query()) }}" class="lr-tool pdf">
                <i class="bi bi-file-earmark-pdf-fill"></i> PDF
            </a>
            <a href="{{ route('admin.library.reports.excel', request()->query()) }}" class="lr-tool xls">
                <i class="bi bi-file-earmark-excel-fill"></i> Excel
            </a>
            <button type="button" onclick="window.print()" class="lr-tool prt">
                <i class="bi bi-printer-fill"></i> Print
            </button>
        </div>
    </div>

    {{-- Summary cards --}}
    <div class="lr-kpis">
        <div class="paper kpi c-blue">
            <span class="kpi-tab">Books</span>
            <div>
                <div class="kpi-value">{{ number_format($totalBooks) }}</div>
                <div class="kpi-note">Total books</div>
            </div>
            <div class="kpi-icon"><i class="bi bi-book-fill"></i></div>
        </div>

        <div class="paper kpi c-violet">
            <span class="kpi-tab">Stock</span>
            <div>
                <div class="kpi-value">{{ number_format($totalCopies) }}</div>
                <div class="kpi-note">Total book stock</div>
            </div>
            <div class="kpi-icon"><i class="bi bi-stack"></i></div>
        </div>

        <div class="paper kpi c-green">
            <span class="kpi-tab">Available</span>
            <div>
                <div class="kpi-value">{{ number_format($availableCopies) }}</div>
                <div class="kpi-note">Available copies</div>
            </div>
            <div class="kpi-icon"><i class="bi bi-check2-circle"></i></div>
        </div>

        <div class="paper kpi c-amber">
            <span class="kpi-tab">Issued</span>
            <div>
                <div class="kpi-value">{{ number_format($issuedCopies) }}</div>
                <div class="kpi-note">Currently issued</div>
            </div>
            <div class="kpi-icon"><i class="bi bi-journal-arrow-up"></i></div>
        </div>
    </div>

    {{-- Filters (below the cards) --}}
    <section class="paper panel no-print">
        <div class="panel-head">
            <h3>
                <i class="bi bi-funnel-fill"></i>
                <span>Report filters<small>Filter library transactions by date, book, student or status</small></span>
            </h3>
        </div>

        <form method="GET" action="{{ route('admin.library.reports.index') }}" class="lr-filters">
            <div class="f-group">
                <label for="from_date">From date</label>
                <input type="date" id="from_date" name="from_date" class="f-input" value="{{ $fromDate }}">
            </div>

            <div class="f-group">
                <label for="to_date">To date</label>
                <input type="date" id="to_date" name="to_date" class="f-input" value="{{ $toDate }}">
            </div>

            <div class="f-group">
                <label for="book_id">Book</label>
                <select id="book_id" name="book_id" class="f-input">
                    <option value="">All books</option>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}" @selected($bookId == $book->id)>{{ $book->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="f-group">
                <label for="student_id">Student ID</label>
                <input type="text" id="student_id" name="student_id" class="f-input" placeholder="Student ID" value="{{ $studentId }}">
            </div>

            <div class="f-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="f-input">
                    <option value="">All status</option>
                    @foreach (['Issued','Returned','Overdue'] as $option)
                        <option value="{{ $option }}" @selected($status === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="f-actions">
                <button type="submit" class="f-btn f-apply"><i class="bi bi-search"></i> Apply</button>
                <a href="{{ route('admin.library.reports.index') }}" class="f-btn f-reset" title="Reset filters"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
            </div>
        </form>
    </section>

    {{-- Book stock --}}
    <section class="paper panel">
        <div class="panel-head">
            <h3>
                <i class="bi bi-boxes"></i>
                <span>Book stock<small>Individual stock details for every book</small></span>
            </h3>
        </div>

        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Author</th>
                        <th>Total copies</th>
                        <th>Available</th>
                        <th>Issued</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($bookStock as $book)
                        <tr>
                            <td>
                                <div class="t-name">{{ $book->title }}</div>
                                @if ($book->isbn)
                                    <div class="t-sub">ISBN: {{ $book->isbn }}</div>
                                @endif
                            </td>
                            <td>{{ $book->author }}</td>
                            <td><span class="num">{{ $book->quantity }}</span></td>
                            <td><span class="pill green">{{ $book->available_quantity }}</span></td>
                            <td><span class="pill blue">{{ $book->quantity - $book->available_quantity }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty">
                                    <i class="bi bi-journal-x"></i>
                                    <strong>No books found</strong>
                                    <span>Add books to the library to see stock here.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if ($bookStock->count())
                    <tfoot>
                        <tr>
                            <td colspan="2">Total book stock</td>
                            <td><span class="num">{{ $bookStock->sum('quantity') }}</span></td>
                            <td><span class="pill green">{{ $bookStock->sum('available_quantity') }}</span></td>
                            <td><span class="pill blue">{{ $bookStock->sum('quantity') - $bookStock->sum('available_quantity') }}</span></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </section>

    {{-- Transactions --}}
    <section class="paper panel">
        <div class="panel-head">
            <h3>
                <i class="bi bi-arrow-left-right"></i>
                <span>Library transactions<small>Complete issue and return history</small></span>
            </h3>
            <span class="count-pill">{{ $transactions->total() }} records</span>
        </div>

        @if ($transactions->count())
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Student ID</th>
                            <th>Issue date</th>
                            <th>Due date</th>
                            <th>Return date</th>
                            <th>Status</th>
                            <th>Fine</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($transactions as $transaction)
                            @php
                                $overdue = $transaction->status === 'Overdue'
                                    || (! $transaction->return_date
                                        && $transaction->due_date
                                        && \Carbon\Carbon::parse($transaction->due_date)->isPast());

                                $state = $transaction->status === 'Returned' ? 'returned' : ($overdue ? 'overdue' : 'issued');
                            @endphp

                            <tr>
                                <td>
                                    <div class="t-name">{{ $transaction->book->title ?? 'Deleted book' }}</div>
                                    @if ($transaction->book)
                                        <div class="t-sub">{{ $transaction->book->author }}</div>
                                    @endif
                                </td>
                                <td>{{ $transaction->student_id }}</td>
                                <td>{{ $fmt($transaction->issue_date) }}</td>
                                <td>{{ $fmt($transaction->due_date) }}</td>
                                <td>{{ $fmt($transaction->return_date) }}</td>
                                <td><span class="badge-st st-{{ $state }}">{{ ucfirst($state) }}</span></td>
                                <td>
                                    @if ($transaction->fine > 0)
                                        <span class="fine">₹{{ number_format($transaction->fine, 2) }}</span>
                                    @else
                                        <span class="muted">₹0.00</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                <div class="lr-pages no-print">{{ $transactions->links() }}</div>
            @endif
        @else
            <div class="empty">
                <i class="bi bi-bar-chart-line"></i>
                <strong>No transactions found</strong>
                <span>Try changing your report filters.</span>
            </div>
        @endif
    </section>

</div>

@endsection