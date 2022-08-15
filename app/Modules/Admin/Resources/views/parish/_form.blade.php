{!! Form::open(['route' => ['parish.save', ['id' => $parish->id, 'translate-lang' => $translateLang]], 'enctype' => 'multipart/form-data']) !!}

<div class="form-group">
    {!! Form::label('Наименование') !!}
    {!! Form::text('title', $parish->title, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Статус') !!}
    {!! Form::select('status', Modules\ActivityStatus\Facades\ActivityStatusFacade::statuses(), $parish->status, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit('Сохранить', ['class' => 'btn btn-success']) !!}
</div>

{!! Form::close() !!}
