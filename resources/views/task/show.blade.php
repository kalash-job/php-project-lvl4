@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('tm.Show task') }}: {{ $task->id }}
        <a href="{{route('tasks.edit', $task)}}">&#9881;</a>
    </h1>
    <p>{{ __('tm.Name') }}: {{ $task->id }}</p>
    <p>{{ __('tm.Status') }}: {{ $task->status->name }}</p>
    <p>{{ __('tm.Description') }}: {{ $task->description }}</p>
@endsection
