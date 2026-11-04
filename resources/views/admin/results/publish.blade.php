<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>View Result</title>
    <style>
        :root {
            --bg: #f4f6f8;
            --card: #ffffff;
            --text: #1f2933;
            --muted: #6b7280;
            --line: #e5e7eb;
            --accent: #1d4ed8;
            --error-bg: #fef2f2;
            --error-text: #b91c1c;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #111827;
                --card: #1f2937;
                --text: #f3f4f6;
                --muted: #9ca3af;
                --line: #374151;
                --accent: #60a5fa;
                --error-bg: #3b1d1d;
                --error-text: #fca5a5;
            }
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 24px 12px;
            background: var(--bg);
            color: var(--text);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        }

        .card {
            max-width: 640px;
            margin: 0 auto 20px;
            padding: 24px;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
        }

        h1 { margin: 0 0 4px; font-size: 22px; }
        h2 { margin: 0 0 4px; font-size: 18px; }
        .muted { color: var(--muted); font-size: 14px; }

        label { display: block; margin: 16px 0 6px; font-weight: 600; font-size: 14px; }

        input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--bg);
            color: var(--text);
            font-size: 16px;
        }

        button, .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 11px 18px;
            border: 0;
            border-radius: 8px;
            background: var(--accent);
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }

        .error {
            margin: 16px 0 0;
            padding: 10px 12px;
            border-radius: 8px;
            background: var(--error-bg);
            color: var(--error-text);
            font-size: 14px;
        }

        .scroll { overflow-x: auto; }

        table { width: 100%; margin-top: 14px; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 8px; border-bottom: 1px solid var(--line); text-align: left; }
        th { color: var(--muted); font-weight: 600; }

        .summary { display: flex; flex-wrap: wrap; gap: 8px 24px; margin-top: 14px; font-size: 15px; }
    </style>
</head>
<body>

@if($results)

    {{-- ================= RESULT ================= --}}

    <div class="card">
        <h1>{{ trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name) }}</h1>
        <div class="muted">Student ID: {{ $student->student_id }}</div>
    </div>

    @foreach($results as $result)
        <div class="card">
            <h2>{{ $result->exam?->exam_name ?? 'Examination' }}</h2>
            <div class="muted">
                {{ $result->academic_year }} &middot;
                {{ $result->class_name }}
                @if($result->section) - {{ $result->section }} @endif
            </div>

            <div class="scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Maximum</th>
                            <th>Obtained</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result->details as $detail)
                            <tr>
                                <td>{{ $detail->subject_name }}</td>
                                <td>{{ $detail->max_marks }}</td>
                                <td>{{ $detail->obtained_marks }}</td>
                                <td>{{ $detail->grade }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="summary">
                <div><strong>Total:</strong> {{ $result->obtained_marks }} / {{ $result->total_marks }}</div>
                <div><strong>Percentage:</strong> {{ number_format((float) $result->percentage, 2) }}%</div>
                <div><strong>Grade:</strong> {{ $result->grade }}</div>
                <div><strong>Result:</strong> {{ ucfirst((string) $result->result_status) }}</div>
            </div>
        </div>
    @endforeach

    <div class="card">
        <a class="btn" href="{{ route('result.public') }}">Check another result</a>
    </div>

@else

    {{-- ================= FORM ================= --}}

    <div class="card">
        <h1>View Result</h1>
        <div class="muted">Enter the details below to see your child's published result.</div>

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('result.public.view') }}" autocomplete="off">
            @csrf

            <label for="student_id">Student ID</label>
            <input id="student_id" name="student_id" type="text" maxlength="50"
                   value="{{ old('student_id') }}" required>

            <label for="mother_name">Mother's Name</label>
            <input id="mother_name" name="mother_name" type="text" maxlength="100" required>

            <label for="captcha">Security question: what is {{ $captchaQuestion }} ?</label>
            <input id="captcha" name="captcha" type="text" inputmode="numeric" maxlength="5" required>

            <button type="submit">View Result</button>
        </form>
    </div>

@endif

</body>
</html>