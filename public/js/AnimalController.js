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
        };

        $scope.removeFromTicket = (index) => {
            $scope.data.animalsTicket.splice(index, 1);
        };

        $scope.hasTicket = (id) => {
            for (let animal in $scope.data.animalsTicket) {
                if ($scope.data.animalsTicket[animal].id == id) {
                    return true;
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

        $scope.data = data;
        $scope.data.animalsTicket = [];
    }])
;