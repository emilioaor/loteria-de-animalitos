@extends('layout.base')

@section('header-title')
    <i class="fa fa-fw fa-user"></i> {{ Auth::user()->name }}
@endsection

@section('content')

    <div class="row">

        <div class="col-xs-12">

            <h3>Registrar sorteo</h3>
            <hr>
        </div>
    </div>

    <form action="{{ route('sorts.store') }}" method="post">

        <div class="row">
            {{ csrf_field() }}

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="sort_id">Sorteo</label>
                    <select name="sort_id" id="sort_id" class="form-control">
                        @foreach($sorts as $sort)
                            <option value="{{ $sort->id }}">{{ $sort->description }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="time_sort">Hora del sorteo</label>
                    <input type="time" class="form-control" name="time_sort" id="time_sort" placeholder="Hora del sorteo" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <button class="btn btn-primary-color">
                    <i class="fa fa-fw fa-save"></i> Guardar
                </button>
            </div>
        </div>

    </form>

@endsection