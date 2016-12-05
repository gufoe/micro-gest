<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    protected $guarded = ['id'];
    protected $visible = ['id', 'name', 'email'];

    public static $rules = [
        'email'    => 'required|email|unique:users',
        'name'     => 'required',
        'password' => 'min:5',
    ];
    public static $edit_rules = [
        'email'    => 'email|unique:users',
        'password' => 'min:5',
    ];

    public function generateSession()
    {
        $session = Session::create([
            'user_id' => $this->id,
            'token' => str_random(10),
        ]);
        return $session;
    }
    public function destroySession($token)
    {
        $this->sessions()->whereToken($token)->delete();
    }

    public function sessions()
    {
        return $this->hasMany('App\Session');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function login($password)
    {
        return Hash::check($password, $this->password);
    }

    public static function byToken($token)
    {
        $session = Session::whereToken($token)->first();
        return $session ? $session->user : null;
    }
}
