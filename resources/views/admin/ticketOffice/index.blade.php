@extends('layout.base')

@section('header-title')
    <i class="fa fa-fw fa-user"></i> {{ Auth::user()->name }}
@endsection

@section('content')

    <div class="row" ng-controller="SortController">

        <div class="col-xs-12">

            <h3>
                Lista de taquillas
                <a href="{{ route('ticketOffice.create') }}" class="btn btn-primary-color">
                    <i class="fa fa-fw fa-plus"></i>
                </a>
            </h3>
            <hr>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th width="45%">Usuario</th>
                        <th width="40%">Taquilla</th>
                        <th width="15%">Registrado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ date_format($user->created_at, 'd-m-Y') }}</td>
                            <td>
                                <a href="{{ route('ticketOffice.edit', ['ticketOffice' => $user->id]) }}" class="btn btn-warning">
                                    <i class="fa fa-fw fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection