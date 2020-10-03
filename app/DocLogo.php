<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocLogo extends Model
{
    // Table name
    protected $table = 'mmdoclogo';
    // Primary key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'image', 'text', 'active',
    ];
}
