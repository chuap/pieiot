<?php
/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */
Route::get('checklogin', 'SystemController@userLogin');
Route::get('login', function() {
    return View::make('pages/login', array('mn' => ''));
});
Route::get('ac', 'AppController@doActionPost');
Route::post('ac', 'AppController@doActionPost');
//Route::get('hrdlogin', 'SystemController@hrdLogin');
if (Session::get('cat.islogin')) {
    Route::get('/', function() {
        return View::make('pages.pies');
    });
    Route::get('view', function() {
        return View::make('view');
    });
    Route::get('pro-{p}.info', function($p) {
        return View::make('pages.proinfo')->with('p', $p);
    });
    Route::get('admin/{p}', 'AdminController@main');
    Route::get('pie-{p}.{m}', 'HomeController@PieInfo');
    Route::get('pro-{pie}-{pro}.{m}', 'HomeController@ProInfo');
    Route::get('logout', 'SystemController@userLogout');

    

    
    Route::get('{b}/{m}', 'SystemController@carlist');
    Route::get('adminaction', 'AdminController@doActionPost');
    Route::get('postaction', 'AdminController@doActionPost');
    Route::post('adminaction', 'AdminController@doActionPost');
    Route::get('monitoraction', 'MonitorController@doAction');
    Route::post('uploadbook', function() {
        $upload_handler = new UploadBook();
    });
    Route::delete('uploadbook', function() {
        $upload_handler = new UploadBook();
    });
    Route::get('uploadbookXXX', function() {
        $upload_handler = new UploadBook();
    });
    Route::get('{p}', 'HomeController@main');
} else {
    Route::get('/', function() {
        return View::make('pages/login');
    });
    Route::get('{p}', function() {
        return View::make('pages/login');
    });
}








