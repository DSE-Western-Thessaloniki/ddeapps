<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExactCopy extends Model
{
    // Table name
    protected $table = 'exact_copy';
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
