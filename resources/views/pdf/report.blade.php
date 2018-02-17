@extends('layout.base')

@section('header-title')
    <div>
        <i class="fa fa-fw fa-list-alt"></i>
        Reporte
    </div>
@endsection

@section('content')
    <p><strong>Desde: </strong> {{ $start->format('d-m-Y') }}</p>
    <p><strong>Hasta: </strong> {{ $end->format('d-m-Y') }}</p>

    <table class="table table-responsive table-striped">
        <thead>
        <tr>
            <th>Ticket</th>
            <th>Creado</th>
            <th>Estatus</th>
            <th>Taquilla</th>
            <th>Sorteo</th>
            <th style="text-align: center">Jugado</th>
            <th style="text-align: center">Ganado</th>
        </tr>
        </thead>

        <tbody>
        @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->public_id }}</td>
                <td>{{ date_format($ticket->created_at, 'd-m-Y h:m:s a') }}</td>
                <td>{{ $ticket->status }}</td>
                <td>{{ $ticket->user->name }}</td>
                <td>{{ $ticket->dailySorts[0]->sort->description . ' - ' . $ticket->dailySorts[0]->timeSortFormat() }}</td>
                <td style="text-align: center">{{ number_format($ticket->amount(), 2, ',', '.') }}</td>
                <td style="text-align: center">{{ number_format($ticket->payToGain(), 2, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>

        <tfoot>
        <tr>
            <th>TOTAL</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th style="text-align: center">{{ number_format($totalAmount, '2', ',', '.') }}</th>
            <th style="text-align: center">{{ number_format($totalGainAmount, '2', ',', '.') }}</th>
        </tr>
        <tr>
            <th>BALANCE</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th colspan="2" style="text-align: center">
                {{ number_format($totalAmount - $totalGainAmount, '2', ',', '.') }}
            </th>
        </tr>
        </tfoot>
    </table>
@endsection