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
                    <label for="sort_id">Sorteo</label>
                    <select name="sort_id" id="sort_id" class="form-control">
                        @foreach($sorts as $s)
                            @if($s->id === $sort->sort_id)
                                <option value="{{ $s->id }}" selected>{{ $s->description }}</option>
                            @else
                                <option value="{{ $s->id }}">{{ $s->description }}</option>
                            @endif
                        @endforeach
                    </select>
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
                    <label>Estatus</label>
                    <p>
                        @if($sort->hasActive())
                            <span class="text-success bg-success">Activo</span>
                        @else
                            <span class="text-danger bg-danger">Cerrado</span>
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
            </div>
        </div>

    </form>



@endsection