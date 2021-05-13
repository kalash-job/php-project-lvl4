@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('tm.Statuses') }}</h1>
    @auth
        <a href="{{route('task_statuses.create')}}" class="btn btn-primary">
            {{ __('tm.Create status') }}
        </a>
    @endauth
    <table class="table mt-2">
        <thead>
        <tr>
            <th>{{ __('tm.ID') }}</th>
            <th>{{ __('tm.Name') }}</th>
            <th>{{ __('tm.Created') }}</th>
            @auth
                <th>{{ __('tm.Actions') }}</th>
            @endauth
        </tr>
        </thead>

        @if($statuses->isNotEmpty())
            @foreach($statuses as $status)
                <tr>
                    <td>{{$status->id}}</td>
                    <td>{{$status->name}}</td>
                    <td>{{$status->created_at->format('d.m.Y')}}</td>
                    @auth
                        <td>
                            <a
                                class="text-danger"
                                href="{{route('task_statuses.destroy', $status)}}"
                                data-confirm="{{ __('tm.sure?') }}"
                                data-method="delete"
                                rel="nofollow"
                            >
                                {{ __('tm.Delete') }}
                            </a>
                            <a href="{{route('task_statuses.edit', $status)}}">
                                {{ __('tm.Update') }}
                            </a>
                        </td>
                    @endauth
                </tr>
            @endforeach
        @endif
    </table>
    {{ $statuses->links() }}
@endsection
