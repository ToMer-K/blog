@extends('app')

@section('title')
Analytics
@endsection

@section('content')
	<div class="list-group">
		@foreach( $tagsWordsArr as $tag => $wordsArr )
			<div class="list-group-item">
				<h3>{{$tag}}</h3>
				@foreach( $wordsArr as $word => $count )
				<div class="list-group-item">
					<span id="statsword">{{$word}}</span>{{$count}}
				</div>
				@endforeach
			</div>
		@endforeach
	</div>
@endsection