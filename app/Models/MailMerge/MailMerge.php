<?php

namespace App\Models\MailMerge;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MailMerge extends Model
{
    use HasFactory;

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
        'ada',
        'files_for_teachers',
    ];

    protected $casts = [
        'files_for_teachers' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function editor()
    {
        return $this->belongsTo(Editor::class, 'editor_id');
    }

    public function logo()
    {
        return $this->belongsTo(DocLogo::class, 'logo_id');
    }

    public function exactCopy()
    {
        return $this->belongsTo(ExactCopy::class, 'exact_copy_id');
    }

    public function signature()
    {
        return $this->belongsTo(Signature::class, 'signature_id');
    }

    public function signedFiles(): array
    {
        return Storage::files("signed/{$this->id}");
    }
}
