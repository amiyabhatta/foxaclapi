(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('ServerController', ServerController);  

    function ServerController($http, $scope, $state, $window, $location, $timeout) {
        var vm = this;
        vm.users;
        vm.error;
        $scope.fullname = '';
        $scope.username = '';
        $scope.email = '';
        $scope.password = '';
        $scope.confirmpassword = '';
        $scope.module = 'server';    
       
        var token = localStorage.AuthUser;
        vm.getServers = function () {
            $scope.showLoader       = true;
            $http.get('api/v1/server', {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {                
                vm.servers = response.data.data;  
                $scope.succ_message = sessionStorage.succ_message ;
                sessionStorage.succ_message = '';
                $scope.showLoader       = false;
            }, function (error) {

            });
        }
        
        vm.getServer = function () {
             var url = $location.search();            
             if(url.serverid != undefined) {
                $http.get('api/v1/server/'+url.serverid, {
                    headers: {
                        "Authorization": 'Bearer ' + token
                    }
                }).then(function (response) {
                    $scope.servers = response.data.data[0];
                    $scope.servername = $scope.servers.servername;
                    $scope.ipaddress = $scope.servers.ipaddress;
                    $scope.username = $scope.servers.username;
                    $scope.password = $scope.servers.password;
                    $scope.databasename = $scope.servers.databasename;
                    $scope.masterid = $scope.servers.master_id;
                    $scope.port = $scope.servers.port;  
                    $scope.mt4api = $scope.servers.mt4api;
                    $scope.gateway_id = $scope.servers.gateway_id;
                    
                }, function (error) {

                });
             }
        }
        
        vm.getGateways = function () {
            $http.get('api/v1/gateway', {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {
                vm.gateways = response.data.data;
                
                
            }, function (error) {

            });
        }
        
        
       //saving new permission
        $scope.saveServer = function () {                
            
            var config = {
                headers : {
                    "Authorization": 'Bearer ' + token
                }
            }
           var url = $location.search();
           if(url.serverid ===  undefined ) {
            $http.post('api/v1/server', 
                {
                  servername      : $scope.servername,
                  ipaddress       : $scope.ipaddress,
                  username        : $scope.username,
                  password        : $scope.password,
                  databasename    : $scope.databasename,
                  masterid        : $scope.masterid,
                  port            : $scope.port,
                  mt4api          : $scope.mt4api,
                  GatewayID       : $scope.gateway_id,
                }, config)
                .then(function (data, status, headers, config) {
                    sessionStorage.succ_message = "Server added successfully.";
                    $state.go('servers');

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
                
                $http.put('api/v1/server/'+url.serverid, 
                {
                  servername      : $scope.servername,
                  ipaddress       : $scope.ipaddress,
                  username        : $scope.username,
                  password        : $scope.password,
                  databasename    : $scope.databasename,
                  masterid        : $scope.masterid,
                  port            : $scope.port,
                  mt4api          : $scope.mt4api,
                  GatewayID       : $scope.gateway_id,
                }, config)
                .then(function (data, status, headers, config) {
                    $scope.gatewayname = '';
                    sessionStorage.succ_message = "Server detail has been updated successfully.";
                    $state.go('servers');

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
                $http.delete('api/v1/server/' + id, {
                    headers: {
                        "Authorization": 'Bearer ' + token
                    }
                }).then(function (response) {
                    $scope.resp = response;
                    $scope.err_message = '';    
                    $scope.succ_message = "Record deleted successfully.";
                    sessionStorage.succ_message = "Record deleted successfully.";
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
        
        vm.clearData = function() {            
            $scope.servers = '';
            $scope.servername = '';
            $scope.ipaddress = '';
            $scope.username = '';
            $scope.password = '';
            $scope.databasename = '';
            $scope.masterid = '';
            $scope.port = '',
            $scope.mt4api = '',
            $scope.gateway_id = '';         
        }
        $scope.resetData = function() {  
            vm.clearData();
        }
        vm.checkLogin = function () {
            var token = localStorage.AuthUser;
            if (token === '' || token === undefined){
                $window.location.href = '/login';
            }
        }();
        
        $(".page-header h1").text("Servers");
    }

})();
