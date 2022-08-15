@extends('Admin::layout.adminlte.page')

@section('title', 'Пользователи')

@section('content_header')
    <h1 class="m-0 text-dark">Пользователи</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('Admin::includes.success')

                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th class="id_column">ID</th>
                                <th>Фамилия</th>
                                <th>Имя</th>
                                <th>Отчество</th>
                                <th>Email</th>
                                <th>Роль</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td>
                                    {!! Form::text('id', null, ['class' => 'form-control', 'placeholder' => 'ID']) !!}
                                </td>
                                <td>
                                    {!! Form::text('surname', null, ['class' => 'form-control', 'placeholder' => 'Фамилия']) !!}
                                </td>
                                <td>
                                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Имя']) !!}
                                </td>
                                <td>
                                    {!! Form::text('patronymic', null, ['class' => 'form-control', 'placeholder' => 'Отчество']) !!}
                                </td>
                                <td>
                                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                                </td>
                                <td>
                                    {!! Form::select('role', Modules\User\Facades\UserFacade::roles(), null, ['class' => 'form-control', 'placeholder' => 'Выберите роль']) !!}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function () {
            $('#table').DataTable({
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                searching: true,
                ajax: '{{ route('user.data') }}',
                columns: [
                    {data: 'id', name: 'users.id'},
                    {data: 'profile.surname', name: 'profile.surname'},
                    {data: 'profile.name', name: 'profile.name'},
                    {data: 'profile.patronymic', name: 'profile.patronymic'},
                    {data: 'email', name: 'users.email'},
                    {data: 'role', name: 'role'},
                    {data: 'action', name: 'action', "orderable": false},
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Russian.json"
                },
                autoWidth: false,
                pageLength: 20,
                lengthMenu: [20, 50, 100],
                initComplete: function () {
                    var filters = $('#table tfoot tr');
                    $('#table thead').append(filters);

                    this.api().columns().every(function () {
                        var column = this;

                        $('input', this.footer()).on('keyup change clear', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });

                        $('select', this.footer()).on('change', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                    });
                }
            });
        });
    </script>
@stop

