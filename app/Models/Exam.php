<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Exam extends Model {
    protected $fillable = ['school_id', 'class_room_id', 'subject_id', 'academic_year_id', 'name', 'exam_date', 'total_marks'];
    protected $casts = ['exam_date' => 'date'];
    public function school(): BelongsTo       { return $this->belongsTo(School::class); }
    public function classRoom(): BelongsTo    { return $this->belongsTo(ClassRoom::class, 'class_room_id'); }
    public function subject(): BelongsTo      { return $this->belongsTo(Subject::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function results(): HasMany        { return $this->hasMany(ExamResult::class); }
}
