<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['signup']]);
    }

    public function list(Request $request)
    {
        return User::where('email', 'like', "%{$request->input('q')}%")->get();
    }

    public function self()
    {
        return user();
    }

    public function signup(Request $req)
    {
        if (!env('SIGNUP')) {
            return error('Registrazione disabilitata', ['disabled' => true]);
        }
        $data = [
            'email'    => $req->input('email'),
            'name'     => $req->input('name'),
            'password' => $req->input('password'),
        ];

        validate(User::$rules, $data);

        if (User::where('email', $data['email'])->exists()) {
            return error('This email address has already been used');
        }
        $user = User::create($data);
        return success('user', $user);
    }

    public function account(Request $request)
    {
        if (app()->environment() == 'demo') {
            return error('Non è consentito cambiare impostazioni nella modalità demo');
        }
        $data = [
            'email'    => $request->input('email'),
            'name'     => $request->input('name'),
            'password' => $request->input('password'),
        ];

        if ($data['email'] == user()->email) {
            unset($data['email']);
        }
        if ($data['name'] == user()->name) {
            unset($data['name']);
        }
        if (!$data['password']) {
            unset($data['password']);
        }

        if (!user()->login($request->input('old_password'))) {
            return error('La vecchia password non corrisponde.');
        }

        validate(User::$edit_rules, $data);

        user()->update($data);

        return user();
    }
}
