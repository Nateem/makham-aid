'use strict';

// declare modules
angular.module('Authentication', []);
var menu_json = [{
        "name": "home",
        "url": "/",
        "templateUrl": "views/home.view.html",
        'controller': 'homeController',
        "TH_name": "หน้าแรก",
        "EN_name": "Home",
        "menu_hide": false,
        "ICO_CLASS": './img/icon/Barn-96.png'
    }, {
        "name": "searchCustomer",
        "url": "/searchCustomer/:inpId",
        "templateUrl": "views/searchCustomer.view.html",
        'controller': 'searchCustomerController',
        "TH_name": "ค้นหาสมาชิก",
        "EN_name": "Search Customer",
        "menu_hide": true
    }, {
        "TH_name": "รายการ",
        "EN_name": "Data",
        "ICO_CLASS": './img/icon/Insert Table-96.png',
        "dropdown": [{
            "name": "customer_value",
            "url": "/customer_value",
            "templateUrl": "views/customer_value.view.html",
            'controller': 'customer_valueController',
            "TH_name": "รับข้อมูล",
            "EN_name": "Customer Value",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-arrow-circle-down'
        }, {
            "name": "edit",
            "url": "/edit",
            "templateUrl": "views/edit.view.html",
            'controller': 'editController',
            "TH_name": "แก้ไข/ยกเลิก",
            "EN_name": "Edit Data",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-pencil-square-o'
        }]
    }, {
        "TH_name": "รายงาน",
        "EN_name": "Report Oil Data",
        "ICO_CLASS": './img/icon/Print-96.png',
        "dropdown": [{
            "name": "reportSome_customer",
            "url": "/reportSome_customer",
            "templateUrl": "views/reportSome_customer.view.html",
            'controller': 'reportSome_customerController',
            "TH_name": "เฉพาะบุคคลแยกประเภท",
            "EN_name": "Report Some Customer",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-user'
        },{
            "name": "reportList_customer_value",
            "url": "/reportList_customer_value",
            "templateUrl": "views/reportList_customer_value.view.html",
            'controller': 'reportList_customer_valueController',
            "TH_name": "เฉพาะบุคคลแยกรายการ",
            "EN_name": "Report List",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-user'
        },{
            "name": "reportList_company",
            "url": "/reportList_company",
            "templateUrl": "views/reportList_company.view.html",
            'controller': 'reportList_companyController',
            "TH_name": "บริษัท/ห้างร้านแยกรายการ",
            "EN_name": "Report List Company",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-list-ol'
        },{
            "name": "reportByCustomer",
            "url": "/reportByCustomer",
            "templateUrl": "views/reportByCustomer.view.html",
            'controller': 'reportByCustomerController',
            "TH_name": "รายละเอียด",
            "EN_name": "Report By Customer",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-area-chart'
        }, {
            "name": "reportSum_company",
            "url": "/reportSum_company",
            "templateUrl": "views/reportSum_company.view.html",
            'controller': 'reportSum_companyController',
            "TH_name": "ตามบริษัท/ห้างร้าน",
            "EN_name": "Report Sum Company",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-bar-chart'
        }, {
            "name": "reportSum_store_type",
            "url": "/reportSum_store_type",
            "templateUrl": "views/reportSum_store_type.view.html",
            'controller': 'reportSum_store_typeController',
            "TH_name": "ตามประเภทสินค้า",
            "EN_name": "Report Sum StoreType",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-bar-chart'
        }, {
            "name": "reportGroup",
            "url": "/reportGroup",
            "templateUrl": "views/reportGroup.view.html",
            'controller': 'reportGroupController',
            "TH_name": "ตามกลุ่มสมาชิก",
            "EN_name": "Report Group",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-users'
        }, {
            "name": "reportMaxCustomer",
            "url": "/reportMaxCustomer",
            "templateUrl": "views/reportMaxCustomer.view.html",
            'controller': 'reportMaxCustomerController',
            "TH_name": "สมาชิกซื้อมาก",
            "EN_name": "Report Max Price Customer",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-line-chart'
        }, {
            "name": "reportMaxCompany",
            "url": "/reportMaxCompany",
            "templateUrl": "views/reportMaxCompany.view.html",
            'controller': 'reportMaxCompanyController',
            "TH_name": "บริษัท/ห้างร้านขายมาก",
            "EN_name": "Report Company Max Sell",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-line-chart'
        }]
    }, {
        "TH_name": "จัดการ",
        "EN_name": "Setting",
        "ICO_CLASS": './img/icon/Settings-100.png',
        "dropdown": [{
            "name": "setTitle",
            "url": "/setTitle",
            "templateUrl": "views/setTitle.view.html",
            'controller': 'setTitleController',
            "TH_name": "หัวข้อ",
            "EN_name": "Setting Title",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-calendar'
        }, {
            "name": "setCompany",
            "url": "/setCompany",
            "templateUrl": "views/setCompany.view.html",
            'controller': 'setCompanyController',
            "TH_name": "บริษัท/ห้างร้าน",
            "EN_name": "Setting Company",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-university'
        }, {
            "name": "setStore_type",
            "url": "/setStore_type",
            "templateUrl": "views/setStore_type.view.html",
            'controller': 'setStore_typeController',
            "TH_name": "ประเภทสินค้า",
            "EN_name": "Setting Store Type",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-envira'
        }, {
            "name": "setCustomer",
            "url": "/setCustomer",
            "templateUrl": "views/setCustomer.view.html",
            'controller': 'setCustomerController',
            "TH_name": "สมาชิก",
            "EN_name": "Setting Customer",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-users'
        }, {
            "name": "addUser",
            "url": "/addUser",
            "templateUrl": "views/addUser.view.html",
            'controller': 'addUserController',
            "TH_name": "เพิ่มผู้ใช้งาน",
            "EN_name": "User Add",
            "menu_hide": false,
            "ICO_CLASS": 'fa fa-user-plus'
        }]
    }


];
angular.module('app', [
        'Authentication',
        'ui.router',
        'ngCookies',
        'chart.js',
        'ui.notify',
        'ngFileUpload',
        'ngTagsInput',
        'monospaced.qrcode',
        'ngLoadingSpinner',
        'ckeditor',
        'datatables',
        'datatables.buttons',
        'daterangepicker',
        'angularMoment',
        'ngSanitize'
    ])
    .config(['$stateProvider', '$urlRouterProvider', 'notificationServiceProvider', function($stateProvider, $urlRouterProvider, notificationServiceProvider) {

        notificationServiceProvider.setDefaults({
            history: false,
            delay: 4000,
            styling: 'bootstrap3',
            closer: false,
            closer_hover: false
        });

        var spd = $stateProvider;
        var funcController = function($rootScope, $scope, $state) {
            $scope.stateName = $state.current.data.stateName;
            $rootScope.globals.stateName = $state.current.data.stateName;
            $scope.stateICO_CLASS = $state.current.data.stateICO_CLASS;
        }

        //$rootScope.menu_json = menu_json;
        angular.forEach(menu_json, function(value1, key) {

            if (!value1.name) {

                angular.forEach(value1.dropdown, function(value2, key2) {
                    if (!value2.name) {
                        angular.forEach(value2.dropdown, function(value3, key3) {
                            spd.state({
                                name: value3.name,
                                url: value3.url,
                                templateUrl: value3.templateUrl,
                                data: {
                                    stateName: value3.TH_name,
                                    stateICO_CLASS: value3.ICO_CLASS
                                },
                                controller: funcController
                            });
                        });
                    } else {
                        spd.state({
                            name: value2.name,
                            url: value2.url,
                            templateUrl: value2.templateUrl,
                            data: {
                                stateName: value2.TH_name,
                                stateICO_CLASS: value2.ICO_CLASS
                            },
                            controller: funcController
                        });
                    }

                });

            } else {

                spd.state({
                    name: value1.name,
                    url: value1.url,
                    templateUrl: value1.templateUrl,
                    data: {
                        stateName: value1.TH_name,
                        stateICO_CLASS: value1.ICO_CLASS
                    },
                    controller: funcController
                });

            }

        });
        spd.state({
            name: 'register',
            url: '/register',
            templateUrl: 'views/register.view.html',
            data: {
                stateName: 'ลงทะเบียน',
                stateICO_CLASS: 'fa fa-registered'
            },
            controller: funcController
        });
        spd.state({
            name: 'login',
            url: '/login',
            templateUrl: 'modules/authentication/views/login.view.html',
            data: {
                stateName: 'เข้าสู่ระบบ',
                stateICO_CLASS: 'fa fa-sign-in'
            },
            controller: funcController
        });
        spd.state({
            name: 'currentUser',
            url: '/currentUser',
            templateUrl: 'views/currentUser.view.html',
            data: {
                stateName: 'ข้อมูลผู้ใช้งาน',
                stateICO_CLASS: 'fa fa-user'
            },
            controller: funcController
        });
        spd.state({
            name: 'error404',
            url: '/error404',
            templateUrl: 'views/error404.view.html',
            data: {
                stateName: 'ไม่พบหน้าที่ร้องขอ',
                stateICO_CLASS: 'fa fa-exclamation-triangle'
            },
            controller: funcController
        });
        $urlRouterProvider.otherwise('/');
    }])
    /*
    .config(['$routeProvider', function ($routeProvider) {

        $routeProvider
            .when('/login', {
                controller: 'LoginController',
                templateUrl: 'modules/authentication/views/login.html',
                hideMenus: true
            })
     
            .when('/', {
                controller: 'HomeController',
                templateUrl: 'modules/home/views/home.html'
            })
     
            .otherwise({ redirectTo: '/login' });
    }])
     */
    .directive('myEnter', function() {
        return function(scope, element, attrs) {
            element.bind("keydown keypress", function(event) {
                if (event.which === 13) {
                    scope.$apply(function() {
                        scope.$eval(attrs.myEnter);
                    });

                    event.preventDefault();
                }
            });
        };
    })
    .directive("repeatEnd", function(){
            return {
                restrict: "A",
                link: function (scope, element, attrs) {
                    if (scope.$last) {
                        scope.$eval(attrs.repeatEnd);
                    }
                }
            };
    })
    .run(function($rootScope, $cookieStore, $http, $location, notificationService, $state, amMoment) {
        //$rootScope.pathHost = $location.protocol()+ '://' + location.host + "/makham_bangchak";
        $rootScope.WebAppData = {
            "APPNAME":"AID",
            "NAME": "สหกรณ์การเกษตร มะขาม จำกัด",
            "ADDRESS": "229/2 ม.1 ต.มะขาม อ.มะขาม จ.จันทบุรี 22150",
            "TAX": "",
            "LOGO_URL": "./img/logo/%5BOriginal%5Dlogo_color.png",
            "PHONE": "039 389 095",
            "CREDIT": {
                "NAME": "Natee Puechpean",
                "TEL" : "080-633-8269"
            },
        };
        amMoment.changeLocale('th');
        $rootScope.goSearch = function(inpId) {
            //console.log(inpId);
            var w = 700;
            var h = 700;
            var title = inpId;
            var url = $state.href("searchCustomer", { inpId: inpId });
            var left = (screen.width / 2) - (w / 2);
            var top = (screen.height / 2) - (h / 1.5);
            var win = window.open(url, title, 'toolbar=no, location=0, directories=0, status=0, menubar=0, scrollbars=0, resizable=0, copyhistory=0, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
            win.focus();
        }
        $rootScope.daterangepickerLocale = {
            applyLabel: '<i class="fa fa-check"></i> ยืนยัน',
            cancelLabel: 'ปิด',
            fromLabel: 'เริ่ม',
            toLabel: 'ถึง',
            format: 'DD/MM/YYYY',
            customRangeLabel: 'กำหนดเอง',
            daysOfWeek: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
            monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
            firstDay: 1
        };
        $rootScope.pathHost = $location.protocol() + '://' + location.host;
        $rootScope.CURDATE = new Date();
        // keep user logged in after page refresh.    
        $rootScope.PrintElem = function(elem) {
            var w = screen.width;
            var h = screen.height;
            var title = 'PRINT';

            var left = (screen.width / 2) - (w / 2);
            var top = (screen.height / 2) - (h / 1.5);

            var mywindow = window.open('', title, 'toolbar=no, location=0, directories=0, status=0, menubar=0, scrollbars=0, resizable=0, copyhistory=0, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
            mywindow.document.write('<html><head><title>' + document.title + '</title>');
            mywindow.document.write('<style media="print">*{font-family:"Browallia New",tahoma,Arial, "times New Roman";font-size:22px;}.text-right{text-align:right;}.text-center{text-align:center;}.head_page{ text-align:center;}html {    font-family:"Browallia New",tahoma,Arial, "times New Roman";    font-size:22px;    color:#000000;}body {    font-family:"Browallia New",tahoma,Arial, "times New Roman";    font-size:22px;    padding:0;    margin:0;    color:#000000;}@media all{.page-break { display:none; }.page-break-no{display:none;}}@media print{.page-break { display:block;height:1px; page-break-before:always; }    .page-break-no{ display:block;height:1px; page-break-after:avoid; }}</style>');
            mywindow.document.write('</head><body><div class="">');

            mywindow.document.write(document.getElementById(elem).innerHTML);

            mywindow.document.write('</div>');

            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
            setTimeout(function() {
                mywindow.print();
                mywindow.close();
            }, 0);


            return true;
        }
        $rootScope.uncommentNode = function (node) {
            //console.log(node);
            var nodestr = node.innerHTML;

            var noderevealHTML =  nodestr.replace(/<!--([\s\S]*?)-->/mig, '');
            node.innerHTML = noderevealHTML;
        }
        $rootScope.tableToExcel = function(table,name) {
            var uri = 'data:application/vnd.ms-excel;base64,',
                template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>',
                base64 = function(s) {
                    return window.btoa(unescape(encodeURIComponent(s)))
                },
                format = function(s, c) {
                    return s.replace(/{(\w+)}/g, function(m, p) {
                        return c[p];
                    })
                }
            if (!table.nodeType) table = document.getElementById(table)
            $rootScope.uncommentNode(table);    
            var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML }
            window.location.href = uri + base64(format(template, ctx))  
        }
        $rootScope.exportData = function (exportable,name) {
        var blob = new Blob([document.getElementById(exportable).innerHTML], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });

            saveAs(blob, name + '.xls');
        };
        $rootScope.$HideNav = function() {
            $(function() {
                var $BODY = $('body'),
                    $MENU_TOGGLE = $('#menu_toggle');
                $("a.link").click(function() {
                    if ($BODY.hasClass('nav-sm')) {
                        $MENU_TOGGLE.click();
                    }
                });
            })
        }
        var offset = 7; //bangkok thailand +7
        $rootScope.GetCurrentDateTime = new Date(new Date().getTime() + offset * 3600 * 1000);
        $rootScope.$HideNav();

        var globals = $cookieStore.get('globals') || {};
        $rootScope.globals = globals;
        if (globals.currentUser) {
            $http.defaults.headers.common['Authorization'] = 'Basic ' + globals.currentUser.authdata; // jshint ignore:line
        }
        $rootScope.$on('$locationChangeStart', function(event, next, current) {
            // redirect to login page if not logged in

            if ($state.current.name !== 'login' && !$rootScope.globals.currentUser && $state.current.name !== 'register') {
                event.preventDefault();
                $state.go('login');
                notificationService.notify({
                    title: 'คำเตือน',
                    text: 'Please login! | กรุณาเข้าสู่ระบบ',
                    styling: "bootstrap3",
                    type: "warning",
                    icon: true
                });
            }
            // console.log($state.current.name);
        });

    })
    .constant('angularMomentConfig', {
        timezone: 'Asia/Bangkok' // e.g. 'Europe/London'
    });
