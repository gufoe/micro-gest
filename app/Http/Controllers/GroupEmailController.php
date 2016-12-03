<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\GroupEmail;

class GroupEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(Request $request)
    {
        return GroupEmail::orderBy('id', 'desc')->with('group')->paginate(10);
    }

    public function queue(Request $request)
    {
        $data = [
            'group_id' => $request->input('group_id'),
            'subject'  => $request->input('subject'),
            'message'  => $request->input('message'),
        ];
        validate(GroupEmail::$rules, $data);

        \DB::beginTransaction();
        $ge = GroupEmail::create($data);
        $ge->queue();
        \DB::commit();

        return $ge;
    }
}
