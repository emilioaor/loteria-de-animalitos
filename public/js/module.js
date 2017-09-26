angular.module('AnimalModule', [])
    .config(['$interpolateProvider', function ($interpolateProvider) {

        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    }])
;