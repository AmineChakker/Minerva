<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    protected $fillable = [
        'user_id', 'employee_id', 'hire_date', 'qualification',
        'experience_years', 'specialization', 'salary', 'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary'    => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
