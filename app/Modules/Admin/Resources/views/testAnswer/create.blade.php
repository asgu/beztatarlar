@extends('Admin::layout.adminlte.page')

@section('title', 'Добавить ответ')

@section('content_header')
    <h1 class="m-0 text-dark">Добавить ответ</h1>
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

                    @include('Admin::testAnswer._form')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
