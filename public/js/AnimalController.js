angular.module('AnimalModule').controller('AnimalController', [
    '$scope',
    function($scope) {

        $scope.addToTicket = (animal) => {
            animal.amount = 0;
            animal.error = {
                required : false
            };
            $scope.data.animalsTicket.push(animal);
            $scope.getTotal();
        };

        $scope.removeFromTicket = (index) => {
            $scope.data.animalsTicket.splice(index, 1);
            $scope.getTotal();
        };

        $scope.hasTicket = (id) => {
            for (let animal in $scope.data.animalsTicket) {
                if ($scope.data.animalsTicket[animal].id == id) {
                    return true;
                }
            }

            return false;
        };

        $scope.hasList = (number) => {
            for (let animal in $scope.data.animalsList) {
                if ($scope.data.animalsList[animal].number == number) {
                    $scope.styleAnimalAdd = {};
                    return true;
                }
            }

            $scope.styleAnimalAdd = {
                'border-color' : '#c9302c',
            };

            return false;
        };

        $scope.getListAnimal = (number) => {
            for (let animal in $scope.data.animalsList) {
                if ($scope.data.animalsList[animal].number == number) {
                    return $scope.data.animalsList[animal]
                }
            }

            return false;
        };

        $scope.clearName = (name) => {
            let animalName = name.toLowerCase();
            animalName = animalName.replace('á', 'a');
            animalName = animalName.replace('é', 'e');
            animalName = animalName.replace('í', 'i');
            animalName = animalName.replace('ó', 'o');
            animalName = animalName.replace('ú', 'u');

            return animalName;
        };
        
        $scope.printIfHasList = function () {
            if ($scope.hasList($scope.newAnimal.number)) {
                return $scope.getListAnimal($scope.newAnimal.number).name;
            }

            return '-';
        };

        $scope.keyToGoAmount = function (evt) {
            if (evt.keyCode == 13) {
                $('#newAnimalAmount').focus();
            }
        };

        $scope.keyToAddNewAnimal = function (evt) {
            if (evt.keyCode == 13) {
                $scope.addNewAnimal();
            }
        };

        $scope.addNewAnimal = function () {
            moveScroll = true;
            if ($scope.hasList($scope.newAnimal.number) && $scope.newAnimal.amount > 0) {
                let animal = $scope.getListAnimal($scope.newAnimal.number);
                if (! $scope.hasTicket(animal.id)) {
                    animal.amount = $scope.newAnimal.amount;
                    animal.error = {
                        required : false
                    };
                    $scope.data.animalsTicket.push(animal);
                }
                $scope.newAnimal = {};
                $('#newAnimalNumber').focus();
            }
            $scope.getTotal();
        };
        
        $scope.hasSelectedSort = function () {
            let sorts = $scope.data.sorts;
            for (let i in sorts) {
                if (sorts[i]) {
                    return true;
                }
            }
            return false;
        };

        $scope.getTotal = function() {
            var total = 0;
            var animal;
            var activeSorts = 0;
            var amount;

            // Cuento los sorteos activos
            for (var s in $scope.data.sorts) {
                if ($scope.data.sorts[s]) {
                    activeSorts++;
                }
            }
            // Acumulo el monto de los animalitos seleccionados
            for (var i in $scope.data.animalsTicket) {
                animal = $scope.data.animalsTicket[i];

                if (parseFloat(animal.amount)) {
                    amount = animal.amount * activeSorts;
                    total += animal.amount;

                    if (amount > animal.limit) {
                        animal.limitError = true;
                    } else {
                        animal.limitError = false;
                    }
                }
            }

            $scope.total =  total * activeSorts;
        };

        $scope.hasLimitError = function() {
            var animal;
            var activeSorts = 0;
            // Cuento los sorteos activos
            for (var s in $scope.data.sorts) {
                if ($scope.data.sorts[s]) {
                    activeSorts++;
                }
            }

            for (var i in $scope.data.animalsTicket) {
                animal = $scope.data.animalsTicket[i];

                if (animal.limitError) {
                    return true;
                }
            }

            return false;
        };

        $scope.data = data;
        $scope.data.animalsTicket = [];
        $scope.newAnimal = {};
        $scope.styleAnimalAdd = {};
        $scope.total = 0;
    }])
;