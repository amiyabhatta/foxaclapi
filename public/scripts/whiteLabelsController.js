(function () {

    'use strict';

    angular
            .module('authApp')
            .controller('WhiteLabelsController', WhiteLabelsController);

    function WhiteLabelsController($http, $scope, $state, $window, $location, $timeout) {
        var vm = this;
        vm.users;
        vm.error;
        $scope.serverid = 0;
        $scope.selectedServerIndex = false;
        $scope.whitelabels = '';
        $scope.groups = '';
        $scope.botime =  '';
        $scope.fxtime = '';
        $scope.emails = '';
        var token = localStorage.AuthUser;
        var url = $location.search();
        $scope.module = 'gateway';
        vm.getWhiteLabels = function () {
            $scope.showLoader       = true;
            $http.get('api/v1/getwhitelabel', {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {
                vm.whitelabels = response.data.data;
                $scope.succ_message = sessionStorage.succ_message;
                sessionStorage.succ_message = '';
                $scope.showLoader = false;
            }, function (error) {

            });
        }
        vm.getWhiteLabel = function () {
            if (url.id !=  undefined ) {
            $http.get('api/v1/getwhitelabel/' + url.id, {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {
                $scope.whitelabel  = response.data.data[0];  
                $scope.servername  = $scope.whitelabel.server;
                $scope.serverid    = $scope.whitelabel.serverid;
                $scope.whitelabels = $scope.whitelabel.whitelabels;
                $scope.groups      = $scope.whitelabel.groups;
                $scope.botime      = $scope.whitelabel.botime;
                $scope.fxtime      = $scope.whitelabel.fxtime;
                $scope.emails      = $scope.whitelabel.emails;
                
                
            }, function (error) {

            });
           }
        }


        //saving new permission
        $scope.saveWhiteLabel = function () {
            
            var config = {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }
            
            if (url.id ===  undefined ) {
                $http.post('api/v1/createwhitelabel',
                        {
                            serverid: $scope.selectedServerIndex['id'],
                            whitelabels: $scope.whitelabels,
                            groups: $scope.groups,
                            //botime: $scope.botime,
                            //fxtime: $scope.fxtime,
                            //email: $scope.emails,
                        }, config)
                        .then(function (data, status, headers, config) {                           
                            sessionStorage.succ_message = "Whitelable setting has been created successfully.";
                            $state.go('whitelabels');

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
                $http.put('api/v1/updatewhitelabel/' + url.id,
                        {
                            serverid: $scope.selectedServerIndex['id'],
                            whitelabels: $scope.whitelabels,
                            groups: $scope.groups,
                            //botime: $scope.botime,
                            //fxtime: $scope.fxtime,
                            //email: $scope.emails,
                        }, config)
                        .then(function (data, status, headers, config) {
                            sessionStorage.succ_message = "Whitelable setting has been updated successfully.";
                            $state.go('whitelabels');

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
                $http.delete('api/v1/deletewhitelabel/' + id, {
                    headers: {
                        "Authorization": 'Bearer ' + token
                    }
                }).then(function (response) {
                    $scope.resp = response;
                    $scope.err_message = '';    
                    $scope.succ_message = "Whitelable has been deleted successfully.";
                    sessionStorage.succ_message = "Whitelable has been deleted successfully.";
                   // $state.go('gateways');
                    //
                    $state.go($state.current, {}, {reload: true});
                    
                }, function (error) {
                    console.log(error.data.message);
                    sessionStorage.err_message = "Unable to delete the record.";
                    $state.go('whitelabels');
                    
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
            $scope.servername = '';
            $scope.whitelabels = '';
            $scope.groups = '';
            $scope.botime = '';
            $scope.fxtime = '';
            $scope.emails = '';       
        }
        $scope.resetData = function() {
            vm.clearData();
        }
        
        
         $(".page-header h1").text("Whitelable Settings");
         
         vm.getServers = function () {
            $scope.showLoader = true;
            $http.get('api/v1/serverlist', {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {
                $scope.servers = response.data;
                $scope.servers.unshift({id : '', servername: 'Select Server Name'});
                vm.setSelectedServer($scope.servers);
                $scope.showLoader = false;
            }, function (error) {

            });
        };
        vm.setSelectedServer = function(servers){
            $scope.selectedServerIndex = servers[0];
            for(var i= 0; i < servers.length; i++){
                if(servers[i].id == $scope.serverid){ 
                    $scope.selectedServerIndex = servers[i];
                    break;
                }
            }
        }
    }

})();


