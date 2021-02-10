@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('tm.Create task') }}</h1>
    {{ Form::model($task, ['url' => route('tasks.store'), 'class' => 'w-50']) }}
    @include('task.form')
    {{ Form::submit(__('tm.Create'), ['class' => ['btn', 'btn-primary']]) }}
    {{ Form::close() }}
@endsection
