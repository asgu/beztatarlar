@extends('Admin::layout.adminlte.page')

@section('title', 'Учитель')

@section('content_header')
    <h1 class="m-0 text-dark">Учитель - {{ $teacher->name }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('Admin::includes.translate_buttons_params_view', ['route' => 'teacher', 'params' => ['teacher' => $teacher->id]])
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('teacher.index') }}">
                            Учителя
                        </a>
                        <a class="btn btn-success" href="{{ route('teacher.edit', $teacher->id) }}">
                            Редактировать
                        </a>
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    <table class="table table-bordered" id="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <td>{{ $teacher->id }}</td>
                        </tr>
                        <tr>
                            <th>ФИО</th>
                            <td>{{ $teacher->name }}</td>
                        </tr>
                        <tr>
                            <th>Должность</th>
                            <td>{{ $teacher->description }}</td>
                        </tr>
                        <tr>
                            <th>Фото</th>
                            <td>
                                @if($teacher->photo)
                                    <img src="{{ $teacher->photo->getFullUrl() }}" alt="{{ $teacher->name }}"
                                         class="img-thumbnail" width="300">
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Статус</th>
                            <td>{{ Modules\ActivityStatus\Facades\ActivityStatusFacade::statusLabel($teacher->status) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
