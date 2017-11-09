angular.module('app')
    .controller('programController', function($rootScope, $scope, $http, $filter, notificationService) {
        $scope.controllerName = 'programController';
        var DATE = moment(new Date());
        $scope.rangOptions = {
            opens: 'left',
            buttonClasses: ['btn btn-sm btn-default'],
            applyClass: 'btn-sm btn-primary',
            cancelClass: 'btn-sm btn-default',
            format: 'DD/MM/YYYY',
            separator: ' ถึง ',
            locale: $rootScope.daterangepickerLocale
        };
        $scope.loadLastProgram = function() {
            $http({
                    method: "POST",
                    url: 'models/program.model.php',
                    data: {
                        TYPES: 'SELECT_program',
                        CURRENT_DATA: $rootScope.globals.currentDATA
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    $scope.programRepeat = data.DATA;
                });
        }
        var loadDataInit = function() {
            $scope.form = {};
            $scope.form.DATERANG = { startDate: DATE.format(), endDate: DATE.format() };
            $scope.form.TITLE = "";
            $scope.form.DETAIL = "";
            $scope.loadLastProgram();
        }
        loadDataInit();

        
        $scope.chooseData = function(ID){
        	 $http({
                    method: "POST",
                    url: 'models/program.model.php',
                    data: {
                        TYPES: 'SELECT_program_WHERE',
                        ID:ID,
                        CURRENT_DATA: $rootScope.globals.currentDATA
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    console.log(data);
                    $scope.form.ID = ID;
                    $scope.form.DATERANG = { startDate: data.DATA.DATE_START, endDate: data.DATA.DATE_END };
		            $scope.form.TITLE = data.DATA.TITLE;
		            $scope.form.DETAIL = data.DATA.DETAIL;
                });
        }
        $scope.formSave = function(formName, Type) {
            if (formName.$invalid == false) {
                if (Type == "UPDATE") {
                    $http({
                            method: "POST",
                            url: 'models/program.model.php',
                            data: {
                                TYPES: 'UPDATE_program',
                                FORM_DATA: $scope.form,
                                CURRENT_DATA: $rootScope.globals.currentDATA
                            },
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        })
                        .success(function(data) {
                            //console.log(data);
                            notificationService.notify({
                                title: 'ระบบตอบรับ',
                                text: data.MSG,
                                styling: "bootstrap3",
                                type: data.TYPE,
                                icon: true
                            });
                            loadDataInit();
                            formName.$setPristine();
                        });
                } else if (Type == "INSERT") {
                    $http({
                            method: "POST",
                            url: 'models/program.model.php',
                            data: {
                                TYPES: 'INSERT_program',
                                FORM_DATA: $scope.form,
                                CURRENT_DATA: $rootScope.globals.currentDATA
                            },
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        })
                        .success(function(data) {
                            //console.log(data);
                            notificationService.notify({
                                title: 'ระบบตอบรับ',
                                text: data.MSG,
                                styling: "bootstrap3",
                                type: data.TYPE,
                                icon: true
                            });
                            loadDataInit();
                            formName.$setPristine();
                        });
                }
            } else {
                notificationService.notify({
                    title: 'ระบบตอบรับ',
                    text: "กรุณาทำตามเงื่อนไขให้ครบถ้วน",
                    styling: "bootstrap3",
                    type: "warning",
                    icon: true
                });
            }
        }
        $scope.deleteData = function(ID){
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
                        url: 'models/program.model.php',
                        data: {
                            TYPES: 'DELETE_program',
                            CURRENT_DATA: $rootScope.globals.currentDATA,
                            ID:ID,
                        },
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function(data) {
                        console.log(data);
                        if (data.ERROR == false) {
                            $scope.loadLastProgram();
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
        $scope.btnReset = function(formName) {
            loadDataInit();
            formName.$setPristine();
        }
    })
