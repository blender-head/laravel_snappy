<?php

class Users_Controller extends Base_Controller {

	public $restful = true;

	// display register form
	public function get_new() 
	{
		return View::make('users.new')
			->with('title', 'Make It Snappy Q&A - Register');
	}

	// create new user
	public function post_create() 
	{
		$validation = User::validate(Input::all());

		if ($validation->passes()) 
		{
			User::create(array(
				'username'=>Input::get('username'),
				'password'=>Hash::make(Input::get('password'))
			));

			$user = User::where_username(Input::get('username'))->first();

			// log in the newly created user
			Auth::login($user);

			return Redirect::to_route('home')
				->with('message', 'Thanks for registering. Your are now logged in!');
		} 
		else 
		{
			return Redirect::to_route('register')->with_errors($validation)->with_input();
		}
	}


	// display login form
	public function get_login() 
	{
		return View::make('users.login')
			->with('title', 'Make It Snappy Q&A - Login');
	}

	// user login process
	public function post_login() 
	{
		$user = array(
			'username'=>Input::get('username'),
			'password'=>Input::get('password')
		);

		if (Auth::attempt($user)) 
		{
			return Redirect::to_route('home')->with('message', 'You are logged in!');
		} 
		else 
		{
			return Redirect::to_route('login')
				->with('message', 'Your username/password combination was incorrect')
				->with_input();
		}
	}

	// user logout process
	public function get_logout() 
	{
		if (Auth::check()) 
		{
			Auth::logout();
			return Redirect::to_route('login')->with('message', 'Your are now logged out!');
		} 
		else 
		{
			return Redirect::to_route('home');
		}
	}
}