@extends('layout.base')

@section('header-title')
    <i class="fa fa-fw fa-user"></i> {{ Auth::user()->name }}
@endsection

@section('content')
    @if(count($sorts))
        <div class="row section-animals" ng-controller="AnimalController">

            <div class="col-xs-6 col-sm-7">
                <div class="row">
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

                <div class="section-float">
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
                        </tr>
                    </table>

                    <form action="{{ route('user.create') }}" method="post" name="formAnimal" id="formAnimal">

                        <div class="form-group">
                            <div class="col-xs-12">
                                <label>
                                    Sorteos
                                    ({{ $sorts[0]->sort->description }})
                                </label>
                            </div>
                            @foreach($sorts as $dailySort)
                                <div class="col-sm-4">
                                    <input
                                            type="checkbox"
                                            name="sorts[{{ $dailySort->id }}]"
                                            ng-model="data.sorts[{{ $dailySort->id }}]"
                                            ng-init="data.sorts[{{ $dailySort->id }}]=false"
                                            ng-change="getTotal()">
                                    {{ $dailySort->time_sort }}
                                </div>
                            @endforeach
                        </div>

                        {{ csrf_field() }}

                                <!-- Animalitos jugados -->
                        <div id="spaceAnimalTicket" style="max-height: 250px; overflow: auto; width: 100%; margin-bottom: 10px;">
                            <table class="table">
                                <tbody>
                                <tr ng-repeat="animal in data.animalsTicket">
                                    <td width="10%">[[ $index + 1 ]]</td>
                                    <td width="50%">[[ animal.number + ' - ' + animal.name ]]</td>
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
                                    ng-disabled="! hasSelectedSort()">
                                <i class="fa fa-save"></i> Guardar ticket
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    @else

        <div class="alert alert-danger">
            <strong>Atención: </strong> En estos momentos no hay sorteos abiertos
        </div>
    @endif

@endsection

@section('js')
    <script>
        @if(isset($sorts[0]))
            var imgUrl = '{{ 'img/' . $sorts[0]->sort->folder }}';
        @else
            var imgUrl = 'img/';
        @endif

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


