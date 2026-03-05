<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = [
        'name', 'slug', 'email', 'phone', 'address', 'city', 'country',
        'logo', 'subscription_plan', 'subscription_expires_at', 'is_active',
    ];

    protected $casts = [
        'subscription_expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function academicYears(): HasMany
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(ClassRoom::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function currentAcademicYear()
    {
        return $this->academicYears()->where('is_current', true)->first();
    }
}
