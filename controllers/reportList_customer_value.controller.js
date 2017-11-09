angular.module('app')
	.controller('reportList_customer_valueController', function($rootScope,$filter,$scope,$http,notificationService){

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
                    url: 'models/reportList_customer_value.model.php',
                    data: {
                        TYPES: 'SELECT_reportList_customer_value',
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
                        $scope.customer_NAME = data.customer_NAME;               
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
        $scope.chkCode = function(code) {
            if (code) {
                if (code.length == 7) {
                    $http({
                            method: "POST",
                            url: 'models/customer_value.model.php',
                            data: {
                                TYPES: 'SELECT_customer',
                                CURRENT_DATA: $rootScope.globals.currentDATA,
                                CUS_CODE: code
                            },
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        })
                        .success(function(data) {
                            //console.log(data);
                            if (data.ERROR == false) {
                                $scope.detail = data.DATA.STNA + data.DATA.CUS_FNAME + ' ' + data.DATA.CUS_LNAME;
                            } else {
                                $scope.detail = data.MSG;
                            }
                            /*
                            notificationService.notify({
                                text: "<h2>" + $scope.detail + "</h2>",
                                styling: "bootstrap3",
                                type: "info",
                                animate: {
                                    animate: true,
                                    in_class: 'bounceInRight',
                                    out_class: 'bounceOut'
                                },
                                icon: 'fa fa-user fa-3x',
                                width: "40%",
                                nonblock: {
                                    nonblock: true
                                }
                                
                            });*/
                        });
                } else {
                    $scope.detail = "เลขสมาชิกมีจำนวน 7 หลัก";
                }
            } else {
                $scope.detail = "กรอกเลขสมาชิก";
            }

        }
	})