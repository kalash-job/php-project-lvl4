@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('tm.Create status') }}</h1>
    {{ Form::model($status, ['url' => route('task_statuses.store'), 'class' => 'w-50']) }}
    @include('status.form')
        {{ Form::submit(__('tm.Create'), ['class' => ['btn', 'btn-primary']]) }}
    {{ Form::close() }}
@endsection
