@extends('Admin::layout.adminlte.page')

@section('title', 'Топик')

@section('content_header')
    <h1 class="m-0 text-dark">Топик - {{ $lessonTopic->title }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('Admin::includes.translate_buttons_params_view', ['route' => 'lessonTopic', 'params' => ['lessonId' => $lessonId, 'id' => $lessonTopic->id]])
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('lesson.view', $lessonTopic->lesson_id) }}">
                            Топики
                        </a>
                        <a class="btn btn-success" href="{{ route('lessonTopic.edit', ['lessonId' => $lessonTopic->lesson_id, 'id' => $lessonTopic->id]) }}">
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
                            <td>{{ $lessonTopic->id }}</td>
                        </tr>
                        <tr>
                            <th>Название</th>
                            <td>{{ $lessonTopic->title }}</td>
                        </tr>
                        <tr>
                            <th>Порядковый номер</th>
                            <td>{{ $lessonTopic->priority }}</td>
                        </tr>
                        <tr>
                            <th>Статус</th>
                            <td>{{ Modules\ActivityStatus\Facades\ActivityStatusFacade::statusLabel($lessonTopic->status) }}</td>
                        </tr>
                        <tr>
                            <th>Заголовок видео</th>
                            <td>{{ $lessonTopic->video_title }}</td>
                        </tr>
                        <tr>
                            <th>Ссылка на видео</th>
                            <td>{{ $lessonTopic->video_url }}</td>
                        </tr>
                        <tr>
                            <th>Заголовок текста</th>
                            <td>{{ $lessonTopic->content_title }}</td>
                        </tr>
                        <tr>
                            <th>Текст</th>
                            <td>{!! $lessonTopic->content_text !!}</td>
                        </tr>
                        <tr>
                            <th>Заголовок аудио</th>
                            <td>{{ $lessonTopic->audio_title }}</td>
                        </tr>
                        <tr>
                            <th>Описание аудио</th>
                            <td>{{ $lessonTopic->audio_description }}</td>
                        </tr>
                        <tr>
                            <th>Аудио файл</th>
                            <td>
                                @if (isset($lessonTopic->audio))
                                    {{ $lessonTopic->audio->file_name }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Таймер</th>
                            <td>{{ $lessonTopic->timer }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
