<?php

namespace App\Models\MailMerge;

use Illuminate\Database\Eloquent\Model;

class DocLogo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'image', 'text', 'active',
    ];
}
