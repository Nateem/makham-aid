angular.module('app')
    .controller('editController', function($rootScope, $scope, $filter, $compile, DTOptionsBuilder, DTColumnBuilder, $http, $q, notificationService) {
        var loadInit = function() {
            $scope.form = {};
            $scope.detail = "เลือกรายการขวามือ";
            $scope.form.CURDATE  = $rootScope.GetCurrentDateTime;
        }
        loadInit();
        var vm = this;
        vm.message = '';
        
        vm.form = edit;
        vm.persons = {};
        vm.dtOptions = DTOptionsBuilder.fromFnPromise(function() {
                var defer = $q.defer();
                $http({
                        method: "POST",
                        url: 'models/edit.model.php',
                        data: {
                            TYPES: 'SELECT_customer_value',
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
            .withPaginationType('full_numbers')
            .withOption('responsive', true)
            .withOption('createdRow', createdRow);


        vm.dtColumns = [
            DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
            .renderWith(actionsHtml),
            DTColumnBuilder.newColumn('CURDATE').withTitle('วันที่').renderWith(function(data, type, full) {
                return $filter('amDateFormat')(data, 'ddd , DD/MM/YYYY'); //could use currency/date or any angular filter
            }), ,
            DTColumnBuilder.newColumn('MASCODE').withTitle('รหัส'),
            DTColumnBuilder.newColumn('customer_NAME').withTitle('สมาชิก'),
            DTColumnBuilder.newColumn('VALUE').withTitle('ยอดเงิน').withClass('text-right').renderWith(function(data, type, full) {
                return $filter('currency')(data, 'THB '); //could use currency/date or any angular filter
            }), ,
            DTColumnBuilder.newColumn('title_NAME').withTitle('หัวข้อ'),
            DTColumnBuilder.newColumn('company_NAME').withTitle('บริษัท/ห้างร้าน'),
            DTColumnBuilder.newColumn('store_type_NAME').withTitle('ประเภทสินค้า'),
            DTColumnBuilder.newColumn('CREATED').withTitle('เมื่อ').renderWith(function(data, type, full) {
                return $filter('amDateFormat')(data, 'DD/MM/YYYY HH:mm น'); //could use currency/date or any angular filter
            }), ,
            DTColumnBuilder.newColumn('user_NAME').withTitle('โดย'),

        ];
        vm.dtInstance = {};
        vm.reloadData = reloadData;

        function reloadData() {
            vm.dtInstance.reloadData();
        };

        function createdRow(row, data, dataIndex) {
            // Recompiling so we can bind Angular directive to the DT
            $compile(angular.element(row).contents())($scope);
        }

        function actionsHtml(data, type, full, meta) {
            return '<button class="btn btn-warning" ng-click="showCase.form(' + full.ID + ')">' +
                '<i class="fa fa-hand-o-left" aria-hidden="true"></i>' +
                '</button>';
        }

        function edit(id) {
            //console.log(id);
            $scope.form.ID = id;
            $http({
                    method: "POST",
                    url: 'models/edit.model.php',
                    data: {
                        TYPES: 'SELECT_customer_value_where',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        customer_value_ID: id
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.form.ID = data.DATA.ID;
                        $scope.form.title_ID = data.DATA.title_ID;
                        $scope.form.company_ID = data.DATA.company_ID;
                        $scope.form.store_type_ID = data.DATA.store_type_ID;
                        $scope.form.CURDATE = new Date(data.DATA.CURDATE);
                        $scope.form.CUS_CODE = data.DATA.MASCODE;
                        $scope.form.VALUE = Number(data.DATA.VALUE);
                        //$scope.chkCode(data.DATA.CUS_CODE);
                        $(function() {
                            $("#CUS_CODE").focus();
                        })
                    }
                });
        }
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
        var store_typeSelect = function() {
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
        titleSelect();
        companySelect();
        store_typeSelect();
        $scope.chkCode = function(code) {
            if (code) {
                if (code.length == 7) {
                    $http({
                            method: "POST",
                            url: 'models/edit.model.php',
                            data: {
                                TYPES: 'SELECT_customer_code',
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
                        });
                } else {
                    $scope.detail = "เลขสมาชิกมีจำนวน 7 หลัก";
                }
            } else {
                $scope.detail = "กรอกเลขสมาชิก";
            }

        }
        $scope.formEditSubmit = function(myform) {
            //console.log('test....');
            $http({
                    method: "POST",
                    url: 'models/edit.model.php',
                    data: {
                        TYPES: 'UPDATE_customer_value',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        FORM_DATA: $scope.form,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        loadInit();
                        myform.$setPristine();
                        vm.dtInstance.reloadData();
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
        $scope.deleteData = function(myform, ID) {
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
                        url: 'models/edit.model.php',
                        data: {
                            TYPES: 'DELETE_customer_value',
                            CURRENT_DATA: $rootScope.globals.currentDATA,
                            FORM_DATA: $scope.form,
                        },
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function(data) {
                        //console.log(data);
                        if (data.ERROR == false) {
                            loadInit();
                            myform.$setPristine();
                            vm.dtInstance.reloadData();
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
