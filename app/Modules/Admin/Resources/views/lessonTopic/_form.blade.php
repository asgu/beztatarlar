{!! Form::open(['route' => ['lessonTopic.save', ['lessonId' => $lessonTopic->lesson_id, 'id' => $lessonTopic->id, 'translate-lang' => $translateLang]], 'enctype' => 'multipart/form-data']) !!}

<div class="form-group">
    {!! Form::label('Название') !!}
    {!! Form::text('title', $lessonTopic->title, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Название видео') !!}
    {!! Form::text('video_title', $lessonTopic->video_title, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Ссылка на видео') !!}
    {!! Form::text('video_url', $lessonTopic->video_url, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Заголовок текста') !!}
    {!! Form::text('content_title', $lessonTopic->content_title, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Текст') !!}
    {!! Form::textArea('content_text', $lessonTopic->content_text, ['class' => 'form-control ckeditor', 'id' => 'text_content_block']) !!}
</div>

<div class="form-group">
    {!! Form::label('Заголовок аудио') !!}
    {!! Form::text('audio_title', $lessonTopic->audio_title, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Описание аудио') !!}
    {!! Form::textArea('audio_description', $lessonTopic->audio_description, ['class' => 'form-control']) !!}
</div>

<div class="form-group image-block">
    @if(!empty($lessonTopic->audio))
        {{ $lessonTopic->audio->file_name }}
    @endif
</div>
<div class="form-group">
    {!! Form::label('Аудио') !!}
    {!! Form::file('audio', ['class' => 'form-control', 'accept' => 'audio/*']) !!}
</div>

<div class="form-group">
    {!! Form::label('Порядковый номер') !!}
    {!! Form::text('priority', $lessonTopic->priority, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Таймер') !!}
    {!! Form::text('timer', $lessonTopic->timer, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Статус') !!}
    {!! Form::select('status', Modules\ActivityStatus\Facades\ActivityStatusFacade::statuses(), $lessonTopic->status, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit('Сохранить', ['class' => 'btn btn-success']) !!}
</div>

{!! Form::close() !!}

<script src="//cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'text_content_block', {
        language: 'en',
        filebrowserUploadUrl: "{!! route('admin.file.upload', ['_token' => csrf_token() ]) !!}",
        filebrowserUploadMethod: 'form'
    });
</script>
