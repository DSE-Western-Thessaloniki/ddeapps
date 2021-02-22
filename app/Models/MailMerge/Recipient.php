<?php

namespace App\Models\MailMerge;

use Illuminate\Database\Eloquent\Model;
use App\User;

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
        'updated_by', 'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function links()
    {
        $result = $this->query()
            ->selectRaw('GROUP_CONCAT(name SEPARATOR ", ") as links')
            ->where('link', '=', $this->attributes['name'])
            ->groupBy('link')
            ->get();
        if ($result->isNotEmpty()) {
            return($result[0]->links);
        }
        return('');
    }

    public function linksJson()
    {
        $result = $this->query()
            ->select('id', 'name')
            ->where('link', '=', $this->attributes['name'])
            ->get();
        return(json_encode($result->toArray()));
    }
}
