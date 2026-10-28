@extends('layouts.app')

@section('title', 'Subjects & Marks')

@section('content')
<div class="subjects-page">
    <header class="sp-header">
        <div class="sp-header-left">
            <a href="{{ route('admin.exams.show', $exam) }}" class="sp-back" aria-label="Back to exam"><i class="bi bi-arrow-left"></i></a>
            <div class="sp-title-icon"><i class="bi bi-bookmarks"></i></div>
            <div>
                <div class="sp-title-line">
                    <h1>Subjects &amp; Marks</h1>
                    <span class="sp-badge">Exam Setup</span>
                </div>
                <p class="sp-muted">Configure exam marks separately for each class.</p>
            </div>
        </div>
        <a href="{{ route('admin.exams.show', $exam) }}" class="sp-btn sp-btn-light"><i class="bi bi-arrow-left"></i> Back to Exam</a>
    </header>

    <div class="sp-breadcrumb">
        <i class="bi bi-journal-text"></i>
        <span>{{ $exam->exam_name }}</span><span class="sp-separator">/</span>
        <span>{{ $exam->academic_year }}</span>
        @if(!empty($exam->exam_type))
            <span class="sp-separator">/</span><span>{{ $exam->exam_type }}</span>
        @endif
    </div>

    @if(session('success'))
        <div class="sp-alert sp-success" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <div><strong>Configuration Saved</strong><p>{{ session('success') }}</p></div>
            <button type="button" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x-lg"></i></button>
        </div>
    @endif

    @if($errors->any())
        <div class="sp-alert sp-danger" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div><strong>Please correct the following errors</strong>
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        </div>
    @endif

    <div class="sp-info">
        <i class="bi bi-info-circle-fill"></i>
        <div><strong>Subjects come from Class Management</strong>
            <p>Use Quick Setup to set marks and duration for one class at a time. You can edit maximum marks, passing marks, and duration directly for every subject. Quick Setup updates only checked subjects in the class where you click Apply.</p>
        </div>
    </div>

    @if($examClasses->isEmpty())
        <section class="sp-empty">
            <i class="bi bi-mortarboard"></i>
            <h2>No Classes Selected</h2>
            <p>Select at least one class before configuring this examination.</p>
            <a href="{{ route('admin.exam-classes.index', $exam) }}" class="sp-btn sp-btn-primary"><i class="bi bi-mortarboard"></i> Select Classes</a>
        </section>
    @else
        @php
            $totalClasses = $examClasses->count();
            $totalAvailableSubjects = 0;
            foreach ($examClasses as $summaryExamClass) {
                if ($summaryExamClass->schoolClass) {
                    $totalAvailableSubjects += ($summaryExamClass->schoolClass->subjects ?? collect())->count();
                }
            }
        @endphp

        <div class="sp-summary">
            <div class="sp-summary-card"><span class="sp-summary-icon"><i class="bi bi-mortarboard"></i></span><div><small>Selected Classes</small><strong>{{ $totalClasses }}</strong><span>Classes included in this exam</span></div></div>
            <div class="sp-summary-card"><span class="sp-summary-icon sp-blue"><i class="bi bi-book"></i></span><div><small>Available Subjects</small><strong>{{ $totalAvailableSubjects }}</strong><span>Fetched from Class Management</span></div></div>
            <div class="sp-summary-card"><span class="sp-summary-icon sp-amber"><i class="bi bi-sliders"></i></span><div><small>Configuration</small><strong>Exam</strong><span>Settings are exam-specific</span></div></div>
        </div>

        <form method="POST" action="{{ route('admin.exam-subjects.store', $exam) }}" id="examSubjectsForm">
            @csrf

            <section class="sp-quick-card">
                <div class="sp-section-heading">
                    <span class="sp-section-icon"><i class="bi bi-lightning-charge-fill"></i></span>
                    <div><h2>Quick Setup</h2><p>Enter the settings once, then apply them only to the class you choose.</p></div>
                </div>

                <div class="sp-quick-grid sp-quick-grid-four">
                    <div class="sp-field">
                        <label for="quickSetupClass">Apply to Class</label>
                        <select id="quickSetupClass" required>
                            @foreach($examClasses as $quickExamClass)
                                @if($quickExamClass->schoolClass)
                                    <option value="{{ $quickExamClass->schoolClass->id }}">{{ $quickExamClass->schoolClass->class_name }}@if($quickExamClass->schoolClass->section) — Section {{ $quickExamClass->schoolClass->section }}@endif</option>
                                @endif
                            @endforeach
                        </select>
                        <small>Only checked subjects in this class are changed.</small>
                    </div>
                    <div class="sp-field">
                        <label for="bulkMaximumMarks">Maximum Marks</label>
                        <input type="number" id="bulkMaximumMarks" min="1" max="1000" step="1" value="50" required>
                    </div>
                    <div class="sp-field">
                        <label for="bulkPassingPercent">Passing Marks</label>
                        <input type="number" id="bulkPassingPercent" min="0" max="1000" step="1" value="17" required>
                        <small>Must not exceed maximum marks.</small>
                    </div>
                    <div class="sp-field">
                        <label for="bulkDuration">Duration (minutes)</label>
                        <input type="number" id="bulkDuration" min="1" max="600" step="1" value="60" required>
                    </div>
                </div>
                <div class="sp-quick-actions">
                    <span id="bulkSelectedCount">0 subjects selected</span>
                    <div>
                        <button type="button" class="sp-btn sp-btn-light" id="selectAllClassSubjects"><i class="bi bi-check2-all"></i> Select All in Class</button>
                        <button type="button" class="sp-btn sp-btn-primary" id="applyQuickSetup"><i class="bi bi-sliders"></i> Apply Quick Setup</button>
                    </div>
                </div>
                <p class="sp-note"><i class="bi bi-info-circle"></i> Choose a class, check the subjects you want to update, then apply. Subjects in other classes are never changed by Quick Setup.</p>
            </section>

            <section class="sp-register-card">
                <div class="sp-register-heading">
                    <div>
                        <h2><i class="bi bi-list-check"></i> Subject Marks Register</h2>
                        <p>All selected classes are listed in one table. Each row keeps its own class-specific marks.</p>
                    </div>
                    <span class="sp-register-count">{{ $totalAvailableSubjects }} subjects</span>
                </div>
                <div class="sp-register-table-wrap">
                    <table class="sp-register-table">
                        <thead>
                            <tr>
                                <th class="sp-number-col">#</th>
                                <th class="sp-include-col">Include</th>
                                <th>Class / Section</th>
                                <th>Subject</th>
                                <th>Subject Code</th>
                                <th>Maximum Marks</th>
                                <th>Passing Marks</th>
                                <th>Duration (min)</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php $rowNumber = 0; @endphp
                        @foreach($examClasses as $examClass)
                            @php
                                $class = $examClass->schoolClass;
                                $classSubjects = $class?->subjects ?? collect();
                            @endphp
                            @if($class && $classSubjects->isNotEmpty())
                                @foreach($classSubjects as $subject)
                                    @php
                                        $rowNumber++;
                                        $key = $class->id . '_' . $subject->id;
                                        $existing = $examSubjects[$key] ?? null;
                                        $statusValue = old('subjects.' . $key . '.status', $existing ? (int) $existing->status : 1);
                                        $maximumValue = old('subjects.' . $key . '.maximum_marks', $existing?->maximum_marks ?? 50);
                                        $passingValue = old('subjects.' . $key . '.passing_marks', $existing?->passing_marks ?? 17);
                                        $durationValue = old('subjects.' . $key . '.duration_minutes', $existing?->duration_minutes ?? 60);
                                    @endphp
                                    <tr class="subject-row" data-class-id="{{ $class->id }}" data-subject-name="{{ mb_strtolower($subject->subject_name) }}">
                                        <td class="sp-row-number">{{ $rowNumber }}</td>
                                        <td>
                                            <input type="hidden" name="subjects[{{ $key }}][class_id]" value="{{ $class->id }}">
                                            <input type="hidden" name="subjects[{{ $key }}][subject_id]" value="{{ $subject->id }}">
                                            <input type="hidden" name="subjects[{{ $key }}][status]" value="0">
                                            <label class="sp-checkbox" aria-label="Include {{ $subject->subject_name }} for {{ $class->class_name }}">
                                                <input class="subject-checkbox" type="checkbox" name="subjects[{{ $key }}][status]" value="1" data-class-id="{{ $class->id }}" {{ (string) $statusValue === '1' ? 'checked' : '' }}>
                                                <span></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="sp-class-cell">
                                                <strong>{{ $class->class_name }}</strong>
                                                @if($class->section)<small>Section {{ $class->section }}</small>@endif
                                            </div>
                                        </td>
                                        <td><div class="sp-subject-name"><span class="sp-book"><i class="bi bi-book"></i></span><div><strong>{{ $subject->subject_name }}</strong><small>Exam subject</small></div></div></td>
                                        <td>@if($subject->subject_code)<span class="sp-code">{{ $subject->subject_code }}</span>@else<span class="sp-dash">—</span>@endif</td>
                                        <td><label class="sp-manual-field"><span class="sp-sr-only">Maximum marks for {{ $subject->subject_name }} in {{ $class->class_name }}</span><input type="number" class="maximum-marks" name="subjects[{{ $key }}][maximum_marks]" min="1" max="1000" step="1" value="{{ $maximumValue }}" required></label></td>
                                        <td><label class="sp-manual-field"><span class="sp-sr-only">Passing marks for {{ $subject->subject_name }} in {{ $class->class_name }}</span><input type="number" class="passing-marks" name="subjects[{{ $key }}][passing_marks]" min="0" max="1000" step="1" value="{{ $passingValue }}" required></label></td>
                                        <td><label class="sp-manual-field"><span class="sp-sr-only">Duration for {{ $subject->subject_name }} in {{ $class->class_name }}</span><input type="number" class="duration-input" name="subjects[{{ $key }}][duration_minutes]" min="1" max="600" step="1" value="{{ $durationValue }}" required></label></td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                        @if($rowNumber === 0)
                            <tr><td colspan="8" class="sp-register-empty"><i class="bi bi-book"></i><strong>No subjects available</strong><span>Add active subjects to the selected classes in Class Management.</span></td></tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="sp-register-footer"><i class="bi bi-info-circle"></i> Marks are saved separately for each class and subject. Check a subject to include it in this exam.</div>
            </section>

            <div class="sp-save-panel">
                <div class="sp-save-info"><span><i class="bi bi-shield-check"></i></span><div><strong>Exam-specific configuration</strong><p>These settings apply only to <b>{{ $exam->exam_name }}</b>.</p></div></div>
                <div class="sp-save-actions">
                    <a href="{{ route('admin.exams.show', $exam) }}" class="sp-btn sp-btn-light"><i class="bi bi-x-lg"></i> Cancel</a>
                    <button type="submit" class="sp-btn sp-btn-primary" id="saveSubjectsButton"><i class="bi bi-check-circle"></i> Save Subjects &amp; Marks</button>
                </div>
            </div>
        </form>
    @endif

    <footer class="sp-footer"><i class="bi bi-journal-check"></i> School Diary <span>·</span> Examination Management</footer>
</div>

<style>
.subjects-page{--ink:#263b35;--forest:#344f43;--muted:#7b857b;--line:#e5e1d5;--paper:#fffefa;max-width:1650px;margin:auto;padding:24px;color:#39483e;font-family:"Public Sans",Arial,sans-serif}
.subjects-page *{box-sizing:border-box}.subjects-page h1,.subjects-page h2,.subjects-page h3,.subjects-page p{margin-top:0}.subjects-page a{text-decoration:none}.subjects-page button,.subjects-page input,.subjects-page select{font:inherit}
.sp-header,.sp-header-left,.sp-title-line,.sp-class-info,.sp-class-title,.sp-class-actions,.sp-quick-foot,.sp-save-panel,.sp-save-info,.sp-save-actions{display:flex;align-items:center;gap:12px}.sp-header{justify-content:space-between;flex-wrap:wrap;margin-bottom:16px}.sp-header-left{min-width:0}.sp-back,.sp-title-icon,.sp-class-icon,.sp-section-icon{display:flex;align-items:center;justify-content:center;flex-shrink:0;border-radius:12px}.sp-back{width:36px;height:36px;border:1px solid var(--line);color:var(--ink);background:var(--paper)}.sp-title-icon{width:49px;height:49px;background:#e9eee5;color:var(--forest);font-size:22px}.sp-title-line{gap:10px;flex-wrap:wrap}.sp-title-line h1{margin:0;color:var(--ink);font:700 clamp(24px,3vw,31px) Georgia,serif}.sp-badge,.sp-section-badge{padding:5px 9px;border:1px solid #d9e3d7;border-radius:20px;background:#edf2e9;color:var(--forest);font-size:10px;font-weight:700}.sp-muted{margin:5px 0 0;color:var(--muted);font-size:12px}.sp-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:39px;padding:9px 13px;border:1px solid transparent;border-radius:9px;font-size:11px;font-weight:700;cursor:pointer;transition:.2s}.sp-btn-light{border-color:#d8d8ca;background:var(--paper);color:var(--ink)}.sp-btn-light:hover{background:#f0f1e8}.sp-btn-primary{border-color:#20352c;background:var(--forest);color:#fffefa}.sp-btn-primary:hover{background:#20352c;color:#fffefa}.sp-breadcrumb{display:flex;align-items:center;flex-wrap:wrap;gap:9px;margin-bottom:22px;padding:11px 14px;border:1px solid var(--line);border-radius:10px;background:#fffefa;color:var(--muted);font-size:11px}.sp-breadcrumb i{color:#527f70}.sp-separator{color:#b0b4a7}
.sp-alert{display:flex;align-items:flex-start;gap:12px;position:relative;margin-bottom:18px;padding:15px;border:1px solid;border-radius:12px}.sp-alert>i{font-size:19px}.sp-alert strong{font-size:12px}.sp-alert p,.sp-alert ul{margin:5px 0 0;font-size:12px;line-height:1.7}.sp-success{border-color:#d4e5d4;background:#f0f6ed;color:var(--forest)}.sp-danger{border-color:#eed7d0;background:#fbefeb;color:#93473e}.sp-info{display:flex;gap:13px;margin-bottom:22px;padding:17px;border:1px solid #dce5d8;border-radius:13px;background:#f0f3e9}.sp-info>i{font-size:21px;color:var(--forest)}.sp-info strong{font-size:12px;color:var(--ink)}.sp-info p{margin:5px 0 0;color:#657367;font-size:11px;line-height:1.8}
.sp-summary{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:15px;margin-bottom:22px}.sp-summary-card{display:flex;align-items:center;gap:14px;min-height:105px;padding:18px;border:1px solid var(--line);border-radius:14px;background:var(--paper)}.sp-summary-icon{display:flex;align-items:center;justify-content:center;flex-shrink:0;width:44px;height:44px;border-radius:12px;background:#e9eee5;color:var(--forest);font-size:19px}.sp-summary-icon.sp-blue{background:#e9eff3;color:#426b8a}.sp-summary-icon.sp-amber{background:#f5ede0;color:#a87543}.sp-summary-card div{display:flex;flex-direction:column;gap:4px}.sp-summary-card small{color:var(--muted);font-size:10px;font-weight:700;text-transform:uppercase}.sp-summary-card strong{color:var(--ink);font-size:23px}.sp-summary-card div span{color:var(--muted);font-size:10px}
.sp-quick-card{margin-bottom:23px;padding:21px;border:1px solid #dce3d6;border-radius:15px;background:linear-gradient(135deg,#fffefa,#f1f4eb)}.sp-section-heading{display:flex;align-items:center;gap:12px;margin-bottom:18px}.sp-section-icon{width:43px;height:43px;background:#e5eddf;color:var(--forest);font-size:19px}.sp-section-heading h2{margin:0 0 5px;color:var(--ink);font:700 19px Georgia,serif}.sp-section-heading p{margin:0;color:var(--muted);font-size:11px;line-height:1.6}.sp-quick-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:13px}.sp-field{display:flex;flex-direction:column;gap:7px;min-width:0}.sp-field label{color:#526356;font-size:10px;font-weight:800;letter-spacing:.35px;text-transform:uppercase}.sp-field input,.sp-field select{width:100%;min-height:41px;padding:9px;border:1px solid #dedfd3;border-radius:8px;background:var(--paper);color:var(--ink);font-size:12px}.sp-field small{font-size:10px;color:var(--muted)}.sp-quick-foot{justify-content:space-between;flex-wrap:wrap;margin-top:17px;padding-top:15px;border-top:1px solid #e4e6db}.sp-quick-foot>span{font-size:11px;color:#6f7b70;font-weight:700}.sp-quick-foot .sp-quick-hint{font-weight:500;color:var(--muted)}.sp-manual-field{display:block;min-width:105px}.sp-manual-field input{width:100%;min-height:38px;padding:8px 9px;border:1px solid #dedfd3;border-radius:8px;background:#fffefa;color:var(--ink);font-size:12px;font-weight:700}.sp-manual-field input:focus,.sp-field input:focus{outline:2px solid #cbd9c9;outline-offset:1px;border-color:#829b83}.sp-sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}.sp-note{display:flex;gap:7px;margin:12px 0 0;color:var(--muted);font-size:10px;line-height:1.7}.sp-note i{color:#527f70}
.sp-class-card{margin-bottom:21px;overflow:hidden;border:1px solid var(--line);border-radius:15px;background:var(--paper);box-shadow:0 4px 16px #263b3508}.sp-class-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:15px;padding:18px 21px;border-bottom:1px solid var(--line);background:linear-gradient(135deg,#fffefa,#f7f7ef)}.sp-class-icon{width:44px;height:44px;background:#e9eee5;color:var(--forest);font-size:19px}.sp-class-title{gap:9px;flex-wrap:wrap}.sp-class-title h2{margin:0;color:var(--ink);font:700 19px Georgia,serif}.sp-class-info p{margin:5px 0 0;color:var(--muted);font-size:11px}.sp-class-info p i{color:#527f70}.sp-class-actions{flex-wrap:wrap}.sp-section-badge{border-radius:7px;background:#f5f3e9;color:#6d786b;border-color:#e2e1d6}.sp-table-wrap{width:100%;overflow-x:auto}.sp-table{width:100%;min-width:780px;border-collapse:separate;border-spacing:0}.sp-table th{padding:14px 15px;border-bottom:1px solid var(--line);background:#f4f3eb;color:#727e72;font-size:10px;font-weight:800;letter-spacing:.5px;text-align:left;text-transform:uppercase;white-space:nowrap}.sp-table td{padding:13px 15px;border-bottom:1px solid #eeece3;vertical-align:middle;background:var(--paper)}.sp-table tr:last-child td{border-bottom:0}.sp-table tbody tr:hover td{background:#f8f8f1}.sp-table tbody tr.subject-selected td{background:#f0f3e9}.sp-include-col{width:75px}.sp-checkbox{position:relative;display:inline-flex;align-items:center;justify-content:center;width:23px;height:25px;cursor:pointer}.sp-checkbox input{position:absolute;width:1px;height:1px;opacity:0}.sp-checkbox span{display:block;width:19px;height:19px;border:1.5px solid #c7cbbf;border-radius:5px;background:white}.sp-checkbox input:checked+span{border-color:var(--forest);background:var(--forest)}.sp-checkbox input:checked+span:after{content:"";display:block;width:8px;height:5px;margin:4px 0 0 4px;border-bottom:2px solid white;border-left:2px solid white;transform:rotate(-45deg)}.sp-subject-name{display:flex;align-items:center;gap:10px;min-width:180px}.sp-book{display:flex;align-items:center;justify-content:center;flex-shrink:0;width:37px;height:37px;border-radius:10px;background:#f0f1e8;color:var(--forest)}.sp-subject-name div{display:flex;flex-direction:column;gap:4px}.sp-subject-name strong{color:var(--ink);font-size:12px}.sp-subject-name small{color:var(--muted);font-size:10px}.sp-code,.sp-value-chip{display:inline-flex;align-items:center;gap:7px;padding:7px 9px;border:1px solid #e4e2d6;border-radius:7px;background:#f7f6ef;color:#637063;font-size:11px;font-weight:700;white-space:nowrap}.sp-value-chip{min-width:82px;background:#fffefa;color:var(--ink)}.sp-value-chip i{color:#527f70}.sp-dash{color:#a5a79b}.sp-class-empty{display:flex;align-items:center;gap:13px;padding:30px 20px}.sp-class-empty>i{font-size:24px;color:#81927e}.sp-class-empty strong{font-size:12px}.sp-class-empty p{margin:5px 0 0;color:var(--muted);font-size:11px}.sp-empty{display:flex;align-items:center;flex-direction:column;padding:45px 20px;border:1px solid var(--line);border-radius:15px;background:var(--paper);text-align:center}.sp-empty>i{font-size:42px;color:#81927e}.sp-empty h2{margin:15px 0 8px;color:var(--ink);font:700 22px Georgia,serif}.sp-empty p{color:var(--muted);font-size:12px}.sp-save-panel{position:sticky;bottom:12px;z-index:10;justify-content:space-between;flex-wrap:wrap;gap:15px;margin-top:24px;padding:16px 19px;border:1px solid #d8ded2;border-radius:14px;background:#fffefaF5;box-shadow:0 8px 26px #263b351c;backdrop-filter:blur(8px)}.sp-save-info>span{display:flex;align-items:center;justify-content:center;width:42px;height:42px;border-radius:11px;background:#e9eee5;color:var(--forest);font-size:19px}.sp-save-info strong{font-size:12px;color:var(--ink)}.sp-save-info p{margin:4px 0 0;color:var(--muted);font-size:11px}.sp-save-actions{flex-wrap:wrap}.sp-footer{display:flex;justify-content:center;align-items:center;gap:8px;padding:20px 8px 4px;color:#93988c;font-size:10px}.sp-footer i{color:#527f70}
@media(max-width:1100px){.sp-quick-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:767px){.subjects-page{padding:14px}.sp-summary{grid-template-columns:1fr}.sp-class-header{align-items:flex-start;flex-direction:column;padding:16px}.sp-class-actions{width:100%;align-items:stretch;flex-direction:column}.sp-class-actions .sp-btn{width:100%}.sp-quick-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.sp-save-panel{position:static;align-items:stretch;flex-direction:column}.sp-save-actions{display:grid;grid-template-columns:1fr}.sp-save-actions .sp-btn{width:100%}}@media(max-width:420px){.subjects-page{padding:11px}.sp-quick-grid{grid-template-columns:1fr}.sp-title-line h1{font-size:22px}.sp-header>.sp-btn{width:100%}}

/* Single class-aware subject register */
.sp-register-card{margin:0 0 22px;overflow:hidden;border:1px solid var(--line);border-radius:15px;background:var(--paper);box-shadow:0 4px 16px #263b3508}
.sp-register-heading{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:19px 21px;border-bottom:1px solid var(--line);background:linear-gradient(135deg,#fffefa,#f7f7ef)}
.sp-register-heading h2{margin:0 0 5px;color:var(--ink);font:700 19px Georgia,serif}.sp-register-heading h2 i{margin-right:7px;color:var(--forest)}.sp-register-heading p{margin:0;color:var(--muted);font-size:11px;line-height:1.6}
.sp-register-count{flex-shrink:0;padding:7px 10px;border:1px solid #d9e3d7;border-radius:20px;background:#edf2e9;color:var(--forest);font-size:10px;font-weight:800}
.sp-register-table-wrap{width:100%;overflow-x:auto}.sp-register-table{width:100%;min-width:1120px;border-collapse:separate;border-spacing:0}
.sp-register-table th{padding:14px 12px;border-bottom:1px solid var(--line);background:#f0f1e8;color:#68766a;font-size:10px;font-weight:800;letter-spacing:.35px;text-align:left;text-transform:uppercase;white-space:nowrap}
.sp-register-table td{padding:11px 12px;border-bottom:1px solid #eeece3;vertical-align:middle;background:var(--paper)}.sp-register-table tbody tr:last-child td{border-bottom:0}.sp-register-table tbody tr:hover td{background:#f8f8f1}.sp-register-table tbody tr.subject-selected td{background:#f0f3e9}
.sp-register-table .sp-number-col{width:52px}.sp-register-table .sp-include-col{width:70px}.sp-row-number{color:#879083;font-size:11px;font-weight:700}.sp-class-cell{display:flex;flex-direction:column;gap:4px;min-width:115px}.sp-class-cell strong{color:var(--ink);font-size:12px;white-space:nowrap}.sp-class-cell small{color:var(--muted);font-size:10px}.sp-register-table .sp-subject-name{min-width:175px}.sp-register-table .sp-manual-field{min-width:105px}.sp-register-empty{height:150px;text-align:center;color:var(--muted)}.sp-register-empty i,.sp-register-empty strong,.sp-register-empty span{display:block}.sp-register-empty i{margin-bottom:8px;font-size:25px;color:#81927e}.sp-register-empty strong{margin-bottom:5px;color:var(--ink);font-size:12px}.sp-register-empty span{font-size:11px}.sp-register-footer{display:flex;align-items:center;gap:8px;padding:13px 18px;border-top:1px solid var(--line);background:#faf9f3;color:var(--muted);font-size:10px;line-height:1.6}.sp-register-footer i{color:#527f70}
.sp-quick-grid-four{grid-template-columns:repeat(4,minmax(0,1fr))}.sp-quick-actions{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:16px;padding-top:15px;border-top:1px solid #e4e6db}.sp-quick-actions>span{color:#6f7b70;font-size:11px;font-weight:800}.sp-quick-actions>div{display:flex;gap:9px;flex-wrap:wrap}
@media(max-width:1100px){.sp-quick-grid-four{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:767px){.sp-register-heading{align-items:flex-start;flex-direction:column;padding:16px}.sp-quick-grid-four{grid-template-columns:1fr}.sp-quick-actions{align-items:stretch;flex-direction:column}.sp-quick-actions>div{display:grid;grid-template-columns:1fr}.sp-quick-actions .sp-btn{width:100%}}
@media print{.sp-header>.sp-btn,.sp-back,.sp-quick-card,.sp-save-panel,.sp-footer{display:none!important}.subjects-page{max-width:none;padding:0}.sp-register-table{min-width:0}.sp-register-table th,.sp-register-table td{padding:6px;font-size:9px}.sp-register-table .sp-manual-field input{border:0;padding:0;min-height:0}}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const page = document.querySelector('.subjects-page');
    if (!page) return;

    const bulkMaximum = page.querySelector('#bulkMaximumMarks');
    const bulkPassing = page.querySelector('#bulkPassingPercent');
    const bulkDuration = page.querySelector('#bulkDuration');
    const bulkCount = page.querySelector('#bulkSelectedCount');

    const rowsForClass = classId => Array.from(page.querySelectorAll('.subject-row'))
        .filter(row => String(row.dataset.classId) === String(classId));

    function renderRow(row) {
        const checkbox = row.querySelector('.subject-checkbox');
        row.classList.toggle('subject-selected', !!checkbox?.checked);
    }

    function refreshCount() {
        if (!bulkCount) return;
        const checked = Array.from(page.querySelectorAll('.subject-checkbox:checked'));
        bulkCount.textContent = checked.length + ' selected subject' + (checked.length === 1 ? '' : 's');
    }

    function updateSelectAllButton(classId) {
        const button = page.querySelector('.subjects-select-all-btn[data-class-id="' + classId + '"]');
        if (!button) return;
        const checkboxes = rowsForClass(classId)
            .map(row => row.querySelector('.subject-checkbox')).filter(Boolean);
        const allChecked = checkboxes.length > 0 && checkboxes.every(checkbox => checkbox.checked);
        button.setAttribute('aria-pressed', String(allChecked));
        button.innerHTML = allChecked
            ? '<i class="bi bi-x-circle"></i><span>Unselect All</span>'
            : '<i class="bi bi-check2-all"></i><span>Select All</span>';
    }

    page.querySelectorAll('.subject-row').forEach(renderRow);

    page.querySelectorAll('.subject-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            renderRow(this.closest('.subject-row'));
            updateSelectAllButton(this.dataset.classId);
            refreshCount();
        });
    });

    const quickClass = page.querySelector('#quickSetupClass');
    const selectAllClassButton = page.querySelector('#selectAllClassSubjects');
    const applyQuickSetupButton = page.querySelector('#applyQuickSetup');

    function selectedClassId() {
        return quickClass ? String(quickClass.value) : '';
    }

    function updateSelectAllClassButton() {
        if (!selectAllClassButton) return;
        const checkboxes = rowsForClass(selectedClassId())
            .map(row => row.querySelector('.subject-checkbox')).filter(Boolean);
        const allChecked = checkboxes.length > 0 && checkboxes.every(checkbox => checkbox.checked);
        selectAllClassButton.innerHTML = allChecked
            ? '<i class="bi bi-x-circle"></i> Unselect All in Class'
            : '<i class="bi bi-check2-all"></i> Select All in Class';
        selectAllClassButton.setAttribute('aria-pressed', String(allChecked));
        selectAllClassButton.disabled = checkboxes.length === 0;
    }

    page.querySelectorAll('.subject-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            renderRow(this.closest('.subject-row'));
            updateSelectAllClassButton();
            refreshCount();
        });
    });

    if (quickClass) {
        quickClass.addEventListener('change', function () {
            updateSelectAllClassButton();
        });
    }

    if (selectAllClassButton) {
        selectAllClassButton.addEventListener('click', function () {
            const classId = selectedClassId();
            const checkboxes = rowsForClass(classId)
                .map(row => row.querySelector('.subject-checkbox')).filter(Boolean);
            const selectAll = checkboxes.some(checkbox => !checkbox.checked);
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll;
                renderRow(checkbox.closest('.subject-row'));
            });
            updateSelectAllClassButton();
            refreshCount();
        });
    }

    if (applyQuickSetupButton) {
        applyQuickSetupButton.addEventListener('click', function () {
            const classId = selectedClassId();
            const selectedRows = rowsForClass(classId)
                .filter(row => row.querySelector('.subject-checkbox')?.checked);

            if (!selectedRows.length) {
                alert('Select at least one subject in the selected class first.');
                return;
            }

            const maximum = Number(bulkMaximum.value);
            const passing = Number(bulkPassing.value);
            const duration = Number(bulkDuration.value);

            if (!Number.isInteger(maximum) || maximum < 1 || maximum > 1000) {
                alert('Maximum marks must be a whole number from 1 to 1000.');
                bulkMaximum.focus();
                return;
            }
            if (!Number.isInteger(passing) || passing < 0 || passing > maximum) {
                alert('Passing marks must be a whole number between 0 and maximum marks.');
                bulkPassing.focus();
                return;
            }
            if (!Number.isInteger(duration) || duration < 1 || duration > 600) {
                alert('Duration must be a whole number from 1 to 600 minutes.');
                bulkDuration.focus();
                return;
            }

            selectedRows.forEach(row => {
                row.querySelector('.maximum-marks').value = maximum;
                row.querySelector('.passing-marks').value = passing;
                row.querySelector('.duration-input').value = duration;
                renderRow(row);
            });

            alert('Quick Setup applied to ' + selectedRows.length +
                ' checked subject(s) in the selected class only. Click Save Subjects & Marks to save.');
        });
    }

    // Validate each row before submitting.
    const form = page.querySelector('#examSubjectsForm');
    const saveButton = page.querySelector('#saveSubjectsButton');
    if (form) {
        form.addEventListener('submit', function (event) {
            let firstInvalid = null;

            page.querySelectorAll('.subject-row').forEach(row => {
                const maximumInput = row.querySelector('.maximum-marks');
                const passingInput = row.querySelector('.passing-marks');
                const durationInput = row.querySelector('.duration-input');

                const maximum = Number(maximumInput.value);
                const passing = Number(passingInput.value);
                const duration = Number(durationInput.value);

                const rowValid =
                    Number.isInteger(maximum) && maximum >= 1 && maximum <= 1000 &&
                    Number.isInteger(passing) && passing >= 0 && passing <= maximum &&
                    Number.isInteger(duration) && duration >= 1 && duration <= 600;

                [maximumInput, passingInput, durationInput].forEach(input => {
                    input.setCustomValidity('');
                });

                if (!rowValid) {
                    if (!Number.isInteger(maximum) || maximum < 1 || maximum > 1000) {
                        maximumInput.setCustomValidity('Maximum marks must be 1–1000.');
                    } else if (!Number.isInteger(passing) || passing < 0 || passing > maximum) {
                        passingInput.setCustomValidity('Passing marks must be between 0 and maximum marks.');
                    } else {
                        durationInput.setCustomValidity('Duration must be 1–600 minutes.');
                    }
                    firstInvalid = firstInvalid || row.querySelector(':invalid');
                }
            });

            if (firstInvalid) {
                event.preventDefault();
                firstInvalid.reportValidity();
                firstInvalid.focus();
                return;
            }

            if (saveButton) {
                saveButton.disabled = true;
                saveButton.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Saving...</span>';
            }
        });
    }

    updateSelectAllClassButton();
    refreshCount();
});
</script>
@endsection
