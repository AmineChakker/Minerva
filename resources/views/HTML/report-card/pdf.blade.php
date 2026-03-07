<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a2e; background: #fff; }

    /* ── Header ── */
    .header { text-align: center; padding: 20px 0 16px; border-bottom: 3px solid #2b7fff; margin-bottom: 18px; }
    .header .school-name { font-size: 22px; font-weight: 700; color: #2b7fff; letter-spacing: 1px; }
    .header .school-sub  { font-size: 10px; color: #555; margin-top: 3px; }
    .header .doc-title   { font-size: 14px; font-weight: 700; color: #1a1a2e; margin-top: 10px; letter-spacing: 2px; text-transform: uppercase; }
    .header .doc-year    { font-size: 10px; color: #2b7fff; margin-top: 2px; }

    /* ── Section title ── */
    .section-title {
        background: #2b7fff; color: #fff;
        font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
        padding: 5px 10px; margin-bottom: 0;
    }

    /* ── Info table ── */
    .info-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; border: 1px solid #e2e8f0; }
    .info-table td { padding: 6px 10px; font-size: 10.5px; border-bottom: 1px solid #f0f0f0; }
    .info-table td.label { font-weight: 700; color: #555; width: 22%; background: #f8faff; }
    .info-table td.value { color: #1a1a2e; }

    /* ── Results table ── */
    .results-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    .results-table th {
        background: #f0f5ff; color: #2b7fff;
        font-size: 9.5px; font-weight: 700; text-transform: uppercase;
        padding: 7px 8px; border: 1px solid #dce7ff; text-align: left;
    }
    .results-table td { padding: 6px 8px; font-size: 10.5px; border: 1px solid #eef0f4; vertical-align: middle; }
    .results-table tr:nth-child(even) td { background: #fafbff; }
    .results-table .subject-group td { background: #f0f5ff !important; font-weight: 700; color: #2b7fff; font-size: 10px; }
    .results-table .no-data td { text-align: center; color: #aaa; font-style: italic; padding: 14px; }

    /* ── Grade badge ── */
    .grade-badge {
        display: inline-block; padding: 1px 6px;
        border-radius: 3px; font-weight: 700; font-size: 10px;
    }
    .grade-a  { background: #dcfce7; color: #16a34a; }
    .grade-b  { background: #dbeafe; color: #1d4ed8; }
    .grade-c  { background: #fef9c3; color: #a16207; }
    .grade-d  { background: #ffedd5; color: #c2410c; }
    .grade-f  { background: #fee2e2; color: #dc2626; }

    /* ── Attendance summary ── */
    .attend-grid { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    .attend-grid td { width: 20%; padding: 10px 8px; text-align: center; border: 1px solid #eef0f4; }
    .attend-grid .stat-num  { font-size: 20px; font-weight: 700; color: #2b7fff; display: block; }
    .attend-grid .stat-lbl  { font-size: 9px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
    .attend-grid .stat-pct  { font-size: 20px; font-weight: 700; color: #16a34a; display: block; }

    /* ── Overall summary ── */
    .summary-box { border: 2px solid #2b7fff; border-radius: 6px; padding: 14px 18px; margin-bottom: 16px; }
    .summary-box table { width: 100%; border-collapse: collapse; }
    .summary-box td { padding: 4px 8px; font-size: 11px; }
    .summary-box .big-grade {
        font-size: 36px; font-weight: 700; color: #2b7fff;
        text-align: center; padding: 0 20px;
        border-left: 2px solid #dce7ff;
    }

    /* ── Progress bar ── */
    .bar-bg  { background: #e5e7eb; border-radius: 4px; height: 6px; width: 100%; }
    .bar-fill { border-radius: 4px; height: 6px; background: #2b7fff; }

    /* ── Signatures ── */
    .signatures { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .signatures td { width: 33%; padding: 8px 20px; text-align: center; font-size: 10px; color: #555; }
    .sig-line { border-top: 1px solid #999; margin-bottom: 4px; height: 30px; }

    /* ── Footer ── */
    .footer { margin-top: 16px; border-top: 1px solid #e2e8f0; padding-top: 8px; text-align: center; font-size: 9px; color: #aaa; }
</style>
</head>
<body>

{{-- ══ HEADER ══ --}}
<div class="header">
    <div class="school-name">{{ strtoupper($school->name ?? 'EduPulse School') }}</div>
    <div class="school-sub">
        {{ $school->address ?? '' }}{{ $school->address && $school->city ? ', ' : '' }}{{ $school->city ?? '' }}
        &nbsp;&nbsp;|&nbsp;&nbsp; {{ $school->email ?? '' }}
        @if($school->phone) &nbsp;&nbsp;|&nbsp;&nbsp; {{ $school->phone }} @endif
    </div>
    <div class="doc-title">Academic Report Card</div>
    <div class="doc-year">{{ $academicYear->name }}</div>
</div>

{{-- ══ STUDENT INFO ══ --}}
<div class="section-title">Student Information</div>
<table class="info-table">
    <tr>
        <td class="label">Full Name</td>
        <td class="value"><strong>{{ $student->user->full_name }}</strong></td>
        <td class="label">Admission No.</td>
        <td class="value">{{ $student->admission_number ?? '—' }}</td>
    </tr>
    <tr>
        <td class="label">Class</td>
        <td class="value">{{ $student->classRoom->full_name ?? '—' }}</td>
        <td class="label">Academic Year</td>
        <td class="value">{{ $academicYear->name }}</td>
    </tr>
    <tr>
        <td class="label">Date of Birth</td>
        <td class="value">{{ $student->date_of_birth ? $student->date_of_birth->format('d M Y') : '—' }}</td>
        <td class="label">Gender</td>
        <td class="value">{{ ucfirst($student->user->gender ?? '—') }}</td>
    </tr>
    <tr>
        <td class="label">Report Generated</td>
        <td class="value">{{ now()->format('d M Y') }}</td>
        <td class="label">Status</td>
        <td class="value">{{ ucfirst($student->status ?? 'active') }}</td>
    </tr>
</table>

{{-- ══ ACADEMIC PERFORMANCE ══ --}}
<div class="section-title">Academic Performance</div>
<table class="results-table">
    <thead>
        <tr>
            <th>Subject</th>
            <th>Exam</th>
            <th>Date</th>
            <th style="text-align:center">Out Of</th>
            <th style="text-align:center">Obtained</th>
            <th style="text-align:center">%</th>
            <th style="text-align:center">Grade</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
    @forelse($reportData['subjectRows'] as $subject => $rows)
        @foreach($rows as $i => $row)
        <tr>
            @if($i === 0)
            <td rowspan="{{ count($rows) }}" style="font-weight:700; color:#2b7fff; vertical-align:top;">{{ $subject }}</td>
            @endif
            <td>{{ $row['exam'] }}</td>
            <td style="white-space:nowrap">{{ $row['date'] }}</td>
            <td style="text-align:center">{{ $row['total_marks'] }}</td>
            <td style="text-align:center">{{ $row['marks_obtained'] ?? '—' }}</td>
            <td style="text-align:center">
                @if($row['pct'] !== null)
                    {{ $row['pct'] }}%
                    <div class="bar-bg" style="margin-top:3px">
                        <div class="bar-fill" style="width:{{ min($row['pct'],100) }}%"></div>
                    </div>
                @else —
                @endif
            </td>
            <td style="text-align:center">
                @if($row['grade'])
                @php
                    $gc = match(true) {
                        str_starts_with($row['grade'],'A') => 'grade-a',
                        str_starts_with($row['grade'],'B') => 'grade-b',
                        str_starts_with($row['grade'],'C') => 'grade-c',
                        str_starts_with($row['grade'],'D') => 'grade-d',
                        default => 'grade-f',
                    };
                @endphp
                <span class="grade-badge {{ $gc }}">{{ $row['grade'] }}</span>
                @else —
                @endif
            </td>
            <td style="font-style:italic; color:#666; font-size:9.5px">{{ $row['remarks'] ?? '' }}</td>
        </tr>
        @endforeach
    @empty
        <tr class="no-data"><td colspan="8">No exam results recorded for this academic year.</td></tr>
    @endforelse
    </tbody>
    @if(!empty($reportData['subjectRows']) && $reportData['totalMarks'] > 0)
    <tfoot>
        <tr style="background:#f0f5ff">
            <td colspan="3" style="font-weight:700; padding:7px 8px; font-size:10.5px">Total</td>
            <td style="text-align:center; font-weight:700">{{ $reportData['totalMarks'] }}</td>
            <td style="text-align:center; font-weight:700">{{ $reportData['obtainedMarks'] }}</td>
            <td style="text-align:center; font-weight:700; color:#2b7fff">{{ $reportData['overallPct'] }}%</td>
            <td colspan="2" style="text-align:center">
                @php
                    $ogc = match(true) {
                        str_starts_with($reportData['overallGrade'],'A') => 'grade-a',
                        str_starts_with($reportData['overallGrade'],'B') => 'grade-b',
                        str_starts_with($reportData['overallGrade'],'C') => 'grade-c',
                        str_starts_with($reportData['overallGrade'],'D') => 'grade-d',
                        default => 'grade-f',
                    };
                @endphp
                <span class="grade-badge {{ $ogc }}">{{ $reportData['overallGrade'] }}</span>
            </td>
        </tr>
    </tfoot>
    @endif
</table>

{{-- ══ ATTENDANCE ══ --}}
<div class="section-title">Attendance Summary</div>
<table class="attend-grid" style="margin-top:0; border:1px solid #eef0f4;">
    <tr>
        <td style="border:1px solid #eef0f4; background:#f8faff">
            <span class="stat-num">{{ $reportData['totalDays'] }}</span>
            <span class="stat-lbl">Total Days</span>
        </td>
        <td style="border:1px solid #eef0f4">
            <span class="stat-num" style="color:#16a34a">{{ $reportData['presentCount'] }}</span>
            <span class="stat-lbl">Present</span>
        </td>
        <td style="border:1px solid #eef0f4">
            <span class="stat-num" style="color:#dc2626">{{ $reportData['absentCount'] }}</span>
            <span class="stat-lbl">Absent</span>
        </td>
        <td style="border:1px solid #eef0f4">
            <span class="stat-num" style="color:#d97706">{{ $reportData['lateCount'] }}</span>
            <span class="stat-lbl">Late</span>
        </td>
        <td style="border:1px solid #eef0f4; background:#f8faff">
            <span class="stat-pct">{{ $reportData['attendancePct'] }}%</span>
            <span class="stat-lbl">Attendance Rate</span>
        </td>
    </tr>
</table>
<div style="margin-bottom:16px; padding:4px 10px; border:1px solid #eef0f4; border-top:0">
    <div class="bar-bg">
        <div class="bar-fill" style="width:{{ min($reportData['attendancePct'],100) }}%; background:{{ $reportData['attendancePct'] >= 75 ? '#16a34a' : ($reportData['attendancePct'] >= 50 ? '#d97706' : '#dc2626') }}"></div>
    </div>
</div>

{{-- ══ OVERALL SUMMARY ══ --}}
<div class="section-title">Overall Performance</div>
<div class="summary-box" style="margin-top:0">
    <table>
        <tr>
            <td style="width:75%; vertical-align:middle">
                <table style="width:100%">
                    <tr>
                        <td style="padding:4px 0; font-weight:700; color:#555; font-size:10px; width:40%">Overall Score</td>
                        <td style="padding:4px 0; font-size:11px">
                            @if($reportData['overallPct'] !== null)
                                {{ $reportData['obtainedMarks'] }} / {{ $reportData['totalMarks'] }} &nbsp;({{ $reportData['overallPct'] }}%)
                            @else
                                — (No results recorded)
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0; font-weight:700; color:#555; font-size:10px">Attendance</td>
                        <td style="padding:4px 0; font-size:11px">{{ $reportData['presentCount'] }}/{{ $reportData['totalDays'] }} days ({{ $reportData['attendancePct'] }}%)</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0; font-weight:700; color:#555; font-size:10px">Result</td>
                        <td style="padding:4px 0; font-size:11px">
                            @if($reportData['overallPct'] !== null)
                                @if($reportData['overallPct'] >= 60)
                                    <span style="color:#16a34a; font-weight:700">PASS</span>
                                @else
                                    <span style="color:#dc2626; font-weight:700">FAIL</span>
                                @endif
                            @else
                                <span style="color:#aaa">Pending</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-top:10px; font-size:10px; color:#555">
                            <strong>Grade Scale:</strong>&nbsp;
                            A+ ≥95% &nbsp;|&nbsp; A ≥90% &nbsp;|&nbsp; B+ ≥85% &nbsp;|&nbsp; B ≥80% &nbsp;|&nbsp;
                            C+ ≥75% &nbsp;|&nbsp; C ≥70% &nbsp;|&nbsp; D ≥60% &nbsp;|&nbsp; F &lt;60%
                        </td>
                    </tr>
                </table>
            </td>
            <td class="big-grade">{{ $reportData['overallGrade'] }}</td>
        </tr>
    </table>
</div>

{{-- ══ SIGNATURES ══ --}}
<table class="signatures">
    <tr>
        <td><div class="sig-line"></div>Class Teacher</td>
        <td><div class="sig-line"></div>School Principal</div></td>
        <td><div class="sig-line"></div>Parent / Guardian</td>
    </tr>
</table>

<div class="footer">
    This report card is computer-generated. &nbsp;|&nbsp;
    {{ $school->name ?? 'EduPulse' }} &nbsp;|&nbsp;
    Generated on {{ now()->format('d M Y, h:i A') }}
</div>

</body>
</html>
