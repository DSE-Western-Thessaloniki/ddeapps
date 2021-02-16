<?php

namespace App\Models\MailMerge;

use Illuminate\Database\Eloquent\Model;

class DocAddress extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'address', 'name', 'telephone', 'email',
    ];
}
