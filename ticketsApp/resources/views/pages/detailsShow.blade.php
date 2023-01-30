@extends('default')

@section('content')
<div class="showEvents">
@foreach ($showDetail->response as $show)
        <div class="show"><a href="/booking/{{ $show->showId }}">{{ $show->date }}</div>
@endforeach
</div>
@stop