<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use \Sofa\Eloquence\Eloquence;

    protected $guarded = ['id'];
    protected $visible = ['id', 'name', 'email', 'cf'];
    protected $searchableColumns = ['name', 'email', 'cf'];

    public static $rules = [
        'name'  => 'required',
        'email' => 'required|email',
        'cf'    => 'required',
    ];

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    public function setCfAttribute($cf)
    {
        $this->attributes['cf'] = strtoupper($cf);
    }

    public function getTokenAttribute($token)
    {
        if (!$token) {
            $token = str_random(20);
            $this->update(['token' => $token]);
        }
        return $token;
    }
}
