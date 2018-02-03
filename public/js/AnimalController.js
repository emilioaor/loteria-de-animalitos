angular.module('AnimalModule').controller('AnimalController', [
    '$scope',
    '$http',
    function($scope, $http) {

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

        $scope.getAnimalTicket = (id) => {
            for (let animal in $scope.data.animalsTicket) {
                if ($scope.data.animalsTicket[animal].id == id) {
                    return $scope.data.animalsTicket[animal];
                }
            }

            return null;
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

        $scope.$watch('newAnimal.number', function(newValue, oldValue) {
            if (newValue !== '' && ! parseInt(newValue)) {
                var animal = $scope.findByName(
                    newValue ? newValue : ''
                );

                if (animal) {
                    $scope.newAnimal.number = animal.number;
                    $scope.setFocus = true;
                }
            }
        });

        $scope.findByName = function (name) {
            var searchName = $scope.clearName(name);
            var animalName;
            var match = null;

            for (let animal in $scope.data.animalsList) {
                animalName = $scope.clearName($scope.data.animalsList[animal].name);

                if (animalName.indexOf(searchName) === 0) {

                    if (match && match.name !== animalName) {
                        return null;
                    }

                    match = $scope.data.animalsList[animal];
                }
            }

            return match;
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
            if ($scope.hasList($scope.newAnimal.number) && $scope.newAnimal.amount > 0) {
                let animal = $scope.getListAnimal($scope.newAnimal.number);
                if (! $scope.hasTicket(animal.id)) {
                    animal.amount = $scope.newAnimal.amount;
                    animal.error = {
                        required : false
                    };
                    $scope.data.animalsTicket.push(animal);
                } else {
                    // Si ya existe le acumulo el monto
                    $scope.getAnimalTicket(animal.id).amount += $scope.newAnimal.amount;
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

        $scope.searchRepeatTicket = function(url) {
            $scope.repeatLoading = true;
            url = url + '?search=' + $scope.filterTicket;

            $http.get(url).then(function(promise) {
                $scope.repeatTickets = promise.data;
                $scope.repeatLoading = false;
            }, function (err) {
                $scope.repeatTickets = [];
                $scope.repeatLoading = false;
            });
        };

        $scope.getAnimalsRepeat = function (ticket) {
            $scope.data.animalsTicket = [];
            for (var i in ticket.animals) {

                for (var x in $scope.data.animalsList) {
                    if ($scope.data.animalsList[x].id === ticket.animals[i].id) {
                        ticket.animals[i].limit = $scope.data.animalsList[x].limit;
                    }
                }

                ticket.animals[i].amount = ticket.animals[i].pivot.amount;
                $scope.data.animalsTicket.push(ticket.animals[i]);
            }
            $('#closeModalRepeat').click();
            $scope.getTotal();
        };

        $scope.saveTicket = function () {
            if (! $scope.submitted) {
                $scope.submitted = true;
                $('#formAnimal').submit();
            }
        };

        var interval = 1000;
        window.setInterval(function() {
            if ($scope.hours > 0 || $scope.minutes > 0 || $scope.seconds > 0) {
                if ($scope.seconds === 0) {

                    if ($scope.minutes === 0) {
                        $scope.minutes = 60;
                        $scope.hours--;
                    }

                    $scope.seconds = 60;
                    $scope.minutes--;
                }

                $scope.seconds--;
                $scope.$apply();
            } else {
                // Llego el contador a 0
                var check = $('#nextSortCheck');

                check.attr('disabled', 'disabled');
                $scope.data.sorts[check.data('sort-id')] = false;
                $scope.$apply();

                interval = 0;
            }
        }, interval);

        // Verifica el cambio de focus
        window.setInterval(function() {
            if ($scope.setFocus) {
                $('#newAnimalAmount').focus();
                $scope.setFocus = false;
                $scope.$apply();
            }
        }, 100);

        $scope.data = data;
        $scope.data.animalsTicket = [];
        $scope.newAnimal = {};
        $scope.styleAnimalAdd = {};
        $scope.total = 0;
        $scope.repeatLoading = false;
        $scope.repeatTickets = [];
        $scope.filterTicket = '';
        $scope.setFocus = false;
        $scope.submitted = false;
        $scope.seconds = seconds;
        $scope.minutes = 0;
        $scope.hours = 0;
        while ($scope.seconds >= 60) {
            $scope.seconds -= 60;
            $scope.minutes++;
        }
        while ($scope.minutes >= 60) {
            $scope.minutes -= 60;
            $scope.hours++;
        }
    }])
;