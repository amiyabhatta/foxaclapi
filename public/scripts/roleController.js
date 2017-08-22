(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('RoleController', RoleController);  

    function RoleController($http, $scope, $state, $window, $stateParams, $location, $timeout) {
            var vm = this;
            vm.users;
            vm.error;        
            $scope.rolename = ''; 
            $scope.rpermissions = '';           
            // Using $location service
            var url = $location.search();            
            var token = sessionStorage.AuthUser;
            $scope.module = 'role';
            $scope.showLoader       = true;
            vm.getRole = function () {
                var url = $location.search();  
                if(url.roleid != undefined) {
                $http.get('api/v1/roles/'+url.roleid, {
                    headers: {
                        "Authorization": 'Bearer ' + token
                    }
                }).then(function (response) {
                    $scope.roles_data   = response.data.data[0];
                    $scope.rolename     = $scope.roles_data.role;
                    $scope.roleid       = $scope.roles_data.id;
                }, function (error) {

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
                    $scope.succ_message = sessionStorage.succ_message ;
                    sessionStorage.succ_message = '';
                    $scope.showLoader       = false;
                    
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
                
                if(url.roleid === undefined) {
                    $http.post('api/v1/roles', 
                        {
                          role: $scope.rolename,
                        }, config)
                        .then(function (data, status, headers, config) {
                            $scope.rolename = '';
                            sessionStorage.succ_message = "Role has been created successfully.";
                            $state.go('roles');

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
                    $http.put('api/v1/roles/'+url.roleid, 
                        {
                          role: $scope.rolename,
                        }, config)
                        .then(function (data, status, headers, config) {
                            $scope.rolename = '';
                            sessionStorage.succ_message = "Role has been updated successfully.";
                            $state.go('roles');

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
         
        $scope.delete = function (id) {
            var deleterecord = $window.confirm('Are you absolutely sure you want to delete?');
            if (deleterecord) {
                $http.delete('api/v1/roles/' + id, {
                    headers: {
                        "Authorization": 'Bearer ' + token
                    }
                }).then(function (response) {
                    $scope.resp = response;
                    $scope.err_message = '';    
                    $scope.succ_message = "Record has been deleted successfully.";
                    sessionStorage.succ_message = "Record has been deleted successfully..";
                   // $state.go('gateways');
                    //
                    $state.go($state.current, {}, {reload: true});
                    
                }, function (error) {;
                    $scope.err_message = "Unable to delete the record.";
                    //$state.go('gateways');                    
                });
            }
        }
        
        vm.getPermissions = function () {
            $http.get('api/v1/permission', {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {
                $scope.permissions = response.data.data;
                
                vm.getRolePermissions();
                
                
                $scope.succ_message = sessionStorage.succ_message;
                sessionStorage.succ_message = '';
            }, function (error) {

            });
        }
        
        
        vm.getRolePermissions = function () {
               
                var url = $location.search();  
                if(url.roleid != undefined) {
                $http.get('api/v1/roles/'+url.roleid, {
                    headers: {
                        "Authorization": 'Bearer ' + token
                    }
                }).then(function (response) {
                    $scope.roles_data   = response.data.data[0];
                    $scope.role_permissions     = $scope.roles_data.role_permissions;
                    angular.forEach($scope.permissions, function (per, key) {
                        
                        angular.forEach($scope.role_permissions, function (val, key2) {
                            if (val.permissions_id === per.id) {
                                if(val.action === 1)
                                 per.checked = true;
                                else
                                 per.checked = false;
                            }
                        })
                    });
                }, function (error) {

                });
              }
            } 
        
        
        $scope.assignRolePermission = function (perid) {
            var checked = false;
            angular.forEach($scope.permissions, function (value, key) {
                        if(value.id === perid) {
                           if(value.checked === true)
                            checked = 1;
                           else
                            checked = 0;
                        }
            });
            
            var config = {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }
            // Using $location service
            var url = $location.search();
            var roleid = url.roleid;
            if (roleid != undefined) {
                
                $http.post('api/v1/assignpermission/' + roleid,
                        {
                            permission_id : perid,
                            action        : checked,
                        }, config)  
                        .then(function (data, status, headers, config) {
                            $scope.permission = '';
                            $scope.succ_message = "Permission has been updated successfully.";
                            $timeout(function() {
                                $scope.succ_message = '';
                             }, 2000); // 2 seconds
                        })
                        .catch(function (data, status, header, config) {
                            $scope.ResponseDetails = "";
                        });
             
            }
        }
        
        $(".page-header h1").text("Roles");
    }

})();