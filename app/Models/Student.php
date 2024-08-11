<?php

namespace App\Models;

use App\Helpers\StudentHelper;
use App\Helpers\TextFormattingHelper;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'lecturer_id', 'nim', 'batch', 'concentration', 'phone_number', 'address', 'photo'];

    /**
     * Get the user that owns the student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the dosen pembimbing 1 for the student.
     */
    public function firstSupervisor(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_id_1');
    }

    /**
     * Get the dosen pembimbing 2 for the student.
     */
    public function secondSupervisor(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_id_2');
    }

    /**
     * Get the fullname of the dosen pembimbing 1.
     */
    public function getFirstSupervisorFullnameAttribute(): string
    {
        if ($this->firstSupervisor) {
            return $this->firstSupervisor->front_degree . ' ' . $this->firstSupervisor->user->name . ' ' . $this->firstSupervisor->back_degree;
        }

        return 'Mahasiswa Belum Memiliki Dosen Pembimbing 1';
    }

    /**
     * Get the fullname of the dosen pembimbing 2.
     */
    public function getSecondSupervisorFullnameAttribute(): string
    {
        if ($this->secondSupervisor) {
            return $this->secondSupervisor->front_degree . ' ' . $this->secondSupervisor->user->name . ' ' . $this->secondSupervisor->back_degree;
        }

        return 'Mahasiswa Belum Memiliki Dosen Pembimbing 2';
    }

    /**
     * Get the fullname of student.
     */
    public function getFullnameAttribute(): string
    {
        return $this->user->name;
    }

    /**
     * Format NIM.
     */
    public function getFormattedNIMAttribute(): string
    {
        return TextFormattingHelper::formatNIM($this->nim);
    }

    /**
     * Get Current Semester.
     */
    public function getCurrentSemesterAttribute(): string
    {
        return StudentHelper::getCurrentSemesterStudent($this->batch);
    }
}
