@extends('layout.base')

@section('header-title')
    <i class="fa fa-fw fa-user"></i> {{ Auth::user()->name }}
@endsection

@section('content')

    <div class="row">

        <div class="col-xs-12">

            <h3>Actualizar sorteo</h3>
            <hr>
        </div>
    </div>

    <form action="{{ route('sorts.update', ['sort' => $sort->id]) }}" method="post">

        <div class="row">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="description">Nombre descriptivo</label>
                    <input type="text" class="form-control" name="description" id="description" value="{{ $sort->description }}" placeholder="Nombre descriptivo">
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="time_sort">Hora del sorteo</label>
                    <input type="time" class="form-control" name="time_sort" id="time_sort" value="{{ $sort->time_sort }}" placeholder="Hora del sorteo">
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="pay_per_100">Pago por 100</label>
                    <input type="number" class="form-control" name="pay_per_100" id="pay_per_100" value="{{ $sort->pay_per_100 }}" placeholder="Pago por 100" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="pay_per_100">Sorteo actual</label>
                    <p>
                        @if(($dailySort = $sort->getLastDailySort()))
                            {{ $dailySort->getDateSort()->format('d-m-Y') }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <button class="btn btn-primary-color">
                    <i class="fa fa-fw fa-save"></i> Actualizar
                </button>

                @if(($dailySort = $sort->getLastDailySort()) && $dailySort->status === \App\DailySort::STATUS_ACTIVE)
                    <a href="{{ route('sorts.downSort', ['sort' => $sort->id]) }}" class="btn btn-danger">
                        <i class="fa fa-fw fa-arrow-down"></i> Cerrar sorteo actual
                    </a>
                @else
                    <a href="{{ route('sorts.upSort', ['sort' => $sort->id]) }}" class="btn btn-primary">
                        <i class="fa fa-fw fa-arrow-up"></i> Activar sorteo actual
                    </a>
                @endif
            </div>
        </div>

    </form>



@endsection