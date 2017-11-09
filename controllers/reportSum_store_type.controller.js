angular.module('app')
	.controller('reportSum_store_typeController', function($rootScope,$filter,$scope,$http){

		var loadInit = function() {
            $scope.form = {};
            $scope.form.DateStart = $rootScope.GetCurrentDateTime;
            $scope.form.DateEnd = $rootScope.GetCurrentDateTime;
        };
        loadInit();
        var titleSelect = function() {
            $http({
                    method: "POST",
                    url: 'models/customer_value.model.php',
                    data: {
                        TYPES: 'SELECT_title',
                        CURRENT_DATA: $rootScope.globals.currentDATA
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.titleRepeat = data.DATA;
                    }
                });
        }
        titleSelect();
        $scope.formSubmit = function(){
             $http({
                    method: "POST",
                    url: 'models/reportSum_store_type.model.php',
                    data: {
                        TYPES: 'SELECT_reportSum_store_type',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        FORM_DATA:$scope.form
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.dataRepeat = data.DATA;
                        $scope.SUM_TOTAL = data.SUM_TOTAL;
                        $scope.DATE_START = data.DATE_START;
                        $scope.DATE_END = data.DATE_END;                                 
                        $scope.title_NAME = data.title_NAME;                   
                    }
                });
        }
	})