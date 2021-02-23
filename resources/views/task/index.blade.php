@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('tm.Tasks') }}</h1>

    <div class="d-flex">
        <div>
            {{ Form::open(['method' => 'GET', 'url' => route('tasks.index'), 'class' => 'form-inline', 'accept-charset' => 'UTF-8']) }}

            {{ Form::select('filter[status_id]', $statuses, $filter['status_id'] ?? null,
            ['class' => ['form-control', 'mr-2'], 'placeholder' => __('tm.Status')]) }}

            {{ Form::select('filter[created_by_id]', $creators, $filter['created_by_id'] ?? null,
            ['class' => ['form-control', 'mr-2'], 'placeholder' => __('tm.Creator')]) }}

            {{ Form::select('filter[assigned_to_id]', $workers, $filter['filter[assigned_to_id]'] ?? null,
            ['class' => ['form-control', 'mr-2'], 'placeholder' => __('tm.Worker')]) }}

            {{ Form::submit(__('tm.Apply'), ['class' => ['btn', 'btn-outline-primary', 'mr-2']]) }}
            {{ Form::close() }}
        </div>
        @auth
            <a href="{{route('tasks.create')}}" class="btn btn-primary ml-auto">
                {{ __('tm.Create task') }}
            </a>
        @endauth
    </div>

    <table class="table mt-2">
        <thead>
        <tr>
            <th>{{ __('tm.ID') }}</th>
            <th>{{ __('tm.Status') }}</th>
            <th>{{ __('tm.Name') }}</th>
            <th>{{ __('tm.Creator') }}</th>
            <th>{{ __('tm.Worker') }}</th>
            <th>{{ __('tm.Created') }}</th>
            @auth
                <th>{{ __('tm.Actions') }}</th>
            @endauth
        </tr>
        </thead>

        @if($tasks->isNotEmpty())
            @foreach($tasks as $task)
                <tr>
                    <td>{{$task->id}}</td>
                    <td>{{$task->status->name}}</td>
                    <td><a href="{{route('tasks.show', $task)}}">{{$task->name}}</a></td>
                    <td>{{$task->creator->name}}</td>
                    <td>{{$task->worker->name ?? ''}}</td>
                    <td>{{$task->created_at->format('d.m.Y')}}</td>
                    @auth
                        <td>
                            @can('delete', $task)
                                <a
                                    class="text-danger"
                                    href="{{route('tasks.destroy', $task)}}"
                                    data-confirm="{{ __('tm.sure?') }}"
                                    data-method="delete"
                                    rel="nofollow"
                                >
                                    {{ __('tm.Delete') }}
                                </a>
                            @endcan
                            <a href="{{route('tasks.edit', $task)}}">
                                {{ __('tm.Update') }}
                            </a>
                        </td>
                    @endauth
                </tr>
            @endforeach
        @endif
    </table>
@endsection
