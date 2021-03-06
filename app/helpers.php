<?php

function public_path($path = '')
{
    return  base_path().'/public'.$path;
}

function success($data = [], $value = null)
{
    if (is_string($data)) {
        $data = [$data => $value];
    }
    return response()->json([
        'success' => true,
    ] + (array) $data);
}
function error($error = null, $data = [], $code = 400)
{
    return response()->json([
        'success' => false,
        'error' => $error,
    ] + $data, $code);
}

function validate($rules, $data, $kill = true)
{
    $validator = \Validator::make($data, $rules);
    if ($validator->fails()) {
        $res = error(collect($validator->errors()->all())->implode(' '), [
            'validator' => $validator->errors(),
        ]);
        if ($kill) {
            $res->send();
        } else {
            return $res;
        }
    }
    return null;
}

function user()
{
    return \Auth::user();
}

function get_mime_type($file)
{
    $mime_types = [
        'pdf' => 'application/pdf'
        ,'exe' => 'application/octet-stream'
        ,'zip' => 'application/zip'
        ,'docx' => 'application/msword'
        ,'doc' => 'application/msword'
        ,'xls' => 'application/vnd.ms-excel'
        ,'ppt' => 'application/vnd.ms-powerpoint'
        ,'gif' => 'image/gif'
        ,'png' => 'image/png'
        ,'jpeg' => 'image/jpg'
        ,'jpg' => 'image/jpg'
        ,'mp3' => 'audio/mpeg'
        ,'wav' => 'audio/x-wav'
        ,'mpeg' => 'video/mpeg'
        ,'mpg' => 'video/mpeg'
        ,'mpe' => 'video/mpeg'
        ,'mov' => 'video/quicktime'
        ,'avi' => 'video/x-msvideo'
        ,'3gp' => 'video/3gpp'
        ,'css' => 'text/css'
        ,'jsc' => 'application/javascript'
        ,'js' => 'application/javascript'
        ,'php' => 'text/html'
        ,'htm' => 'text/html'
        ,'html' => 'text/html',
    ];
    $ext = explode('.', $file);
    $extension = strtolower(end($ext));
    return (string) @$mime_types[$extension];
}

function linkify($text)
{
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $text);
}

function format($str)
{
    return '<p>'.str_replace("\n", "</p><p>\n", e($str)).'</p>';
}

function notify($to, $name, $subject, $body, $link = null)
{
    $vars = [
        'subject' => $subject,
        'name'    => $name,
        'body'    => $body,
        'link'    => $link,
    ];

    try {
        return Illuminate\Support\Facades\Mail::send('email', $vars, function ($msg) use ($subject, $to, $name) {
            $msg->subject($subject);
            $msg->to($to, $name);
        });
    } catch (Exception $e) {
        return false;
    }
}
