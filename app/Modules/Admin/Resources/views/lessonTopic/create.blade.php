@extends('Admin::layout.adminlte.page')

@section('title', 'Добавить топик')

@section('content_header')
    <h1 class="m-0 text-dark">Добавить топик</h1>
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
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    @include('Admin::lessonTopic._form')
                </div>
            </div>
        </div>
    </div>
@stop
