{!! Form::open(['route' => ['mukhtasibat.save', ['id' => $mukhtasibat->id, 'translate-lang' => $translateLang]], 'enctype' => 'multipart/form-data']) !!}

<div class="form-group">
    {!! Form::label('Мухтасибат') !!}
    {!! Form::text('title', $mukhtasibat->title, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Статус') !!}
    {!! Form::select('status', Modules\ActivityStatus\Facades\ActivityStatusFacade::statuses(), $mukhtasibat->status, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit('Сохранить', ['class' => 'btn btn-success']) !!}
</div>

{!! Form::close() !!}
