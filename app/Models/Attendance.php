<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Attendance extends Model {
    protected $fillable = ['school_id', 'student_id', 'class_room_id', 'date', 'status', 'note'];
    protected $casts = ['date' => 'date'];
    public function school(): BelongsTo    { return $this->belongsTo(School::class); }
    public function student(): BelongsTo   { return $this->belongsTo(Student::class); }
    public function classRoom(): BelongsTo { return $this->belongsTo(ClassRoom::class, 'class_room_id'); }
}
