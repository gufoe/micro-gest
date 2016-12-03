<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\Group;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(Request $request)
    {
        $query = null;
        if (($gid = $request->input('group_id'))) {
            $query = Group::findOrFail($gid)->contacts();
        } else {
            $query = Contact::limit(1000);
        }
        if (($s = $request->input('name')) || ($s = $request->input('q'))) {
            $query->where('name', 'like', "%{$s}%");
        }
        if (($s = $request->input('email'))) {
            $query->where('email', 'like', "%{$s}%");
        }
        return $query->paginate(10);
    }

    public function edit(Request $request, $id = null)
    {
        $contact = Contact::find($id);

        $data = [
            'name'  => $request->input('name'),
            'email' => $request->input('email'),
        ];
        validate(Contact::$rules, $data);

        if (!$contact || $data['email'] != $contact['email']) {
            if (Contact::whereEmail($data['email'])->exists()) {
                return error('La mail è già in uso da un altro contatto');
            }
        }

        if ($contact) {
            $contact->update($data);
        } else {
            $contact = Contact::create($data);
        }

        return $contact;
    }

    public function delete($id)
    {
        return Contact::whereId($id)->delete();
    }
}
