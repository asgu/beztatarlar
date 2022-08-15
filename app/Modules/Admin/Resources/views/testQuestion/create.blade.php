@extends('Admin::layout.adminlte.page')

@section('title', 'Добавить вопрос')

@section('content_header')
    <h1 class="m-0 text-dark">Добавить вопрос</h1>
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
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    {!! Form::open(['route' => ['test.question.store', ['testId' => $testId, 'translate-lang' => $translateLang]], 'enctype' => 'multipart/form-data']) !!}

                    @include('Admin::testQuestion._form')

                    <div class="form-group">
                        {!! Form::label('Правильный ответ') !!}
                        {!! Form::text('answer[answer]', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::submit('Сохранить', ['class' => 'btn btn-success']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
