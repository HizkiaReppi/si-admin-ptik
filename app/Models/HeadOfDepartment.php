<?php

namespace App\Models;

use App\Helpers\TextFormattingHelper;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeadOfDepartment extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'nip',
        'nidn',
        'front_degree',
        'back_degree',
        'position',
        'rank',
        'type',
        'phone_number',
    ];

    /**
     * Get the user that owns the student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the fullname with gelar depan and gelar belakang.
     */
    public function getFullnameAttribute(): string
    {
        return $this->front_degree . ' ' . $this->user->name . ', ' . $this->back_degree;
    }

    /**
     * Format NIP.
     */
    public function getFormattedNIPAttribute(): string
    {
        return TextFormattingHelper::formatNIP($this->nip);
    }

    /**
     * Format NIDN.
     */
    public function getFormattedNIDNAttribute(): string
    {
        return TextFormattingHelper::formatNIDN($this->nidn);
    }
}
