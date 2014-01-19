<?php

class Questions_Controller extends Base_Controller {

	public $restful = true;

	public function __construct() 
	{
		$this->filter('before', 'auth')->only(array('create', 'your_questions', 'edit', 'update'));
	}

	public function get_index() 
	{
		return View::make('questions.index')
			->with('title', 'Make It Snappy Q&A - Home')
			->with('questions', Question::unsolved());
	}

	public function post_create() 
	{
		$validation = Question::validate(Input::all());

		if($validation->passes()) 
		{
			Question::create(array(
				'question'=>Input::get('question'),
				'user_id'=>Auth::user()->id
			));

			return Redirect::to_route('home')->with('message', 'Your question has been posted!');
		} 
		else 
		{
			return Redirect::to_route('home')->with_errors($validation)->with_input();
		}
	}

	public function get_view($id = null) 
	{
		return View::make('questions.view')
			->with('title', 'Make It Snappy - View Question')
			->with('question', Question::find($id));
	}

	public function get_your_questions() 
	{
		return View::make('questions.your_questions')
			->with('title', 'Make It Snappy Q&A - Your Questions')
			->with('username', Auth::user()->username)
			->with('questions', Question::your_questions());
	}

	public function get_edit($id = NULL) 
	{
		if (!$this->question_belongs_to_user($id)) 
		{
			return Redirect::to_route('your_questions')->with('message', 'Invalid Question');
		}

		return View::make('questions.edit')
			->with('title', 'Make It Snappy Q&A - Edit')
			->with('question', Question::find($id));
	}

	public function put_update() 
	{
		$id = Input::get('question_id');

		if (!$this->question_belongs_to_user($id)) 
		{
			return Redirect::to_route('your_questions')->with('message', 'Invalid Question');
		}

		$validation = Question::validate(Input::all());

		if ($validation->passes()) 
		{
			Question::update($id, array(
				'question'=>Input::get('question'),
				'solved'=>Input::get('solved')
			));

			return Redirect::to_route('question', $id)
				->with('message', 'Your question has been updated!');
		} 
		else 
		{
			return Redirect::to_route('edit_question', $id)->with_errors($validation);
		}
	}

	public function get_results($keyword) 
	{
		return View::make('questions.results')
			->with('title', 'Make It Snappy Q&A - Search Results')
			->with('questions', Question::search($keyword));
	}

	public function post_search() 
	{
		$keyword = Input::get('keyword');

		if (empty($keyword)) 
		{
			return Redirect::to_route('home')
				->with('message', 'No keyword entered, please try again');
		}

		return Redirect::to('results/'.$keyword);
	}

	private function question_belongs_to_user($id) 
	{
		$question = Question::find($id);

		if ($question->user_id == Auth::user()->id) 
		{
			return true;
		}

		return false;
	}
}