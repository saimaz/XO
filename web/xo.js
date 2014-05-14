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

        $scope.autoPlay = function() {
            if ($scope.players.X != 'Me') {
                $scope.reset();
                var counter = 0;
                var setGo = setInterval(function(){
                    $scope.send();
                    counter++;
                    console.log(counter);
                    if ($scope.winner) {
                        $scope.autoPlay();
                        counter = 0;
                        clearTimeout(setGo);
                    }
                    if (counter > 9) {
                        $scope.reset();
                        counter = 0;
                        alert('Found a bug no winner response from backend');
                        clearTimeout(setGo);
                    }
                },100);

            }
            else {
                $scope.winner = 'Select other Player for X';
            }
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
                console.log(data);
                $scope.winner = data.winner;
            });
        };

        $scope.reset = function() {
            $scope.table = empty();
            $scope.winner = null;
        };
    }]);