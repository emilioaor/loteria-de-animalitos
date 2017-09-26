----------------------------------
      LOTERIA DE ANIMALITOS
----------------------------------
{{ strtoupper($ticket->dailySort->sort->description . ' ' . $ticket->dailySort->getDateSort()->format('d-m-Y') . ' ' . $ticket->dailySort->sort->time_sort) }}
TICKET: {{ $ticket->public_id }}
FECHA: {{ date_format($ticket->created_at, 'd-m-Y h:i a') }}
TAQUILLA: {{ $ticket->user->name }}

ANIMALITO                MONTO
----------------------------------
@foreach($ticket->animals as $animal)
{{ $animal->getLabelMoreSpace() }}{{ $animal->pivot->amount }}
@endforeach
----------------------------------
TOTAL                  {{ number_format($ticket->amount(), 2, ',', '.') }}

REVISE SU TICKET
VALIDO POR 5 DIAS


----------------------------------