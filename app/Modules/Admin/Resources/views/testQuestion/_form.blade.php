
<div class="form-group">
    {!! Form::label('Номер вопроса') !!}
    {!! Form::text('link[priority]', $question->link ? $question->link->priority : 0, ['class' => 'form-control', 'rows' => 2]) !!}
</div>
<div class="form-group">
    {!! Form::label('Вопрос') !!}
    {!! Form::text('question', $question->question, ['class' => 'form-control']) !!}
</div>




