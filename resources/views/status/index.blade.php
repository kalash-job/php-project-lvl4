@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Статусы</h1>
    <a href="{{route('task_statuses.create')}}" class="btn btn-primary">
        Создать статус
    </a>
    <table class="table mt-2">
        <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Дата создания</th>
            <th>Действия</th>
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
                            data-confirm="Вы уверены?"
                            data-method="delete"
                        >
                            Удалить
                        </a>
                        <a href="{{route('task_statuses.edit', $status)}}">
                            Изменить
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </table>
@endsection
