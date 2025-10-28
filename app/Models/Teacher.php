<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'photo',
        'bio',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function homeroomClasses(): HasMany
    {
        return $this->hasMany(ClassModel::class, 'homeroom_teacher_id');
    }

    public function managedClassrooms(): HasMany
    {
        return $this->hasMany(Classroom::class, 'pic_id');
    }
}
