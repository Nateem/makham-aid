angular.module('app')
    .controller('currentUserController', function($rootScope, $scope,$http) {
        var loadInit = function() {
            $scope.form = {};            
        }
        loadInit();
        var SelectUser = function(){
            $http({
                    method: "POST",
                    url: 'models/currentUser.model.php',
                    data: {
                        TYPES: 'SELECT_user_where',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    console.log(data);
                    if (data.ERROR == false) {
                        $scope.form.PIC = data.DATA.PIC;
                        $scope.form.user_NAME = data.DATA.user_NAME;   
                        $scope.form.OFFICE = data.DATA.OFFICE; 
                        $scope.form.TEL = data.DATA.TEL;                      
                        
                    }
                });
        }
        SelectUser();
    })
