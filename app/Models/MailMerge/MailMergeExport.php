<?php

namespace App\Models\MailMerge;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailMergeExport extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_READY = 'ready';

    public const STATUS_FAILED = 'failed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'mail_merge_id',
        'status',
        'total',
        'processed',
        'path',
        'error',
    ];

    protected $casts = [
        'total' => 'integer',
        'processed' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mailMerge()
    {
        return $this->belongsTo(MailMerge::class);
    }

    public function isReady(): bool
    {
        return $this->status === self::STATUS_READY;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }
}
