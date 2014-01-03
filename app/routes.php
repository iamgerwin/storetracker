<?php

Route::get('/', function()
{
	if(Session::get('Login') === 'gerwin')
		return Redirect::to('hello');
	else
		return Redirect::to('in');
});

Route::get('in', function()
{
	Session::flush();
	return View::make('login');
});
Route::get('out',function()
{
	Session::flush();
	return Redirect::to('/');
});
Route::post('login', function()
{
	Session::flush();
	$d = DB::table('tbl_users')->where('username', '=', Input::get('username'))
	->where('password', '=', md5(Input::get('password')))->where('is_active',1)->count();
	$g = DB::table('tbl_users')->where('username', '=', Input::get('username'))
	->where('password', '=', md5(Input::get('password')))->where('is_active',1)->first();

	if($d == 1) {
		Session::put('account_id', $g->employeeid);
		Session::put('Login', 'gerwin');
		Session::put('Role',$g->role_id);
		return 'Login';
	} else
		return 'Invalid';
});

Route::controller('send', 'SendController');
Route::controller('receive','ReceiveController');

Route::group(array('before' => 'authger'), function()
{
	Route::resource('hello', 'HelloController');
		Route::get('helloTable', function() {	
		        $data = Inbox::orderBy('message_id','desc')->take(10)->get();
		        return View::make('layout.helloTable')->with('datas', $data);
		});
	
	Route::group(array('before' => 'isadmin'), function()
	{
		Route::post('invoice/items', array('as' => 'items', 'uses' => 'InvoiceController@postUpdateitems'));
		Route::post('invoice/summary', array('as' => 'summary', 'uses' => 'InvoiceController@postSummary'));
		Route::resource('invoice', 'InvoiceController');
	});

	Route::controller('sellout', 'SelloutController');
	Route::controller('inbox', 'InboxController');
	Route::controller('contacts','ContactsController');
	Route::controller('settings','SettingsController');

});


Route::controller('knock', 'KnockController');

/*
*FILTERS
*/
Route::filter('authger', function()
{
    // if ( Session::get('Login') != 'gerwin' )
    // {
    //     return Redirect::to('in');
    // }
    if ( Session::get('account_id') == NULL)
    {
    	return Redirect::to('in');
    }
});

Route::filter('isadmin', function()
{
	if (Session::get('Role')  != 1)
	{
		return App::abort(401, 'You are not authorized.');
	}
});

Route::filter('authip',function()
{
	if($_SERVER['REMOTE_ADDR'] == '122.52.163.202')
		return App::abort(401,'Not Authorized');
});