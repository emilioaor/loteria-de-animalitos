<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ticket: {{ $ticket->public_id }}</title>
</head>
<body>
    <style>
        @page {
            size: 80mm 120mm;
        }

        * {
            margin: 0 2px !important;
            padding: 0 !important;
            font-size: 8px !important;
            font-weight: normal;
            border-width: 0.3px;
        }
        .text-center {
            text-align: center;
        }
        #title {
            font-size: 15px!important;
            margin-bottom: 4px !important;
        }
        #head th {
            font-weight: bold !important;
        }
        #body td {
            height: 20px !important;
        }
        #space {
            margin-bottom: 6px !important;
        }
        #hippo {
            font-size: 14px !important;
            font-weight: bold !important;
        }
        strong {
            font-weight: bold !important;
        }
        #total th {
            font-size: 13px !important;
            font-weight: bold !important;
        }
    </style>
    <main>
        <br>
        <hr>
        <h1 id="title" class="text-center">SISTEMA TU HIPICO ONLINE</h1>
        <hr id="space">
        <p id="hippo">{{ strtoupper($ticket->run->hippodrome->name) }}</p>
        <p><strong>TICKET:</strong> {{ $ticket->public_id }}</p>
        <p><strong>FECHA:</strong> {{ date_format($ticket->created_at, 'd-m-Y h:m') }}</p>
        <p><strong>CARRERA:</strong> {{ strtoupper($ticket->run->public_id) }}</p>
        <p><strong>CAJA:</strong> {{ strtoupper($ticket->user->name) }}</p>

        <br>

        <hr>
        <table width="100%">
            <thead>
                <tr id="head">
                    <th width="32%">CABALLO</th>
                    <th width="17%" class="text-center">PRE/TAB</th>
                    <th width="17%" class="text-center">CAN/TAB</th>
                    <th width="17%" class="text-center">TOT/TAB</th>
                    <th width="17%" class="text-center">GAN</th>
                </tr>
            </thead>
        </table>
        <hr>
        <table width="100%">
            <tbody id="body">
                @foreach($ticket->ticketDetails as $detail)
                    <tr>
                        <td width="32%">
                            @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                                {{ str_limit($detail->horse->name, 15) }}
                            @elseif($detail->status === App\TicketDetail::STATUS_NULL)
                                <del>{{ str_limit($detail->horse->name, 15) }}</del>
                            @endif
                        </td>
                        <td width="17%" class="text-center">
                            @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                                {{ $priceTable = $detail->horse->runs()->find($ticket->run_id)->pivot->static_table }}
                            @elseif($detail->status === App\TicketDetail::STATUS_NULL)
                                <del>{{ $priceTable = $detail->horse->runs()->find($ticket->run_id)->pivot->static_table }}</del>
                            @endif
                        </td>
                        <td width="17%" class="text-center">
                            @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                                {{ $detail->tables }}
                            @elseif($detail->status === App\TicketDetail::STATUS_NULL)
                                <del>{{ $detail->tables }}</del>
                            @endif
                        </td>
                        <td width="17%" class="text-center">
                            @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                                {{ $detail->tables * $priceTable }}
                            @elseif($detail->status === App\TicketDetail::STATUS_NULL)
                                <del>{{ $detail->tables * $priceTable }}</del>
                            @endif
                        </td>
                        <td width="17%" class="text-center">
                            @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                                {{ $detail->gain_amount }}
                            @elseif($detail->status === App\TicketDetail::STATUS_NULL)
                                <del>{{ $detail->gain_amount }}</del>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>
        <table width="100%">
            <thead>
            <tr>
                <th width="66%">SUBTOTAL</th>
                <th width="17%" class="text-center">{{ number_format($ticket->totalForTables(), '2', ',', '.') }}</th>
                <th width="17%" class="text-center">{{ number_format($ticket->totalForGains(), '2', ',', '.') }}</th>
            </tr>
            </thead>
        </table>
        <hr>
        <br>

        <table width="100%">
            <thead>
            <tr id="total">
                <th width="66%">TOTAL</th>
                <th width="34%" class="text-center">{{ number_format($ticket->totalActiveAmount(), '2', ',', '.') }}</th>
            </tr>
            </thead>
        </table>
        <br>
        <p>REVISE SU TICKET</p>
        <p>VALIDO POR 5 DIAS</p>

    </main>
</body>
</html>