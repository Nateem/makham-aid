angular.module('app')
    .controller('customer_valueController', function($rootScope, $interval, $filter, $state, $scope, $http, notificationService) {

        $scope.form = {};
        //$scope.form.CURDATE = DATE.format('dddd, Do MMMM YYYY');
        $scope.form.CURDATE = new Date();
        $scope.dateConvert =  moment(new Date()).format("LLLL");
        $interval(function() {
            //console.log("setInterval");
            $scope.dateConvert = moment(new Date()).format("LLLL");
        }, 1000);
        var selectEventLast = function() {
            $http({
                    method: "POST",
                    url: 'models/customer_value.model.php',
                    data: {
                        TYPES: 'SELECT_customer_value',
                        CURRENT_DATA: $rootScope.globals.currentDATA
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.dataRepeat = data.DATA;
                    }
                });
        }
       selectEventLast();
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
        var companySelect = function(){
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
        var store_typeSelect = function(){
            $http({
                    method: "POST",
                    url: 'models/customer_value.model.php',
                    data: {
                        TYPES: 'SELECT_store_type',
                        CURRENT_DATA: $rootScope.globals.currentDATA
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.store_typeRepeat = data.DATA;
                    }
                });
        }
        var LoadInit = function() {
            $scope.detail = "กรอกเลขสมาชิก";
            $scope.form.CODE = "";
            $scope.form.VALUE = 0;
            
            /*
            $(function() {
                $('#CURDATE').daterangepicker({
                    format: 'dddd, Do MMMM YYYY',
                    singleDatePicker: true,
                    calender_style: "picker_1",
                    locale: $rootScope.daterangepickerLocale
                }, function(start, end, label) {
                    //console.log(start.toISOString(), end.toISOString(), label);
                });
            })*/
        }
        titleSelect();
        companySelect();
        store_typeSelect();
        LoadInit();
        /*
        $scope.drOptions = {
            //format: 'dddd, Do MMMM YYYY',
            singleDatePicker: true,
            calender_style: "picker_1",
            locale: $rootScope.daterangepickerLocale
        };
        */
        $scope.autofocusGencode = function() {
            //console.log("test");
            $(function() {
                $("#CUS_CODE").focus();
            })

        }
       
        $scope.formSave = function() {
            //console.log("formSave...");
            $http({
                    method: "POST",
                    url: 'models/customer_value.model.php',
                    data: {
                        TYPES: 'INSERT_customer_value',
                        FORM_DATA: $scope.form,
                        CURRENT_DATA: $rootScope.globals.currentDATA
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);

                    if (data.ERROR == false) {
                        selectEventLast();
                        LoadInit();
                        $scope.autofocusGencode();
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
        $scope.checkEnter = function(code){
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
                            console.log(data);
                            if (data.ERROR == false) {
                                $(function(){
                                    $("#inpVALUE").focus();
                                });
                            } 
                            else{
                                $rootScope.goSearch('CUS_CODE');
                            }
                            
                        });
                } else {
                    $rootScope.goSearch('CUS_CODE');
                }
            } else {
                $rootScope.goSearch('CUS_CODE');
            }
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
        $scope.deleteCustomer_value = function(ID) {
            notificationService.notify({
                title: 'ยืนยัน',
                text: 'คุณต้องการลบรายการนี้ใช่หรือไม่?',
                hide: false,
                styling: "bootstrap3",
                animate: {
                    animate: true,
                    in_class: 'bounceInLeft',
                    out_class: 'bounceOutRight'
                },
                confirm: {
                    confirm: true,
                },
                buttons: {
                    closer: false,
                    sticker: false
                },
                history: {
                    history: false
                }
            }).get().on('pnotify.confirm', function() {
                $http({
                        method: "POST",
                        url: 'models/customer_value.model.php',
                        data: {
                            TYPES: 'DELETE_customer_value',
                            CURRENT_DATA: $rootScope.globals.currentDATA,
                            customer_value_ID: ID,
                        },
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function(data) {
                        //console.log(data);
                        if (data.ERROR == false) {
                            selectEventLast(); 
                        }
                        notificationService.notify({
                            title: 'ระบบตอบรับ',
                            text: data.MSG,
                            styling: "bootstrap3",
                            type: data.TYPE,
                            icon: true
                        });
                    });
            }).on('pnotify.cancel', function() {
                //event
            });

        }
        
    })
