<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Fee extends Model {
    protected $fillable = ['school_id', 'student_id', 'academic_year_id', 'title', 'amount', 'due_date', 'paid_at', 'status'];
    protected $casts = ['due_date' => 'date', 'paid_at' => 'datetime', 'amount' => 'decimal:2'];
    public function school(): BelongsTo       { return $this->belongsTo(School::class); }
    public function student(): BelongsTo      { return $this->belongsTo(Student::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
}
