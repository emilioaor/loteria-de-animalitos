@extends('layout.base')

@section('header-title')
    <i class="fa fa-fw fa-user"></i> {{ Auth::user()->name }}
@endsection

@section('content')

    <div class="row">

        <div class="col-xs-12">

            <h3>Registrar taquilla</h3>
            <hr>
        </div>
    </div>

    <form action="{{ route('ticketOffice.store') }}" method="post">

        <div class="row">
            {{ csrf_field() }}

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="username">Nombre de usuario</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Nombre de usuario" maxlength="15" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Nombre" maxlength="40" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="print_code">Código de impresión</label>
                    <input type="number" class="form-control" name="print_code" id="print_code" placeholder="Código de impresión" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="password1">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password1" placeholder="Contraseña" maxlength="15" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="password2">Repetir contraseña</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password2" placeholder="Repetir contraseña" maxlength="15" required>
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