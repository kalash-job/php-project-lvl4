@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('tm.Updating label') }}</h1>
    {{ Form::model($label, ['method' => 'PATCH', 'url' => route('labels.update', $label), 'class' => 'w-50']) }}
    @include('label.form')
    {{ Form::submit(__('tm.BtnUpdate'), ['class' => ['btn', 'btn-primary']]) }}
    {{ Form::close() }}
@endsection
