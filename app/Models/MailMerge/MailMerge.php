<?php

namespace App\Models\MailMerge;

use Illuminate\Database\Eloquent\Model;
use App\User;

class MailMerge extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'editor_id',
        'logo_id',
        'address_id',
        'protocol_num',
        'date',
        'subject',
        'text',
        'exact_copy_id',
        'signature_id',
        'xlsxdata',
        'xlsxdata_header',
        'mergefields',
        'updated_by',
        'created_by',
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
