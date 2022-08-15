@extends('Admin::layout.adminlte.page')

@section('title', 'Редактировать ответ')

@section('content_header')
    <h1 class="m-0 text-dark">Редактировать ответ - {{$answer->answer}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('test.question.view', ['testId' => $question->link->test_id, 'id' => $answer->question_id]) }}">
                            Ответы
                        </a>
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    @include('Admin::includes.translate_buttons_params', ['route' => 'test.answer', 'params' => ['questionId' => $answer->question_id, 'id' => $answer->id]])

                    @include('Admin::testAnswer._form')

                </div>
            </div>
        </div>
    </div>
@stop
