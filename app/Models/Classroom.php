<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    /** @use HasFactory<\Database\Factories\ClassroomFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'capacity',
        'pic_id',
        'pic_photo',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'type' => 'string',
        ];
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'pic_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
