(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('RoleController', RoleController);  

    function RoleController($http, $scope, $state, $window) {
        var vm = this;
        vm.users;
        vm.error;        
        $scope.rolename = '';            
       
            var token = sessionStorage.AuthUser;
            vm.getRoles = function () {
                $http.get('api/v1/roles', {
                    headers: {
                        "Authorization": 'Bearer ' + token
                    }
                }).then(function (response) {
                    vm.roles = response.data.data;
                }, function (error) {

                });
            }
            
            var token = sessionStorage.AuthUser;
            //saving new role
            $scope.saveRole = function () {
                
                // use $.param jQuery function to serialize data from JSON 
                $scope.rolename = $scope.rolename;
                var config = {
                    headers : {
                        "Authorization": 'Bearer ' + token
                    }
                }
                
                $http.post('api/v1/roles', 
                    {
                      role: $scope.rolename,
                    }, config)
                    .then(function (data, status, headers, config) {
                        $scope.rolename = '';
                        $state.go('roles');

                    })
                .error(function (data, status, header, config) {
                    $scope.ResponseDetails = "Data: " + data +
                        "<hr />status: " + status +
                        "<hr />headers: " + header +
                        "<hr />config: " + config;
                });
            };
         
         /*
            $scope.getUsers.$save(function (data) {
                if (data.statusCode === 200) {
                    toastr.success(data.message);
                    $state.go('whitelabel.users');
                } else {
                    toastr.error(data.message);
                }
            });
          */  
            
        
        
        
    }

})();