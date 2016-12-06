<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function getDownload($token)
    {
        $file = \App\File::where('token', $token)->firstOrFail();
        $headers = $file->extension == 'pdf' ? [
            'Content-Type: application/pdf',
        ] : [];
        return response()->download($file->path($token), $file->safeName, $headers);
    }
}
