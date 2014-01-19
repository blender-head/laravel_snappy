@layout('layouts.default')

@section('content')
<div id="ask">
	<h1>Ask a Question</h1>

	@if(Auth::check())
		@if($errors->has())
			<p>The following errors have occurred:</p>

			<ul id="form-errors">
				{{ $errors->first('question', '<li>:message</li>') }}
			</ul>
		@endif

		{{ Form::open('ask', 'POST') }}

		{{ Form::token() }}

		<p>
			{{ Form::label('question', 'Question') }}<br />
			{{ Form::text('question', Input::old('question')) }}

			{{ Form::submit('Ask a Question') }}
		</p>

		{{ Form::close() }}
	@else
		<p>Please login to ask or answer questions.</p>
	@endif
</div><!-- end ask -->

<div id="questions">
	<h2>Unsolved Questions</h2>

	@if(!$questions->results)
		<p>No questions have been asked.</p>
	@else
		<ul>
			@foreach($questions->results as $question)
				<li>
					{{ HTML::link_to_route('question', Str::limit($question->question, 35), $question->id) }} 
					by {{ ucfirst($question->user->username) }} 
					({{ count($question->answers) }} {{ Str::plural("Answer", count($question->answers))}})
				</li>
			@endforeach
		</ul>

		{{ $questions->links() }}
	@endif
</div><!-- end questions -->
@endsection