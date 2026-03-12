<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'school_id', 'academic_year_id', 'class_id', 'subject_id',
        'teacher_id', 'day_of_week', 'start_time', 'end_time', 'room',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
    ];

    public static array $dayNames = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
    ];

    public static array $periods = [
        '08:30' => '08:30 – 10:00',
        '10:15' => '10:15 – 11:45',
        '14:00' => '14:00 – 15:30',
        '15:45' => '15:45 – 17:15',
    ];

    public static array $periodEnds = [
        '08:30' => '10:00',
        '10:15' => '11:45',
        '14:00' => '15:30',
        '15:45' => '17:15',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function getDayNameAttribute(): string
    {
        return self::$dayNames[$this->day_of_week] ?? '';
    }

    public function getStartTimeShortAttribute(): string
    {
        return substr($this->start_time, 0, 5);
    }

    public function getEndTimeShortAttribute(): string
    {
        return substr($this->end_time, 0, 5);
    }

    public function getTimeRangeAttribute(): string
    {
        return $this->start_time_short . ' – ' . $this->end_time_short;
    }
}
