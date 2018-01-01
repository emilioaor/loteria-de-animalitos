@extends('layout.base')

@section('header-title')
    <i class="fa fa-fw fa-user"></i> {{ Auth::user()->name }}
@endsection

@section('content')

    <div class="row" ng-controller="SortController">

        <div class="col-xs-12">

            <h3>
                Lista de sorteos
                <a href="{{ route('sorts.create') }}" class="btn btn-primary-color">
                    <i class="fa fa-fw fa-plus"></i>
                </a>
            </h3>
            <hr>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th width="33%">Sorteo</th>
                        <th width="33%">Hora del sorteo</th>
                        <th width="33%">Estatus</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sorts as $ds)
                        <tr>
                            <td>{{ $ds->sort->description }}</td>
                            <td>{{ $ds->timeSortFormat() }}</td>
                            <td>
                                @if($ds->hasActive())
                                    <span class="text-success bg-success">Activo</span>
                                @else
                                    <span class="text-danger bg-danger">Cerrado</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('sorts.edit', ['sort' => $ds->id]) }}" class="btn btn-warning">
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