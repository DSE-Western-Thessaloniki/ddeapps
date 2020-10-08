<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailMerge extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'logo_id',
        'address_id',
        'protocol_num',
        'date',
        'subject',
        'text',
        'exact_copy_id',
        'signature_id',
    ];

}
