```blade
@php
    $school = $schoolSetting ?? \App\Models\SchoolSetting::first();

    $logo = $school->logo ?? null;

    if ($logo && !preg_match('~^(https?://|/)~i', $logo)) {
        $logo = asset('storage/' . ltrim($logo, '/'));
    }

    if (!$logo) {
        $logo = asset('images/gurukullogo.png');
    }

    $address = collect([
        $school->address ?? null,
        $school->city ?? null,
        $school->district ?? null,
        $school->state ?? null,
        $school->pincode ?? null,
    ])->filter()->implode(', ');
@endphp

<div class="school-heading">
    <img src="{{ $logo }}" alt="School Logo"
         onerror="this.style.display='none'">

    <div class="school-details">
        <h1>{{ $school->school_name ?? 'School Name' }}</h1>

        @if($address)
            <p>{{ $address }}</p>
        @endif

        <p>
            @if($school->phone ?? false)
                Phone: {{ $school->phone }}
            @endif

            @if(($school->phone ?? false) && ($school->email ?? false))
                |
            @endif

            @if($school->email ?? false)
                Email: {{ $school->email }}
            @endif
        </p>

        @if($school->udise_code ?? false)
            <p>UDISE Code: {{ $school->udise_code }}</p>
        @endif

        <h2>CASTE / CATEGORY STUDENT REPORT</h2>
        <p>Academic Year: {{ $academicYear ?? 'All Years' }}</p>
    </div>
</div>

<div class="filter-summary">
    <strong>Selected Filters:</strong>

    Year: {{ $selectedFilters['academic_year'] ?? 'All' }}
    |
    Class: {{ $selectedFilters['class'] ?? 'All' }}
    |
    Section: {{ $selectedFilters['section'] ?? 'All' }}
    |
    Caste: {{ $selectedFilters['caste'] ?? 'All' }}
</div>

<table class="report-table">
    <thead>
        <tr>
            <th style="width: 7%">Sr. No.</th>
            <th style="width: 29%">Caste / Category</th>
            <th style="width: 22%">Class</th>
            <th style="width: 14%">Boys</th>
            <th style="width: 14%">Girls</th>
            <th style="width: 14%">Total</th>
        </tr>
    </thead>

    <tbody>
        @forelse($report as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row->caste }}</td>
                <td>{{ $row->class }}</td>
                <td>{{ $row->boys }}</td>
                <td>{{ $row->girls }}</td>
                <td>{{ $row->total }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No caste report records found.</td>
            </tr>
        @endforelse

        <tr class="grand-total">
            <td colspan="3">GRAND TOTAL</td>
            <td>{{ $grandBoys }}</td>
            <td>{{ $grandGirls }}</td>
            <td>{{ $grandTotal }}</td>
        </tr>
    </tbody>
</table>

<div class="signature-row">
    <span>Class Teacher</span>
    <span>Principal / Headmaster</span>
</div>
```
