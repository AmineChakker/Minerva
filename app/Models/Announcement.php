<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Announcement extends Model {
    protected $fillable = ['school_id', 'user_id', 'title', 'content', 'type', 'is_published'];
    protected $casts = ['is_published' => 'boolean'];
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function user(): BelongsTo  { return $this->belongsTo(User::class); }
}
