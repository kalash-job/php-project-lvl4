@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('tm.Updating task') }}</h1>
    {{ Form::model($task, ['method' => 'PATCH', 'url' => route('tasks.update', $task), 'class' => 'w-50']) }}
    @include('task.form')
    {{ Form::submit(__('tm.BtnUpdate'), ['class' => ['btn', 'btn-primary']]) }}
    {{ Form::close() }}
@endsection
