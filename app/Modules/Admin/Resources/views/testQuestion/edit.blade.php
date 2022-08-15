@extends('Admin::layout.adminlte.page')

@section('title', 'Редактировать вопрос')

@section('content_header')
    <h1 class="m-0 text-dark">Редактировать вопрос - {{$question->question}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('test.view', ['id' => $testId]) }}">
                            Тест
                        </a>
                        <a class="btn btn-default" href="{{ route('test.question.view', ['testId' => $testId, 'id' => $question->id]) }}">
                            Просмотр
                        </a>
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    @include('Admin::includes.translate_buttons_params', ['route' => 'test.question', 'params' => ['testId' => $testId, 'id' => $question->id]])

                    {!! Form::open(['route' => ['test.question.update', ['testId' => $testId, 'id' => $question->id, 'translate-lang' => $translateLang]], 'enctype' => 'multipart/form-data']) !!}
                    @include('Admin::testQuestion._form')
                    <div class="form-group">
                        {!! Form::submit('Сохранить', ['class' => 'btn btn-success']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
