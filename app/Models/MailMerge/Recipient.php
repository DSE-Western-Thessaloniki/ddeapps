<?php

namespace App\Models\MailMerge;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'link',
    ];
}
