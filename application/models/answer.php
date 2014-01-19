<?php

class Answer extends Basemodel {

	public static $rules = array(
		'answer'=>'required|min:2|max:255'
	);

	public function user() {
		return $this->belongs_to('User');
	}

	public function question() {
		return $this->belongs_to('Question');
	}
}