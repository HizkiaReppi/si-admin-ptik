<?php

namespace App\Models;

use App\Helpers\EncryptionHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['title', 'content', 'user_id'];

    // Accessor untuk mendekripsi pesan saat diakses
    public function getContentAttribute($value): string
    {
        return EncryptionHelper::decryptContent($value, $this->getEncryptionKey());
    }

    // Mutator untuk mengenkripsi pesan saat disimpan
    public function setContentAttribute($value): void
    {
        $this->attributes['content'] = EncryptionHelper::encryptContent($value, $this->getEncryptionKey());
    }

    private function getEncryptionKey(): string
    {
        return config('app.key');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
