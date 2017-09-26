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

            <form action="{{ route('user.create') }}" method="post" name="formAnimal" class="section-float">

                <div class="form-group">
                    <label for="sort_id">Sorteo</label>
                    <select name="sort_id" id="sort_id" class="form-control" required>
                        @foreach($sorts as $dailySort)
                            <option value="{{ $dailySort->id }}">{{ $dailySort->sort->description . ' - ' . $dailySort->sort->time_sort }}</option>
                        @endforeach
                    </select>
                </div>

                {{ csrf_field() }}

                <table class="table">
                    <tbody>
                    <tr ng-repeat="animal in data.animalsTicket">
                        <td width="10%">[[ $index + 1 ]]</td>
                        <td width="50%">[[ animal.id + ' - ' + animal.name ]]</td>
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
                        <i class="fa fa-save"></i> Guardar ticket
                    </button>
                </div>
            </form>

        </div>

    </div>

@endsection

@section('js')
    <script>
        var data = {
            animalsList : {!! json_encode($animals) !!},
            imgUrl : '{{ asset('img/animals') }}'
        };
    </script>
@endsection


