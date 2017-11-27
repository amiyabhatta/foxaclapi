(function () {

    'use strict';

    angular
            .module('authApp')
            .controller('DashboardController', DashboardController);
    function DashboardController($http, $auth) {
        var vm = this;
        vm.users;
        vm.error;

        var token = sessionStorage.AuthUser;
        vm.getUsers = function () {
            $http.get('api/v1/users', {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {
                vm.users = response.data.data;
            }, function (error) {

            });
        }
    }

})();
