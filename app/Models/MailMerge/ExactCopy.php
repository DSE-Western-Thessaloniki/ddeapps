<?php

namespace App\Models\MailMerge;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ExactCopy extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'text', 'active',
        'updated_by', 'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
