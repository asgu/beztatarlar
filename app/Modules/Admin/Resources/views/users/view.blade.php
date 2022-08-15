@extends('Admin::layout.adminlte.page')

@section('title', 'Пользователь')

@section('content_header')
    <h1 class="m-0 text-dark">Пользователь - {{ \Modules\User\Facades\UserFacade::fullName($user) }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('user.index') }}">
                            Пользователи
                        </a>
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    <table class="table table-bordered" id="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <th>ФИО</th>
                            <td>{{ \Modules\User\Facades\UserFacade::fullName($user) }}</td>
                        </tr>
                        <tr>
                            <th>Почта</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Роль</th>
                            <td>{{ \Modules\User\Facades\UserFacade::roleLabel($user->role) }}</td>
                        </tr>
                        <tr>
                            <th>Дата рождения</th>
                            <td>{{ \Modules\User\Facades\UserFacade::birthday($user) }}</td>
                        </tr>
                        <tr>
                            <th>Пол</th>
                            <td>{{ \Modules\User\Facades\UserFacade::genderLabel($user) }}</td>
                        </tr>
                        <tr>
                            <th>Телефон</th>
                            <td>{{ \Modules\User\Facades\UserFacade::phone($user) }}</td>
                        </tr>
                        <tr>
                            <th>Должность</th>
                            <td>{{ \Modules\User\Facades\UserFacade::position($user) }}</td>
                        </tr>
                        <tr>
                            <th>Мухтасибат</th>
                            <td>{{ \Modules\User\Facades\UserFacade::mukhtasibat($user) }}</td>
                        </tr>
                        <tr>
                            <th>Приход</th>
                            <td>{{ \Modules\User\Facades\UserFacade::parish($user) }}</td>
                        </tr>
                        <tr>
                            <th>Аватар</th>
                            <td>
                                @if($user->photo)
                                    <img src="{{ $user->photo->getFullUrl() }}" alt="{{ $user->email }}"
                                         class="img-thumbnail" width="300">
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Пройденный курс</th>
                            <td>-</td>
                        </tr>
                        <tr>
                            <th>Пройденные уроки</th>
                            <td>-</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
