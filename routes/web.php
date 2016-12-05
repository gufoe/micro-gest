<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// Sessions
$app->group(['middleware' => 'ajax'], function () use ($app) {
    $app->post('sessions', 'SessionController@login');
    $app->delete('sessions', 'SessionController@logout');

    // Users
    $app->get('users', 'UserController@list');
    $app->post('users', 'UserController@signup');
    $app->post('users/self', 'UserController@account');
    $app->get('users/self', 'UserController@self');

    // Contacts
    $app->get('contacts', 'ContactController@list');
    $app->post('contacts[/{id}]', 'ContactController@edit');
    $app->delete('contacts/{id}', 'ContactController@delete');

    // Groups
    $app->get('groups', 'GroupController@list');
    $app->get('groups/{id}', 'GroupController@single');
    $app->post('groups[/{id}]', 'GroupController@edit');
    $app->post('groups/{id}/contacts', 'GroupController@setContacts');
    $app->delete('groups/{id}', 'GroupController@delete');

    // Group Emails
    $app->get('groups-emails', 'GroupEmailController@list');
    $app->post('groups-emails', 'GroupEmailController@queue');
});

// Any other requests is sent to the frontend
$app->get('{path:.*}', function () use ($app) {
    return file_get_contents(base_path().'/public/app.html');
});
