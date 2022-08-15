{!! Form::open(['route' => ['lesson.save', ['id' => $lesson->id, 'translate-lang' => $translateLang]], 'enctype' => 'multipart/form-data']) !!}

<div class="form-group">
    {!! Form::label('Название') !!}
    {!! Form::text('title', $lesson->title, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Порядковый номер') !!}
    {!! Form::text('priority', $lesson->priority, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Статус') !!}
    {!! Form::select('status', Modules\ActivityStatus\Facades\ActivityStatusFacade::statuses(), $lesson->status, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit('Сохранить', ['class' => 'btn btn-success']) !!}
</div>

{!! Form::close() !!}
