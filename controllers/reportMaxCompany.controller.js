angular.module('app')
    .controller('reportMaxCompanyController', function($rootScope,$filter,$scope,$http){

        var loadInit = function() {
            $scope.form = {};
            $scope.form.DateStart = $rootScope.GetCurrentDateTime;
            $scope.form.DateEnd = $rootScope.GetCurrentDateTime;
            $scope.form.TOP_NUM = 5;
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
                    url: 'models/reportMaxCompany.model.php',
                    data: {
                        TYPES: 'SELECT_reportMaxCompany',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        FORM_DATA:$scope.form
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.dataRepeat = data.DATA;  
                        $scope.data2Repeat = data.DATA2;                          
                        $scope.TOP_NUM = data.TOP_NUM;
                        $scope.title_NAME = data.title_NAME;                       
                    }
                });
        }
    })