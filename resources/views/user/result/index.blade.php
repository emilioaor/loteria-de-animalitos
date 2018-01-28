@extends('layout.base')

@section('header-title')
    <i class="fa fa-fw fa-user"></i> {{ Auth::user()->name }}
@endsection

@section('content')

    <div class="row">

        <div class="col-xs-12">

            <h3>Resultados de {{ $date->format('d-m-Y') }}</h3>
            <div class="row">
                <div class="col-sm-5">
                    <input
                            type="date"
                            id="date"
                            class="form-control"
                            value="{{ $date->format('Y-m-d') }}"
                            max="{{ $now->format('Y-m-d') }}"
                            onchange="location.href = '{{ route('results.index') }}?date=' + $('#date').val()">
                </div>
            </div>
            <hr>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th width="25%">Sorteo</th>
                        <th width="15%">Estatus</th>
                        <th width="15%">Total jugado</th>
                        <th width="20%">Ganador</th>
                        <th width="10%" class="text-center">Ganadores</th>
                        <th width="15%" class="text-center">P. de pago</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailySorts as $dailySort)
                        <tr>
                            <td>{{ $dailySort->sort->description . ' - ' . $dailySort->timeSortFormat() }}</td>
                            <td>
                                @if($date->format('Y-m-d') === $now->format('Y-m-d') && $dailySort->hasActive())
                                    <span class="text-success bg-success">Activo</span>
                                @else
                                    <span class="text-danger bg-danger">Cerrado</span>
                                @endif
                            </td>
                            <td>{{ number_format($dailySort->totalTicketsToDate($date), 2, ',', '.') }}</td>
                            <td>
                                @if($animal = $dailySort->getAnimalGainToDate($date))
                                    <img
                                            src="{{ asset('img/' . $dailySort->sort->folder . '/' . $animal->getClearName() . '.jpg') }}"
                                            alt="{{ $animal->name }}"
                                            style="max-width: 30px">
                                    {{ $animal->name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">{{ $dailySort->countTicketsGainToDate($date) }}</td>
                            <td class="text-center">{{ $dailySort->countTicketsPendingToDate($date) }}</td>
                            <td>
                                <button
                                        type="button"
                                        class="btn btn-primary-color"
                                        data-toggle="modal"
                                        data-target="#animalsModal{{ $dailySort->id }}"
                                        @if($date->format('Y-m-d') === $now->format('Y-m-d') && $dailySort->hasActive())
                                            disabled
                                        @endif
                                        >
                                    <i class="glyphicon glyphicon-ok-sign"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>


    <!-- Modal -->
    @foreach($dailySorts as $dailySort)
        <div id="animalsModal{{ $dailySort->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">
                            Ganador
                            <small>({{ $dailySort->sort->description . ' ' . $dailySort->timeSortFormat() }})</small>
                            <i class="glyphicon glyphicon-ok-sign"></i>
                        </h4>
                    </div>
                    <div class="modal-body">

                        <div class="row">

                            @foreach($dailySort->sort->animals as $animal)
                                <div class="col-xs-4 col-sm-3">
                                    <a
                                            href=""
                                            onclick="changeAnimalId('{{ $animal->id  }}', '{{ $dailySort->id }}');"
                                            ng-click="selectedAnimal = {{ $animal->id }}">
                                        <p>
                                            <img
                                                    src="{{ asset('img/' . $dailySort->sort->folder . '/' . $animal->getClearName() . '.jpg') }}"
                                                    alt="{{ $animal->name }}"
                                                    style="max-width: 38px">
                                            {{ $animal->name }}
                                            <i class="fa fa-check" ng-show="selectedAnimal == {{ $animal->id }}"></i>
                                        </p>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center">

                            <form action="{{ route('results.animalGain', ['dailySort' => $dailySort->id]) }}" method="post" id="animalForm{{ $dailySort->id }}">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}

                                <input type="hidden" id="animal_id{{ $dailySort->id }}" name="animal_id">
                                <input type="hidden" name="date_sort" value="{{ $date->format('Y-m-d') }}">

                                <button class="btn btn-primary-color" id="btnSaveAnimal{{ $dailySort->id }}" disabled>
                                    <i class="fa fa-fw fa-save"></i> Guardar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach

@endsection

@section('js')
    <script>
        function changeAnimalId(animalId, id) {
            $('#animal_id' + id).val(animalId);
            $('#btnSaveAnimal' + id).removeAttr('disabled');
        }
    </script>
@endsection