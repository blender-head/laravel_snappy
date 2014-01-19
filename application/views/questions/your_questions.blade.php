@layout('layouts.default')

@section('content')
	<h1>{{ ucfirst($username) }} Questions</h1>

	@if(!$questions->results)
		<p>You've not posted any questions yet.</p>
	@else
		<ul>
			@foreach($questions->results as $question)
				<li>
					{{ Str::limit(e($question->question), 40) }} - 
					{{ ($question->solved) ? ("(Solved) - ") : ("") }}
					{{ HTML::link_to_route('edit_question', 'Edit', $question->id) }} - 
					{{ HTML::link_to_route('question', 'View', $question->id) }}
				</li>
			@endforeach
		</ul>

		{{ $questions->links() }}
	@endif
@endsection