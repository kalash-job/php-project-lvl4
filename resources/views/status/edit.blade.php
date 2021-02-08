@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('tm.Updating status') }}</h1>
    {{ Form::model($status, ['method' => 'PATCH', 'url' => route('task_statuses.update', $status), 'class' => 'w-50']) }}
    @include('status.form')
    {{ Form::submit(__('tm.Update'), ['class' => ['btn', 'btn-primary']]) }}
    {{ Form::close() }}
@endsection
