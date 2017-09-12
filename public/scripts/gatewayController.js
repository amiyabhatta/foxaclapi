(function () {

    'use strict';

    angular
            .module('authApp')
            .controller('GatewayController', GatewayController);

    function GatewayController($http, $scope, $state, $window, $location, $timeout) {
        var vm = this;
        vm.users;
        vm.error;
        $scope.showLoader       = true;
        $scope.fullname         = '';
        $scope.username         = '';
        $scope.email            = '';
        $scope.password         = '';
        $scope.confirmpassword  = '';        

        var token = sessionStorage.AuthUser;
        var url = $location.search();
        $scope.module = 'gateway';
        vm.getGateways = function () {
            $scope.showLoader       = true;
            $http.get('api/v1/gateway', {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {
                vm.gateways = response.data.data;
                $scope.succ_message = sessionStorage.succ_message;
                sessionStorage.succ_message = '';
                $scope.showLoader       = false;
            }, function (error) {

            });
        }
        vm.getGateway = function () {
            
            $http.get('api/v1/gateway/' + url.gid, {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {
                $scope.gateway = response.data.data[0];
                $scope.gatewayname = $scope.gateway.name;
                $scope.hostname = $scope.gateway.host;
                $scope.portname = $scope.gateway.port;
                $scope.password = $scope.gateway.password;
                $scope.username = $scope.gateway.username;
            }, function (error) {

            });
        }


        //saving new permission
        $scope.saveGateway = function () {
            // use $.param jQuery function to serialize data from JSON 
            $scope.gatewayname = $scope.gatewayname;
            var config = {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }
            if (url.gid === undefined) {
                $http.post('api/v1/gateway',
                        {
                            gatewayname: $scope.gatewayname,
                            host: $scope.hostname,
                            port: $scope.portname,
                            password: $scope.password,
                            username: $scope.username,
                        }, config)
                        .then(function (data, status, headers, config) {
                            $scope.gatewayname = '';
                            $scope.rolename = '';
                            sessionStorage.succ_message = "Gateway has been created successfully.";
                            $state.go('gateways');

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
                $http.put('api/v1/gateway/' + url.gid,
                        {
                            gatewayname: $scope.gatewayname,
                            host: $scope.hostname,
                            port: $scope.portname,
                            password: $scope.password,
                            username: $scope.username,
                        }, config)
                        .then(function (data, status, headers, config) {
                            $scope.gatewayname = '';
                            $scope.rolename = '';
                            sessionStorage.succ_message = "Gateway has been updated successfully.";
                            $state.go('gateways');

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
                $http.delete('api/v1/gateway/' + id, {
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
                    
                }, function (error) {
                    console.log(error.data.message);
                    $scope.err_message = "Unable to delete the record.";
                    //$state.go('gateways');
                    
                });

            }
        }
        
        vm.checkLogin = function() {            
            var token = sessionStorage.AuthUser;        
            if(token === '') {
               $window.location.href = '/login';
           }
        }
        
        vm.clearData = function() {            
            $scope.gatewayname = '';
            $scope.hostname = '';
            $scope.portname = '';
            $scope.password = '';
            $scope.username = '';        
        }
        $scope.resetData = function() {  
            vm.clearData();
        }
        
        vm.checkLogin();
        
        
        $(".page-header h1").text("Gateways");

    }

})();


