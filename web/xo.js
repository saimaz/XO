angular.module('xo', [
    'xo.controllers'
]);

angular.module('xo.controllers', []).
    controller('game', ['$scope', '$http' ,function($scope, $http) {

        // initial table
        $scope.table = [
            [null, null, null],
            [null, null, null],
            [null, null, null]
        ];

        $scope.winner = null;

        /**
         * @param val
         */
        $scope.itemClass = function(val) {
            var classes = ['fa', 'fa-5x', 'fa-ban'];

            switch (val) {
                case 'X':
                    classes.push('fa-ban');
                    break;
                case 'O':
                    classes.push('fa-circle-o');
                    break;
                default:
                    classes.push('invisible');
                    break;
            }

            return classes;
        };

        $scope.setItem = function(row, col) {
            $scope.table[row][col] = 'X';
            $scope.send();
        };

        $scope.send = function() {
            $http({
                method: 'GET',
                url: 'index.php/turn.json',
                params: { 'table': angular.toJson($scope.table) }
            }).success(function(data) {
                $scope.table = data.table;
                $scope.winner = data.winner;
            });
        };
    }]);