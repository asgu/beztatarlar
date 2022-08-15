{!! Form::open(['route' => ['test.save', ['id' => $test->id, 'translate-lang' => $translateLang]], 'enctype' => 'multipart/form-data']) !!}

<div class="form-group">
    {!! Form::label('Урок') !!}
    {!! Form::select('lesson[lesson_id]', $lessons, $test->activeLessonTest ? $test->activeLessonTest->lesson_id : null, ['class' => 'form-control select2']) !!}
</div>
<div class="form-group">
    {!! Form::label('Название') !!}
    {!! Form::text('title', $test->title, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Описание') !!}
    {!! Form::textArea('description', $test->description, ['class' => 'form-control', 'rows' => 2]) !!}
</div>
<div class="form-group">
    {!! Form::label('Таймер') !!}
    {!! Form::text('timer', $test->timer, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Статус') !!}
    {!! Form::select('status', Modules\ActivityStatus\Facades\ActivityStatusFacade::statuses(), $test->status, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit('Сохранить', ['class' => 'btn btn-success']) !!}
</div>

{!! Form::close() !!}
