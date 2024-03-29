<?php

class Question extends Basemodel {

	public static $rules = array(
		'question'=>'required|min:10|max:255',
		'solved'=>'in:0,1'
	);

	// 'questions' table is belong to 'user' table
	public function user() 
	{
		return $this->belongs_to('User');
	}

	// 'questions' table has many 'answers'
	public function answers() 
	{
		return $this->has_many('Answer');
	}

	public static function unsolved() 
	{
		return static::where('solved','=',0)->order_by('id', 'DESC')->paginate(3);
	}

	public static function your_questions() 
	{
		return static::where('user_id','=',Auth::user()->id)->paginate(3);
	}

	public static function search($keyword) 
	{
		return static::where('question', 'LIKE', '%'.$keyword.'%')->paginate(3);
	}
}