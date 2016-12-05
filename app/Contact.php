<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use \Sofa\Eloquence\Eloquence;

    protected $guarded = ['id'];
    protected $visible = ['id', 'name', 'email'];
    protected $searchableColumns = ['name', 'email'];

    public static $rules = [
        'name'    => 'required',
        'email'    => 'required|email',
    ];
}
