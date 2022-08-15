{!! Form::open(['route' => ['position.save', ['id' => $position->id, 'translate-lang' => $translateLang]], 'enctype' => 'multipart/form-data']) !!}

<div class="form-group">
    {!! Form::label('Должность') !!}
    {!! Form::text('title', $position->title, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Статус') !!}
    {!! Form::select('status', Modules\ActivityStatus\Facades\ActivityStatusFacade::statuses(), $position->status, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit('Сохранить', ['class' => 'btn btn-success']) !!}
</div>

{!! Form::close() !!}
