(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('AuthController', AuthController);

    function AuthController($auth, $state, $window) {

        var vm = this;

        vm.login = function() {

            var credentials = {
                email: vm.email,
                password: vm.password
            }

            // Use Satellizer's $auth service to login
            $auth.login(credentials).then(function(data) {
                //sessionStorage.AuthUser = JSON.stringify(data.data);
                //console.log( data.token);
                //console.log( data.data.data.token);
                sessionStorage.AuthUser = data.data.data.token;
                
                // If login is successful, redirect to the users state
                //$state.go('home', {});
                $window.location.href = '/home#!/home';
            });
        }

    }

})();