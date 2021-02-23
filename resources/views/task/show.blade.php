@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('tm.Show task') }}: {{ $task->name }}
        <a href="{{route('tasks.edit', $task)}}">&#9881;</a>
    </h1>
    <p>{{ __('tm.Name') }}: {{ $task->name }}</p>
    <p>{{ __('tm.Status') }}: {{ $task->status->name }}</p>
    <p>{{ __('tm.Description') }}: {{ $task->description }}</p>
    @if($task->labels->isNotEmpty())
        <p>{{ __('tm.Labels') }}:</p>
        <ul>
            @foreach($task->labels as $label)
                <li>{{$label->name}}</li>
            @endforeach
        </ul>
    @endif
@endsection
