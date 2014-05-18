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

        $scope.setGo;
        $scope.uiTest = false;

        $scope.scoreMap = function() { return {
            w:0,
            wp: 0,
            d:0,
            dp:0,
            l:0,
            lp:0,
            c:0
        }};
        $scope.score = $scope.scoreMap();

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
        $scope.stopAutoPlay = function() {
            //$scope.players.X = 'Me';
            $scope.uiTest = false;
        };

        $scope.autoPlay = function() {
            if ($scope.players.X != 'Me') {
                $scope.uiTest = true;
                $scope.reset();
                var counter = 0;
                $scope.setGo = setInterval(function(){
                    if ($scope.uiTest) {
                        $scope.send();
                        counter++;
                        if ($scope.winner) {
                            $scope.autoPlay();
                            counter = 0;
                            clearTimeout($scope.setGo);
                        }
                        if (counter > 9) {
                            counter = 0;
                            $scope.autoPlay();
                            clearTimeout($scope.setGo);
                        }
                    }
                    else {
                        clearTimeout($scope.setGo);
                    }

                },16);

            }
            else {
                clearTimeout($scope.setGo);
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
                $scope.score.c++;
                if (data.winner == 'X') {
                    $scope.score.w++;
                }
                else if(data.winner == 'O') {
                    $scope.score.l++;

                }
                //else if(data.winner !== "D") {
                    //$scope.score.d++;
                //}
                $scope.score.wp = parseInt($scope.score.w * 100 / $scope.score.c);
                $scope.score.lp = parseInt($scope.score.l * 100 / $scope.score.c);
                //$scope.score.dp = parseInt($scope.score.d * 100 / $scope.score.c);
                $scope.winner = data.winner;
            });
        };

        $scope.reset = function() {
            $scope.table = empty();

            $scope.winner = null;
        };
    }]);