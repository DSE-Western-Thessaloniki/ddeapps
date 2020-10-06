<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocAddress extends Model
{
    // Table name
    protected $table = 'addresses';
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
        'title', 'address', 'name', 'telephone', 'email',
    ];

}
