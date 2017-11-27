(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('ServerController', ServerController);  

    function ServerController($http, $scope, $state, $window) {
        var vm = this;
        vm.users;
        vm.error;
        $scope.fullname = '';
        $scope.username = '';
        $scope.email = '';
        $scope.password = '';
        $scope.confirmpassword = '';
            
       
        var token = sessionStorage.AuthUser;
        vm.getServers = function () {
            $http.get('api/v1/server', {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {
                vm.servers = response.data.data;
            }, function (error) {

            });
        }
        
        //saving new user
        $scope.createUsers = function () {
            
            $scope.fullname = $scope.fullname;
            $scope.username = $scope.username;
            $scope.email = $scope.email;
            $scope.password = $scope.password;
            $scope.confirmpassword = $scope.confirmpassword;
            
            var token = sessionStorage.AuthUser;
        
            $scope.createUsers = function () {
               // use $.param jQuery function to serialize data from JSON 
                var data = $.param({
                    user_manager_id : $scope.manager_id,
                    user_name       : $scope.username,
                    user_email      : $scope.email,
                    user_password   : $scope.password,
                    confirmpassword : $scope.confirmpassword,
                    server_id       : 3
                });

                var config = {
                    headers : {
                        "Authorization": 'Bearer ' + token
                    }
                }

                $http.post('api/v1/register', 
                { user_manager_id: $scope.manager_id,
                  user_name: $scope.manager_id,
                  user_email: $scope.manager_id, 
                  user_password: $scope.manager_id,
                  server_id: $scope.manager_id
                }, config)
                .then(function (data, status, headers, config) {
                    $scope.fullname = '';
                    $scope.username = '';
                    $scope.email = '';
                    $scope.password = '';
                    $scope.confirmpassword = '';
                    $state.go('home');
                    
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
            
            
        };
        
        
    }

})();
