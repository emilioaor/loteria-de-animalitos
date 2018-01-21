Agencia 9192
TICKET: {{ $ticket->public_id }}
FECHA: {{ date_format($ticket->created_at, 'd-m-Y h:i a') }}
@foreach($ticket->dailySorts()->orderBy('time_sort')->get() as $dailySort)
{{ strtoupper($dailySort->sort->description . ' ' . $dailySort->timeSortFormat()) }}
@endforeach
------------------------------
@foreach($ticket->animals as $animal)
{{ $animal->getLabelMoreSpace() }}{{ $animal->pivot->amount }}
@endforeach
------------------------------
TOTAL                  {{ number_format($ticket->amount(), 2, ',', '.') }}

VALIDO POR 3 DIAS


------------------------------