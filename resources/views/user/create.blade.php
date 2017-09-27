@extends('layout.base')

@section('header-title')
    <i class="fa fa-fw fa-user"></i> {{ Auth::user()->name }}
@endsection

@section('content')
    <div class="row section-animals" ng-controller="AnimalController">

        <div class="col-xs-6 col-sm-7">
            <div class="row">
                <!-- Lista de animalitos -->
                <div class="col-sm-6 col-md-4 section-animals__item" ng-repeat="animal in data.animalsList">
                    <p class="text-center" ng-hide="hasTicket(animal.id);">
                        <a href="" ng-click="addToTicket(animal);">
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
                            <button type="button" class="btn btn-success" ng-click="addNewAnimal()">
                                <i class="fa fa-plus"></i>
                            </button>
                        </td>
                    </tr>
                </table>

                <form action="{{ route('user.create') }}" method="post" name="formAnimal" id="formAnimal">

                    <div class="form-group">
                        <label for="sort_id">Sorteo</label>
                        <select name="sort_id" id="sort_id" class="form-control" required>
                            @foreach($sorts as $dailySort)
                                <option value="{{ $dailySort->id }}">{{ $dailySort->sort->description . ' - ' . $dailySort->sort->time_sort }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{ csrf_field() }}

                            <!-- Animalitos jugados -->
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
                                <button type="button" class="btn btn-danger" ng-click="removeFromTicket($index)">
                                    <i class="fa fa-remove"></i>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="text-center">

                        <button class="btn btn-lg btn-primary-color" ng-show="data.animalsTicket.length">
                            <i class="fa fa-save"></i> Guardar ticket (F2)
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

@endsection

@section('js')
    <script>
        var data = {
            animalsList : {!! json_encode($animals) !!},
            imgUrl : '{{ asset('img/animals') }}'
        };

        $('#newAnimalNumber').focus();

        $(window).ready(function () {

            $(window).on('keydown', function(evt) {
                var countTickets = [[ data.animalsTicket.length ]];
                if (evt.keyCode == 113 && countTickets > 0) {
                    $('#formAnimal').submit();
                }
            })
        });
    </script>
@endsection


