angular.module('app')
    .controller('setCompanyController', function($rootScope, $scope, $filter, $compile, DTOptionsBuilder, DTColumnBuilder, $http, $q, notificationService) {
        var loadInit = function() {
            $scope.form = {};
            $scope.detail = "เลือกรายการขวามือ";
        }
        loadInit();
        var vm = this;
        vm.message = '';
        
        vm.form = setCompany;
        vm.persons = {};
        vm.dtOptions = DTOptionsBuilder.fromFnPromise(function() {
                var defer = $q.defer();
                $http({
                        method: "POST",
                        url: 'models/setCompany.model.php',
                        data: {
                            TYPES: 'SELECT_company',
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
            .withOption('createdRow', createdRow)
            .withButtons([{
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel',
                    className:"btn-success", 
                }, {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> พิมพ์', 
                    className:"btn-danger"
                }
            ]);


        vm.dtColumns = [
            DTColumnBuilder.newColumn(null).withTitle('').notSortable()
            .renderWith(actionsHtml),
            DTColumnBuilder.newColumn('NAME_FULL').withTitle('ชื่อเต็ม'), 
            DTColumnBuilder.newColumn('NAME_SHORT').withTitle('ชื่อย่อ'),
            DTColumnBuilder.newColumn('ADDSS').withTitle('ที่อยู่'),          
            DTColumnBuilder.newColumn('TEL').withTitle('เบอร์โทรศัพท์'),           
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
                ' <i class="fa fa-hand-o-left" aria-hidden="true"></i>' +
                '</button>';
        }

        function setCompany(id) {
            //console.log(id);
            $scope.form.ID = id;
            $http({
                    method: "POST",
                    url: 'models/setCompany.model.php',
                    data: {
                        TYPES: 'SELECT_company_where',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        company_ID: id
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.form.ID = data.DATA.ID;
                        $scope.form.NAME_FULL = data.DATA.NAME_FULL;
                        $scope.form.NAME_SHORT = data.DATA.NAME_SHORT;
                        $scope.form.ADDSS = data.DATA.ADDSS;
                        $scope.form.TEL = data.DATA.TEL;
                        
                    }
                });
        }

        
        $scope.formSettingSubmit = function(myform,submitType) {
            if(submitType=="INSERT"){
                $http({
                    method: "POST",
                    url: 'models/setCompany.model.php',
                    data: {
                        TYPES: 'INSERT_company',
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
            else if(submitType=="UPDATE"){
                $http({
                    method: "POST",
                    url: 'models/setCompany.model.php',
                    data: {
                        TYPES: 'UPDATE_company',
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
                        url: 'models/setCompany.model.php',
                        data: {
                            TYPES: 'DELETE_company',
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
