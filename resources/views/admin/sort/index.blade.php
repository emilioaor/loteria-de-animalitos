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

                @if(count($sorts))
                    <form action="{{ route('sorts.upAll') }}" method="post" style="display: inline-block">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        <button class="btn btn-primary-color">
                            <i class="fa fa-fw fa-arrow-up"></i> Habilitar sorteos del día
                        </button>
                    </form>

                    <a href="{{ route('sorts.downAll') }}" class="btn btn-danger">
                        <i class="fa fa-fw fa-arrow-down"></i> Cerrar sorteos del día
                    </a>
                @endif
            </h3>
            <hr>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th width="35%">Sorteo</th>
                        <th width="30%">Hora del sorteo</th>
                        <th width="15%">Sorteo actual</th>
                        <th width="20%">Estatus</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sorts as $sort)
                        <tr>
                            <td>{{ $sort->description }}</td>
                            <td>{{ $sort->time_sort }}</td>
                            <td>
                                @if(($dailySort = $sort->getLastDailySort()))
                                    {{ $dailySort->getDateSort()->format('d-m-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $dailySort ? $dailySort->status: '-'}}</td>
                            <td>
                                <a href="{{ route('sorts.edit', ['sort' => $sort->id]) }}" class="btn btn-warning">
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