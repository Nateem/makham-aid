angular.module('app')
    .controller('reportByCustomerController', function($rootScope, $timeout, $location, $filter, $scope, $http) {


        var loadInit = function() {
            $scope.form = {};
            $scope.form.DateStart = $rootScope.GetCurrentDateTime;
            $scope.form.DateEnd = $rootScope.GetCurrentDateTime;
            $scope.form.AUMNO = '01';
            $scope.bindHtmlText = "<a href=\"#\">test<a>";
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
        var aumnoSelect = function() {
            $http({
                    method: "POST",
                    url: 'models/reportByCustomer.model.php',
                    data: {
                        TYPES: 'SELECT_aumno',
                        CURRENT_DATA: $rootScope.globals.currentDATA
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(json) {
                    //console.log(json);
                    if (json.ERROR == false) {
                        $scope.aumnoRepeat = json.DATA;
                    }
                });
        }
       
        aumnoSelect();
        $scope.uncomment = function(id,namefile){
            var elem = document.getElementById(id);
            $rootScope.uncommentNode(elem);
            $rootScope.exportData(id,namefile);
        }
        $scope.formSubmit = function(tableID){
             $scope.showhide = false;
             $http({
                    method: "POST",
                    url: 'models/reportByCustomer.html.model.php',
                    data: {
                        TYPES: 'SELECT_TABLE_2_HTML',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        FORM_DATA:$scope.form
                    },
                    headers: { 'Content-Type': 'text/plain' }
                })
                .success(function(html) {
                    //console.log(html);
                    $scope.bindHtmlText = html;
                    $scope.showhide = true;
                    /*
                    if (json.ERROR == false) {
                        $scope.dataRepeat = json.DATA;
                        $scope.title_NAME = json.title_NAME;                           
                    }*/
                });
        }
        $scope.show = function(id){
            $timeout(function(){
                var elem = document.getElementById(id);
                $rootScope.uncommentNode(elem);               
               
                
            }, 1);
        }
    })
