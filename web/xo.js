angular.module('xo', [
    'xo.controllers'
]);

angular.module('xo.controllers', []).
    controller('game', ['$scope', '$http' ,function($scope, $http) {

        var empty = function() {
            return [
                [null, null, null],
                [null, null, null],
                [null, null, null]
            ];
        };

        // initial table
        $scope.table = empty();

        $scope.winner = null;

        $scope.players = { X: 'Me', 'O': 'Drunk player' };

        /**
         * @param val
         */
        $scope.itemClass = function(val) {
            var classes = ['glyphicon', 'fa-5x', 'glyphicon-remove'];

            switch (val) {
                case 'X':
                    classes.push('glyphicon-remove');
                    break;
                case 'O':
                    classes.push('fa-circle-o');
                    classes.push('fa');
                    break;
                default:
                    classes.push('invisible');
                    break;
            }

            return classes;
        };

        $scope.setItem = function(row, col) {
            if ($scope.winner) {
                return;
            }
            $scope.table[row][col] = 'X';
            $scope.send();
        };

        $scope.send = function() {
            if ($scope.winner) {
                return;
            }
            $http({
                method: 'GET',
                url: 'index.php/turn.json',
                params: {
                    'table': angular.toJson($scope.table),
                    'X': $scope.players.X == 'Me' ? null : $scope.players.X,
                    'O': $scope.players.O
                }
            }).success(function(data) {
                $scope.table = data.table;
                $scope.winner = data.winner;
            });
        };

        $scope.reset = function() {
            $scope.table = empty();
            $scope.winner = null;
        };
    }]);