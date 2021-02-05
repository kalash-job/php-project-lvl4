@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('tm.Statuses') }}</h1>
    <a href="{{route('task_statuses.create')}}" class="btn btn-primary">
        {{ __('tm.Create status') }}
    </a>
    <table class="table mt-2">
        <thead>
        <tr>
            <th>{{ __('tm.ID') }}</th>
            <th>{{ __('tm.Name') }}</th>
            <th>{{ __('tm.Created') }}</th>
            <th>{{ __('tm.Actions') }}</th>
        </tr>
        </thead>

        @if($statuses->isNotEmpty())
            @foreach($statuses as $status)
                <tr>
                    <td>{{$status->id}}</td>
                    <td>{{$status->name}}</td>
                    <td>{{$status->created_at->format('d.m.Y')}}</td>
                    <td>
                        <a
                            class="text-danger"
                            href="{{route('task_statuses.destroy', $status)}}"
                            data-confirm="{{ __('tm.sure?') }}"
                            data-method="delete"
                        >
                            {{ __('tm.Delete') }}
                        </a>
                        <a href="{{route('task_statuses.edit', $status)}}">
                            {{ __('tm.Update') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </table>
@endsection
