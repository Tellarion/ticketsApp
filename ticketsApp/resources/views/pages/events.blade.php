@extends('default')

@section('content')
<div class="showEvents">
@foreach ($shows->response as $show)
        <div class="show"><a href="/show/{{ $show->id }}">{{ $show->name }}</div>
@endforeach
</div>
@stop