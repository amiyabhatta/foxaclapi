(function () {

    'use strict';

    angular
            .module('authApp')
            .controller('DashboardController', DashboardController);
    function DashboardController($http, $auth, $scope, $window, $filter, $location, $state) {
        var vm = this;
        vm.users;
        vm.error;
        $scope.succ_message = sessionStorage.succ_message;
        sessionStorage.succ_message = '';
        
        $scope.modulename = 'Users';
        var token = localStorage.AuthUser;
        vm.getUsers = function () {
            $scope.showLoader       = true;
            $http.get('api/v1/users', {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {
                vm.users = response.data.data;
                $scope.showLoader       = false;
            }, function (error) {

            });
        }
        
        $scope.delete = function (id) {
            var deleterecord = $window.confirm('Are you absolutely sure you want to delete?');
            if (deleterecord) {
                $http.delete('api/v1/users/' + id, {
                    headers: {
                        "Authorization": 'Bearer ' + token
                    }
                }).then(function (response) {
                    $scope.resp = response;
                    $scope.err_message = '';    
                    $scope.succ_message = "Record has been deleted successfully.";
                    sessionStorage.succ_message = "Record has been deleted successfully.";
                    $state.go($state.current, {}, {reload: true});
                    
                }, function (error) {
                    $scope.err_message = error.data.message;
                });
            }
        }
        
        vm.checkLogin = function () {
            var token = localStorage.AuthUser;
            if (token === '' || token === undefined){
                $window.location.href = '/login';
            }
        }();     
        
        $(".page-header h1").text("Users");
        
    }

})();
