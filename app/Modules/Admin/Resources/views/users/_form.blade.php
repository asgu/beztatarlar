{!! Form::open(['route' => ['user.save', ['id' => $user->id]], 'enctype' => 'multipart/form-data']) !!}

<div class="form-group">
    {!! Form::label('Имя') !!}
    {!! Form::text('name', $user->name, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Телефон') !!}
    {!! Form::text('phone', $user->phone, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Email') !!}
    {!! Form::text('email', $user->email, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Пароль') !!}
    {!! Form::text('password', '', ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::submit('Сохранить', ['class' => 'btn btn-success']) !!}
</div>

{!! Form::close() !!}
