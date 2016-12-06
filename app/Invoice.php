<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = ['id'];
    protected $visible = ['id', 'link', 'contact', 'created_at'];
    protected $appends = ['link'];

    public static $rules = [
        'file_id'    => 'required|exists:files,id',
        'contact_id' => 'required|exists:contacts,id',
    ];

    public function file()
    {
        return $this->belongsTo('App\File');
    }

    public function contact()
    {
        return ($this->attributes['contact'] = $this->belongsTo('App\Contact'));
    }

    public function getLinkAttribute()
    {
        return $this->file->link();
    }
}
