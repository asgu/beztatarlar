@extends('Admin::layout.adminlte.page')

@section('title', 'Редактировать топик')

@section('content_header')
    <h1 class="m-0 text-dark">Редактировать топик - {{$lessonTopic->title}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('lesson.view', $lessonTopic->lesson_id) }}">
                            Топики
                        </a>
                        <a class="btn btn-default" href="{{ route('lessonTopic.view', ['lessonId' => $lessonTopic->lesson_id, 'id' => $lessonTopic->id]) }}">
                            Просмотр
                        </a>
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    @include('Admin::includes.translate_buttons_params', ['route' => 'lessonTopic', 'params' => ['id' => $lessonTopic->id, 'lessonId' => $lessonTopic->lesson_id]])
                    @include('Admin::lessonTopic._form')
                </div>
            </div>
        </div>
    </div>
@stop
