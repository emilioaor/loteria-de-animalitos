@extends('layout.base')

@section('header-title')
    <i class="fa fa-fw fa-user"></i> {{ Auth::user()->name }}
@endsection

@section('content')

    <div class="row">

        <div class="col-xs-12">

            <h3>Lista de tickets</h3>
            <hr>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th width="15%">Ticket ID</th>
                        <th width="15%">Fecha de registro</th>
                        <th width="15%">Taquilla</th>
                        <th width="20%">Estatus</th>
                        <th width="20%">Sorteo</th>
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
                            <td>{{ $ticket->dailySort->sort->description . ' - ' . $ticket->dailySort->getDateSort()->format('d-m-Y') }}</td>
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