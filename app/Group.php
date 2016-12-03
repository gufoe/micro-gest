<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $guarded = ['id'];
    protected $visible = ['id', 'name', 'size', 'contacts'];
    protected $appends = ['size'];

    public static $rules = [
        'name'    => 'required',
    ];

    public function contacts()
    {
        return $this->belongsToMany('App\Contact', 'group_contacts');
    }

    public function getSizeAttribute()
    {
        return $this->contacts()->count();
    }
}
