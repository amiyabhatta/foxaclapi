(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('AuthController', AuthController);

    function AuthController($auth, $state, $window, $scope, $timeout) {
        var vm = this;
        localStorage.AuthUser = ''; 
             
        vm.login = function() {
            var credentials = {
                email: vm.email,
                password: vm.password
            }
           
            // Use Satellizer's $auth service to login
            $auth.login(credentials).then(function(data) {
                $scope.err_message = '';
                if(data.data.message === 'Invalid Credentials.') {
                     $scope.err_message +=  'Invalid Credentials.' + "\n";  
                     swal({
                        title: "Error!",
                        text: "Invalid Credentials.",
                        type: "error",
                        confirmButtonText: "Ok"
                      });

                     $timeout(function() {
                       // $scope.err_message = '';
                     }, 4000); // 4 seconds   
                }
                
                localStorage.AuthUser = data.data.data.token;                
                // If login is successful, redirect to the users state
                $window.location.href = '/home#!/home';
            }).catch(function (response, status, header, config) {
                $scope.err_message = '';
                angular.forEach(response.data, function (errmessage, key) {                                    
                    $scope.err_message +=  errmessage + "\n";                               
                })  
                $timeout(function() {
                    $scope.err_message = '';
                 }, 4000); // 4 seconds                            
            });
        }
        
        $scope.logout = function($event) {
            localStorage.AuthUser = '';     
        }
        $scope.redirect = 0; 
        vm.checkLogin = function() {            
            var token = localStorage.AuthUser;        
            if(token === '') {
               $window.location.href = '/login';
           }
        }
        
        $scope.onKeyPress = function($event) {
            if ($event.keyCode == 13) {                
                vm.login()
            }
        }; 

    }

})();