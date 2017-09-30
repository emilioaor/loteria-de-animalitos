@extends('layout.base')

@section('header-title')
    <i class="fa fa-fw fa-user"></i> {{ Auth::user()->name }}
@endsection

@section('content')

    <div class="row">

        <div class="col-xs-12">

            <h3>Lista de tickets</h3>
            <div class="row">
                <div class="col-sm-8 col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" ng-model="search" placeholder="Buscar por ticket ID">
                    <span class="input-group-btn">
                        <a href="{{ route('user.list') }}?search=[[ search ]]" id="btnSearch" class="btn btn-default">
                            <i class="fa fa-fw fa-search"></i>
                        </a>
                    </span>

                    </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->

                <div class="col-xs-12">
                    @if(Request::has('search'))
                        <br>
                        <a href="{{ route('user.list') }}" class="text-danger">
                            <i class="fa fa-remove"></i>
                        </a>
                        <small>
                            <strong>Filtrado por:</strong> {{ Request::get('search') }}

                        </small>

                    @endif
                </div>
            </div><!-- /.row -->
            <hr>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th width="15%">Ticket ID</th>
                        <th width="25%">Fecha de registro</th>
                        <th width="25%">Taquilla</th>
                        <th width="20%">Estatus</th>
                        <th width="15%">Monto</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->public_id }}</td>
                            <td>{{ date_format($ticket->created_at, 'd-m-Y h:i a') }}</td>
                            <td>{{ $ticket->user->name }}</td>
                            <td>
                                @if($ticket->isGain() && $ticket->status === \App\Ticket::STATUS_ACTIVE)
                                    <p class="gain">Ganador</p>
                                @else
                                    {{ $ticket->status }}
                                @endif
                            </td>
                            <td>{{ number_format($ticket->amount(), 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('user.show', ['ticket' => $ticket->id]) }}" class="btn btn-primary-color">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="text-center">
                {{ $tickets->render() }}
            </div>
        </div>
    </div>

@endsection