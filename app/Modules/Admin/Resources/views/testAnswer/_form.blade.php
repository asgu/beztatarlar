{!! Form::open(['route' => ['test.answer.save', ['questionId' => $answer->question_id, 'id' => $answer->id, 'translate-lang' => $translateLang]], 'enctype' => 'multipart/form-data']) !!}

<div class="form-group">
    {!! Form::label('Вопрос:') !!}
    {{ $question->question }}
</div>

<div class="form-group">
    {!! Form::label('Ответ') !!}
    {!! Form::text('answer', $answer->answer, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Правильный') !!}
    <input type="checkbox" disabled="disabled" {{ $answer->is_correct ? "checked='checked'" : "" }}>
</div>

<div class="form-group">
    {!! Form::submit('Сохранить', ['class' => 'btn btn-success']) !!}
</div>

{!! Form::close() !!}
