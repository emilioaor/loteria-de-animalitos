@extends('layout.base')

@section('header-title')
    <i class="fa fa-fw fa-user"></i> {{ Auth::user()->name }}
@endsection

@section('content')
    @if(count($sorts))
        <div class="row section-animals" ng-controller="AnimalController">

            <div class="col-xs-6 col-sm-7">
                <div class="row animal-list">
                    <!-- Lista de animalitos -->
                    <div class="col-sm-6 col-md-4 section-animals__item" ng-repeat="animal in data.animalsList">
                        <p class="text-center" ng-hide="hasTicket(animal.id);">
                            <a href="" ng-click="addToTicket(animal);" onclick="moveScroll = true">
                                <img ng-src="[[ data.imgUrl + '/' + clearName(animal.name) + '.jpg' ]]" alt="[[ animal.name ]]">
                                <strong>[[ animal.number ]]</strong>
                                [[ animal.name ]]
                            </a>
                        </p>

                        <p class="text-center selected" ng-show="hasTicket(animal.id);">
                            <img ng-src="[[ data.imgUrl + '/' + clearName(animal.name) + '.jpg' ]]" alt="[[ animal.name ]]">
                            <strong>[[ animal.number ]]</strong>
                            [[ animal.name ]]
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-sm-5">

                <div class="">
                    <!-- Agregar por numero -->
                    <table class="table">
                        <tr>
                            <td width="25%">
                                <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Animalito"
                                        max="36"
                                        id="newAnimalNumber"
                                        ng-model="newAnimal.number"
                                        ng-style="styleAnimalAdd"
                                        ng-keydown="keyToGoAmount($event)"
                                        >
                            </td>
                            <td width="35%">
                                [[ printIfHasList() ]]
                            </td>
                            <td width="40%">
                                <input
                                        type="number"
                                        class="form-control"
                                        placeholder="Valor"
                                        id="newAnimalAmount"
                                        ng-model="newAnimal.amount"
                                        ng-keydown="keyToAddNewAnimal($event)"
                                        ng-disabled="! hasList(newAnimal.number)"
                                        >
                            </td>
                            <td>
                                <button type="button" class="btn btn-success" ng-click="addNewAnimal()" onclick="moveScroll = true">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary"
                                        data-toggle="modal"
                                        data-target="#repeatModal"
                                        ng-click="searchRepeatTicket('{{ route('user.lastTickets') }}')">
                                    <i class="glyphicon glyphicon-search"></i>
                                </button>
                            </td>
                        </tr>
                    </table>

                    <form action="{{ route('user.create') }}" method="post" name="formAnimal" id="formAnimal">

                        @foreach($sorts as $sort)
                            @if(count($sort))
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <label>
                                            Sorteos
                                            ({{ $sort[0]->sort->description }})
                                        </label>
                                    </div>
                                    @foreach($sort as $dailySort)
                                        <div class="col-sm-4">
                                            <input
                                                    type="checkbox"
                                                    name="sorts[{{ $dailySort->id }}]"
                                                    ng-model="data.sorts[{{ $dailySort->id }}]"
                                                    ng-init="data.sorts[{{ $dailySort->id }}]=false"
                                                    ng-change="getTotal()">
                                            {{ $dailySort->timeSortFormat() }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach

                        {{ csrf_field() }}

                                <!-- Animalitos jugados -->
                        <div id="spaceAnimalTicket" style="overflow: auto; width: 100%; margin-bottom: 10px;">
                            <table class="table">
                                <tbody>
                                <tr ng-repeat="animal in data.animalsTicket">
                                    <td width="10%">[[ $index + 1 ]]</td>
                                    <td width="30%">[[ animal.number + ' - ' + animal.name ]]</td>
                                    <td width="20%">
                                        <span class="text-danger" ng-if="animal.limitError || true">
                                            <strong>Limite:</strong>
                                            [[ animal.limit ]]
                                        </span>
                                    </td>
                                    <td width="40%">
                                        <input
                                                type="hidden"
                                                class="form-control"
                                                placeholder="Valor"
                                                ng-value="animal.id"
                                                name="animals[]"
                                                required
                                                >
                                        <input
                                                type="number"
                                                class="form-control"
                                                placeholder="Valor"
                                                ng-model="animal.amount"
                                                name="amounts[]"
                                                ng-change="getTotal()"
                                                required
                                                >
                                    <span
                                            class="error"
                                            ng-show=""
                                            >
                                        Este valor es requerido
                                    </span>
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-danger"
                                                ng-click="removeFromTicket($index)">
                                            <i class="fa fa-remove"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <span ng-if="data.animalsTicket.length">
                                            [[ total ]] Bsf
                                        </span>
                                    </td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="text-center">

                            <button
                                    class="btn btn-lg btn-primary-color"
                                    ng-show="data.animalsTicket.length"
                                    ng-disabled="! hasSelectedSort() || hasLimitError()">
                                <i class="fa fa-save"></i> Guardar ticket
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- Modal ticket repeat -->
            <div id="repeatModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button id="closeModalRepeat" type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Repetir ticket</h4>
                        </div>
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-xs-12">
                                    <input
                                            type="text"
                                            class="form-control"
                                            ng-change="searchRepeatTicket('{{ route('user.lastTickets') }}')"
                                            ng-model="filterTicket"
                                            placeholder="Numero del ticket">
                                    <hr>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 text-center">

                                    <table ng-if="true" class="table table-responsive">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Ticket</th>
                                                <th class="text-center">Estatus</th>
                                                <th class="text-center">Creado</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr  ng-show="repeatLoading">
                                                <th colspan="4" class="text-center">
                                                    <img src="{{ asset('img/loading.gif') }}" alt="Cargando..">
                                                </th>
                                            </tr>
                                            <tr  ng-show="! repeatLoading && repeatTickets.length === 0">
                                                <th colspan="4" class="text-center">
                                                    Sin resultados
                                                </th>
                                            </tr>
                                            <tr ng-repeat="ticket in repeatTickets"  ng-show="! repeatLoading">
                                                <td>[[ ticket.public_id ]]</td>
                                                <td>[[ ticket.status ]]</td>
                                                <td>[[ ticket.created_at ]]</td>
                                                <td>
                                                    <button class="btn btn-primary-color" ng-click="getAnimalsRepeat(ticket)">
                                                        <i class="glyphicon glyphicon-check"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /Modal ticket repeat -->

        </div>
    @else

        <div class="alert alert-danger">
            <strong>Atención: </strong> En estos momentos no hay sorteos abiertos
        </div>
    @endif

@endsection

@section('js')
    <script>
        var imgUrl = 'img/';

        @foreach($sorts as $sort)
            @foreach($sort as $dailySort)
                imgUrl = '{{ 'img/' . $dailySort->sort->folder }}';
                @break(2)
            @endforeach
        @endforeach

        var data = {
            animalsList : {!! json_encode($animals) !!},
            imgUrl : imgUrl,
        };

        var moveScroll = false;

        $('#newAnimalNumber').focus();

        $(window).ready(function () {

            window.setInterval(function() {
                if (moveScroll) {
                    $('#spaceAnimalTicket').scrollTop(99999);
                    moveScroll = false;
                }
            }, 500)
        });
    </script>
@endsection


