@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('tm.Labels') }}</h1>
    @auth
        <a href="{{route('labels.create')}}" class="btn btn-primary">
            {{ __('tm.Create label') }}
        </a>
    @endauth
    <table class="table mt-2">
        <thead>
        <tr>
            <th>{{ __('tm.ID') }}</th>
            <th>{{ __('tm.Name') }}</th>
            <th>{{ __('tm.Description') }}</th>
            <th>{{ __('tm.Created') }}</th>
            @auth
                <th>{{ __('tm.Actions') }}</th>
            @endauth
        </tr>
        </thead>

        @if($labels->isNotEmpty())
            @foreach($labels as $label)
                <tr>
                    <td>{{$label->id}}</td>
                    <td>{{$label->name}}</td>
                    <td>{{$label->description ?? ''}}</td>
                    <td>{{$label->created_at->format('d.m.Y')}}</td>
                    @auth
                        <td>
                            <a
                                class="text-danger"
                                href="{{route('labels.destroy', $label)}}"
                                data-confirm="{{ __('tm.sure?') }}"
                                data-method="delete"
                                rel="nofollow"
                            >
                                {{ __('tm.Delete') }}
                            </a>
                            <a href="{{route('labels.edit', $label)}}">
                                {{ __('tm.Update') }}
                            </a>
                        </td>
                    @endauth
                </tr>
            @endforeach
        @endif
    </table>
@endsection
