@extends('Admin::layout.adminlte.page')

@section('title', 'Редактировать учителя')

@section('content_header')
    <h1 class="m-0 text-dark">Редактировать учителя - {{$teacher->name}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('teacher.index') }}">
                            Учителя
                        </a>
                        <a class="btn btn-default" href="{{ route('teacher.view', $teacher->id) }}">
                            Просмотр
                        </a>
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    @include('Admin::includes.translate_buttons', ['model' => 'teacher', 'route' => 'teacher', 'id' => $teacher->id])
                    @include('Admin::teacher._form')
                </div>
            </div>
        </div>
    </div>
@stop
