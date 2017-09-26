@extends('layout.base')

@section('header-title')
    <div class="text-center">
        <i class="fa fa-fw fa-openid"></i>
        Ingreso al sistema
    </div>
@endsection

@section('content')
    <div class="row section-form">

        <div class="col-sm-6 col-sm-offset-3">
            <form action="{{ route('index.auth') }}" method="post">

                {{ csrf_field() }}

                <div class="form-group">
                    <div class="col-xs-4">
                        <label for="username" class="block">Usuario</label>
                    </div>

                    <div class="col-xs-8">
                        <input type="text" id="username" name="username" maxlength="15" class="form-control" placeholder="Nombre de usuario" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-4">
                        <label for="password" class="block">Contraseña</label>
                    </div>

                    <div class="col-xs-8">
                        <input type="password" id="password" name="password" maxlength="15" class="form-control" placeholder="Contraseña" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="text-center">
                        <button class="btn btn-primary-color">
                            <i class="glyphicon glyphicon-log-in"></i>
                            Iniciar sesión
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection


