(function () {

    'use strict';

    angular
            .module('authApp')
            .controller('PermissionController', PermissionController);

    function PermissionController($http, $scope, $state, $window, $location, $timeout) {
        var vm = this;
        vm.users;
        vm.error;

        $scope.fullname = '';
        $scope.username = '';
        $scope.email = '';
        $scope.password = '';
        $scope.confirmpassword = '';
        $scope.module = 'permission';
        $scope.showLoader       = true;
        $scope.types = {"1": {"id": 1, "name": "Basic"}, "2": {"id": 2, "name": "Premium"}};

        var token = localStorage.AuthUser;
        vm.getPermissions = function () {
            $scope.showLoader       = true
            $http.get('api/v1/permission', {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {
                vm.permissions = response.data.data;
                $scope.succ_message = sessionStorage.succ_message;
                sessionStorage.succ_message = '';
                $scope.showLoader       = false
            }, function (error) {

            });
        }

        vm.getPermission = function () {
            
            // Using $location service
            var url = $location.search();
            if (url.pid != undefined) {
                $http.get('api/v1/permission/' + url.pid, {
                    headers: {
                        "Authorization": 'Bearer ' + token
                    }
                }).then(function (response) {
                    $scope.permission = response.data.data[0];
                    $scope.permissionname = $scope.permission.name;
                    $scope.user_type = $scope.permission.user_type;
                    $scope.permission_id = $scope.permission.id;

                }, function (error) {
                    $scope.permission.id;


                });
            }
        }

        //saving new permission
        $scope.savePermission = function () {
            // use $.param jQuery function to serialize data from JSON 
            $scope.permission = $scope.permissionname;
            var config = {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }

            var url = $location.search();
            var uri;

            if (url.pid != undefined) {
                uri = 'api/v1/permission/' + url.pid;
            } else {
                uri = 'api/v1/permission';
            }

            if (url.pid === undefined) {
                $http.post(uri,
                        {
                            permission: $scope.permissionname,
                            user_type: $scope.user_type,
                        }, config)
                        .then(function (data, status, headers, config) {
                            $scope.permission = '';
                            $state.go('permissions');

                        })
                        .catch(function (response, status, header, config) {
                            $scope.err_message = '';
                            angular.forEach(response.data, function (errmessage, key) {                                
                                angular.forEach(errmessage, function (mesg, key) {                                    
                                    $scope.err_message +=  mesg + "\n";
                                 })                                 
                            })  
                            $timeout(function() {
                                $scope.err_message = '';
                             }, 4000); // 4 seconds                            
                        });
            } else {

                $http.put(uri,
                        {
                            permission: $scope.permissionname,
                            user_type: $scope.user_type,
                        }, config)
                        .then(function (data, status, headers, config) {
                            $scope.permission = '';
                            $scope.err_message = '';
                            $scope.succ_message = "Record has been updated successfully.";
                            sessionStorage.succ_message = "Record has been updated successfully.";
                            $state.go('permissions');

                        })
                        .catch(function (response, status, header, config) {
                            $scope.err_message = '';
                            angular.forEach(response.data, function (errmessage, key) {                                
                                angular.forEach(errmessage, function (mesg, key) {                                    
                                    $scope.err_message +=  mesg + "\n";
                                 })                                 
                            })  
                            $timeout(function() {
                                $scope.err_message = '';
                             }, 4000); // 4 seconds                            
                        });

            }

        };
        
        
        vm.getUser = function () {
            $(".page-header h1").text("USER ROLE MANAGEMENT");
            // Using $location service
            var url = $location.search();
            if (url.userid != undefined) {
                $http.get('api/v1/users/' + url.userid, {
                    headers: {
                        "Authorization": 'Bearer ' + token
                    }
                }).then(function (response) {    
                            $scope.user_data = response.data.data[0];
                            $scope.role_id = $scope.user_data.role_id;
                }, function (error) {
                    // To Do something 

                });
            }
        }
        
        
        
        $scope.assignPermission = function (roleid) {
            
            var config = {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }
            // Using $location service
            var url = $location.search();
            var user_id = url.userid;
            if (user_id != undefined) {
                
                $http.post('api/v1/assignrole/' + user_id,
                        {
                            role_id : roleid,
                        }, config)
                        .then(function (data, status, headers, config) {
                            $scope.permission = '';
                            sessionStorage.succ_message = "Role has been assigned successfully.";
                            $state.go('home');

                        })
                        .error(function (data, status, header, config) {
                            $scope.ResponseDetails = "";
                        });
             
            }
        }
        
         vm.getRoles = function () {
                $scope.showLoader       = true;
                $http.get('api/v1/roles', {
                    headers: {
                        "Authorization": 'Bearer ' + token
                    }
                }).then(function (response) {
                    vm.roles = response.data.data;
                    sessionStorage.succ_message = '';
                    $scope.showLoader       = false;
                    
                }, function (error) {

                });
            }
            
        $scope.delete = function (id) {
            var deleterecord = $window.confirm('Are you absolutely sure you want to delete?');
            if (deleterecord) {
                $http.delete('api/v1/permission/' + id, {
                    headers: {
                        "Authorization": 'Bearer ' + token
                    }
                }).then(function (response) {
                    $scope.resp = response;
                    $scope.err_message = '';
                    $scope.succ_message = "Record has been deleted successfully.";
                    sessionStorage.succ_message = "Record has been deleted successfully.";
                    // $state.go('gateways');
                    //
                    $state.go($state.current, {}, {reload: true});

                }, function (error) {
                    console.log(error.data.message);
                    $scope.err_message = "Unable to delete the record.";
                    //$state.go('gateways');                    
                });
            }
        }
        
        vm.checkLogin = function () {
            var token = localStorage.AuthUser;
            if (token === '' || token === undefined){
                $window.location.href = '/login';
            }
        }();  
        vm.clearData = function() {            
            $scope.permissionname = '';
            $scope.user_type= '';             
        }
        $scope.resetData = function() {  
            vm.clearData();
        }
        
        
        $(".page-header h1").text("Permissions");
    }

})();
