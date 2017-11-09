angular.module('app')
	.controller('reportList_companyController', function($rootScope,$filter,$scope,$http,notificationService){

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
        var companySelect = function() {
            $http({
                    method: "POST",
                    url: 'models/customer_value.model.php',
                    data: {
                        TYPES: 'SELECT_company',
                        CURRENT_DATA: $rootScope.globals.currentDATA
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.companyRepeat = data.DATA;
                    }
                });
        }
        companySelect();
        $scope.formSubmit = function(){
             $http({
                    method: "POST",
                    url: 'models/reportList_company.model.php',
                    data: {
                        TYPES: 'SELECT_reportList_company',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        FORM_DATA:$scope.form
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    console.log(data);
                    if (data.ERROR == false) {
                        
                        $scope.dataRepeat = data.DATA;
                        $scope.SUM_TOTAL = data.SUM_TOTAL;
                        $scope.DATE_START = data.DATE_START;
                        $scope.DATE_END = data.DATE_END;             
                        $scope.title_NAME = data.title_NAME;   
                        $scope.company_NAME = data.company_NAME;               
                    }
                    notificationService.notify({
                            title: 'ระบบตอบรับ',
                            text: data.MSG,
                            styling: "bootstrap3",
                            type: data.TYPE,
                            icon: true
                        });
                });

        }
        
	})