<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    // Table name
    protected $table = 'signatures';
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
        'title', 'text', 'active',
    ];

}
