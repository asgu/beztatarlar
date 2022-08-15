{!! Form::open(['route' => ['teacher.save', ['id' => $teacher->id, 'translate-lang' => $translateLang]], 'enctype' => 'multipart/form-data']) !!}

<div class="form-group">
    {!! Form::label('ФИО') !!}
    {!! Form::text('name', $teacher->name, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Должность') !!}
    {!! Form::textArea('description', $teacher->description, ['class' => 'form-control', 'rows' => 2]) !!}
</div>
<div class="form-group">
    {!! Form::label('Статус') !!}
    {!! Form::select('status', Modules\ActivityStatus\Facades\ActivityStatusFacade::statuses(), $teacher->status, ['class' => 'form-control']) !!}
</div>
<div class="form-group image-block">
    @if(!empty($teacher->photo))
        <img
            src="{!! $teacher->photo->getFullUrl() !!}"
            alt="{!! $teacher->photo->file_name !!}"
            class="img-thumbnail"
            data-id="{{ $teacher->photo->id }}"
        >
    @endif
</div>
<div class="form-group">
    {!! Form::label('Фото') !!}
    {!! Form::file('photo', ['class' => 'form-control', 'accept' => 'image/*']) !!}
</div>
<div class="form-group">
    {!! Form::submit('Сохранить', ['class' => 'btn btn-success']) !!}
</div>

{!! Form::close() !!}
