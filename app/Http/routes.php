<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function()
{
    if (Auth::check()) return 'Welcome back, ' . Auth::user()->username . ' ' . link_to('logout', 'Logout!');

    return 'Hi guest. ' . link_to('login/github', 'Login with Github!') . link_to('login/twitter', 'Login with twitter!');
});

Route::get('login/{provider}', 'AuthController@login');

Route::get('logout', function(){
    Auth::logout();
    return redirect('/home');
});
