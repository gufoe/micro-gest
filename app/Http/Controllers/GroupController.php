<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function single($id)
    {
        return Group::whereId($id)->with('contacts')->first();
    }

    public function setContacts(Request $request, $id)
    {
        return Group::findOrFail($id)
            ->contacts()
            ->sync((array) $request->input('contacts'));
    }

    public function list(Request $request)
    {
        $query = Group::limit(1000);
        if (($s = $request->input('name'))) {
            $query->where('name', 'like', "%$s%");
        }
        return $query->paginate(10);
    }

    public function edit(Request $request, $id = null)
    {
        $group = Group::find($id);

        $data = [
            'name'  => $request->input('name'),
        ];
        validate(Group::$rules, $data);

        if ($group) {
            $group->update($data);
        } else {
            $group = Group::create($data);
        }

        return $group;
    }

    public function delete($id)
    {
        return Group::whereId($id)->delete();
    }
}
