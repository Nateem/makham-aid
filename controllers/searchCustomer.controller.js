angular.module('app')
    .controller('searchCustomerController', function($rootScope, $scope, $compile, $timeout, $state, $stateParams, $http, DTOptionsBuilder, DTColumnBuilder, $q) {
        $scope.PARAMS = $stateParams.inpId;
        $scope.dtInstance = {};
        $scope.loadReady = function() {
            $scope.dtOptions = DTOptionsBuilder.fromFnPromise(function() {
                    var defer = $q.defer();
                    $http({
                            method: "POST",
                            url: 'models/searchCustomer.model.php',
                            data: {
                                TYPES: 'SELECT_customer',
                                CURRENT_DATA: $rootScope.globals.currentDATA
                            },
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        })
                        .success(function(data) {
                            //console.log(data);
                            if (data.ERROR == false) {
                                defer.resolve(data.DATA);
                            }
                        });
                    return defer.promise;
                })
                .withPaginationType('full')
                .withOption('responsive', true)
                .withDOM('frtip')
                .withOption('createdRow', createdRow);


            $scope.dtColumns = [
                DTColumnBuilder.newColumn(null).withTitle('').notSortable()
                .renderWith(actionsHtml),
                DTColumnBuilder.newColumn('MASCODE').withTitle('รหัสสมาชิก').withClass("text-center text-MASCODE"),
                DTColumnBuilder.newColumn('FULLNAME').withTitle('ชื่อ').withClass("text-MASCODE")

            ];

            function actionsHtml(data, type, full, meta) {
                return '<button class="btn btn-success btn-xs" ng-click="onChoose(\'' + full.MASCODE + '\')">' +
                    '   <i class="fa fa-hand-o-right" aria-hidden="true"></i>' +
                    '</button>';
            }

            function createdRow(row, data, dataIndex) {
                // Recompiling so we can bind Angular directive to the DT
                $compile(angular.element(row).contents())($scope);
            }

            $scope.onChoose = function(CODE) {
                console.log(CODE);
                var s = window.opener.angular.element("#" + $scope.PARAMS).scope();
                s.form.CODE = CODE;
                s.$apply();
                window.opener.angular.element("#" + $scope.PARAMS).focus();
                window.close();
            }

        }
        $scope.loadReady();
        
        


    })
