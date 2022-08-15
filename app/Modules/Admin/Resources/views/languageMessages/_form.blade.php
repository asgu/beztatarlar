{!! Form::open(['route' => ['languageMessages.save', ['id' => $model->id]], 'method' => 'patch', 'enctype' => 'multipart/form-data']) !!}

<div class="form-group">
    {!! Form::label('Language') !!}
    {!! Form::select('code', $languages, $model->code, ['class' => 'form-control select2']) !!}
</div>
<div class="form-group">
    {!! Form::label('Section') !!}
    {!! Form::select('type', \Modules\Language\Helpers\LanguageHelper::getLanguageMessageTypesLabels(), $model->type, ['class' => 'form-control select2']) !!}
</div>

<div class="form-group">
    {!! Form::label('Translation') !!}
    {!! Form::textarea('message_values', \App\Support\JsonSupport::encode($model->message_values ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), ['class' => 'form-control', "rows" => 20]) !!}
</div>
<div class="form-group">
    {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}
</div>

{!! Form::close() !!}
