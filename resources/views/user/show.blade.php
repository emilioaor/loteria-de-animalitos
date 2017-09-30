@extends('layout.base')

@section('header-title')
    <i class="fa fa-fw fa-user"></i> {{ Auth::user()->name }}
@endsection

@section('content')

    <div class="row">

        <div class="col-xs-12">

            <h3>{{ $ticket->public_id }}</h3>
            <hr>
        </div>

        <div class="col-sm-3">
            <label for="">Ticket ID</label>
            <p>{{ $ticket->public_id }}</p>
        </div>

        <div class="col-sm-3">
            <label for="">Fecha de registro</label>
            <p>{{ date_format($ticket->created_at, 'd-m-Y h:i a')}}</p>
        </div>

        <div class="col-sm-3">
            <label for="">Taquilla</label>
            <p>{{ $ticket->user->name }}</p>
        </div>

        <div class="col-sm-3">
            <label for="">Estatus</label>
            <p>{{ $ticket->status }}</p>
        </div>

        <div class="col-xs-12">
            <label for="">Sorteos</label>
        </div>

        @foreach($ticket->dailySorts as $dailySort)
            <div class="col-sm-3">
                <p>{{ $dailySort->sort->description . ' - ' . $dailySort->getDateSort()->format('d-m-Y')  }}</p>
                <p><strong>Ganador:</strong> {{ $dailySort->result ? $dailySort->result->animal->name : '-' }}</p>
            </div>
        @endforeach

    </div>

    @if($ticket->isGain() && $ticket->status === \App\Ticket::STATUS_ACTIVE)
        <div class="row">
            <div class="col-xs-12">
                <form action="{{ route('user.payTicket', ['ticket' => $ticket->id]) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <button class="btn btn-primary-color">
                        <i class="fa fa-fw fa-money"></i> Pagar ticket
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="row">
        <hr>

        @foreach($ticket->animals as $animal)
            <div class="col-xs-6 col-sm-3">
                <p>
                    <img
                            src="{{ asset('img/animals/' . $animal->getClearName() . '.jpg') }}"
                            alt="{{ $animal->name }}"
                            style="max-width: 50px">
                    {{ $animal->name }}
                </p>
                <p>
                    <strong>Monto:</strong>
                    {{ number_format($animal->pivot->amount, 2, ',', '.') }}
                </p>
            </div>
        @endforeach

    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>Total: {{ number_format($ticket->amount(), 2, ',', '.') }}</h3>
            @if($ticket->isGain())
                <h3>Pagar al ganador: {{ number_format($ticket->payToGain(), 2, ',', '.') }}</h3>
            @endif
        </div>
    </div>

@endsection