<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = ['id'];
    protected $visible = ['id', 'name', 'email'];

    public static $rules = [
        'name'    => 'required',
        'email'    => 'required|email',
    ];
}
