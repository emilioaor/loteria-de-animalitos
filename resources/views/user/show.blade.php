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

        @foreach($ticket->dailySorts()->orderBy('sort_id')->orderBy('time_sort')->get() as $dailySort)
            <div class="col-sm-3">
                <p>{{ $dailySort->sort->description . ' - ' . $dailySort->timeSortFormat()  }}</p>
                <p><strong>Ganador:</strong> {{ ($animal = $dailySort->getAnimalGainToDate($ticket->created_at)) ? $animal->name : '-' }}</p>
            </div>
        @endforeach

    </div>

    <div class="row">
        <div class="col-xs-12">
            @if($ticket->isGain())
                <form
                        action="{{ route('user.payTicket', ['ticket' => $ticket->id]) }}"
                        method="post"
                        style="display: inline-block;"
                        id="payForm">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <button type="button" class="btn btn-primary-color" onclick="payTicket();">
                        <i class="fa fa-fw fa-money"></i> Pagar ticket
                    </button>
                </form>
            @endif

            @if($ticket->status === \App\Ticket::STATUS_ACTIVE)
                <form
                        action="{{ route('user.nullTicket', ['ticket' => $ticket->id]) }}"
                        method="post"
                        style="display: inline-block;"
                        id="nullForm">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <button type="button" class="btn btn-danger" onclick="nullTicket();">
                        <i class="fa fa-fw fa-remove"></i> Anular ticket
                    </button>
                </form>
            @endif

                <form
                        action="{{ route('user.printTicket', ['ticket' => $ticket->id]) }}"
                        method="post"
                        style="display: inline-block;"
                        id="printForm">
                    {{ csrf_field() }}

                    <button type="button" class="btn btn-primary" onclick="printTicket();">
                        <i class="fa fa-fw fa-print"></i> Imprimir ticket
                    </button>
                </form>
        </div>
    </div>

    <div class="row">
        <hr>

        @foreach($ticket->animals as $animal)
            <div class="col-xs-6 col-sm-3">
                <p>
                    <img
                            src="{{ asset('img/' . $ticket->dailySorts[0]->sort->folder. '/' . $animal->getClearName() . '.jpg') }}"
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
            @if($ticket->isGain() || $ticket->isPay())
                <h3>Pagar al ganador: {{ number_format($ticket->payToGain(), 2, ',', '.') }}</h3>
            @endif
        </div>
    </div>

@endsection

@section('js')
    <script>
        function nullTicket() {
            if (confirm('¿Anular este ticket?')) {
                $('#nullForm').submit();
            }
        }
        function payTicket() {
            if (confirm('¿Pagar este ticket?')) {
                $('#payForm').submit();
            }
        }
        function printTicket() {
            if (confirm('¿Imprimir este ticket?')) {
                $('#printForm').submit();
            }
        }
    </script>
@endsection