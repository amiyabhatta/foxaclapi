(function () {

    'use strict';

    angular
            .module('authApp')
            .controller('WhiteLabelsController', WhiteLabelsController);

    function WhiteLabelsController($http, $scope, $state, $window, $location, $timeout) {
        var vm = this;
        vm.users;
        vm.error;
        $scope.servername = '';
        $scope.whitelabels = '';
        $scope.groups = '';
        $scope.botime =  '';
        $scope.fxtime = '';
        $scope.emails = '';
        var token = sessionStorage.AuthUser;
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
                $scope.whitelabel = response.data.data[0];  
                $scope.servername = $scope.whitelabel.server;
                $scope.whitelabels = $scope.whitelabel.whitelabels;
                $scope.groups = $scope.whitelabel.groups;
                $scope.botime = $scope.whitelabel.botime;
                $scope.fxtime = $scope.whitelabel.fxtime;
                $scope.emails = $scope.whitelabel.emails;
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
                            servername: $scope.servername,
                            whitelabels: $scope.whitelabels,
                            groups: $scope.groups,
                            botime: $scope.botime,
                            fxtime: $scope.fxtime,
                            email: $scope.emails,
                        }, config)
                        .then(function (data, status, headers, config) {                           
                            sessionStorage.succ_message = "White Label has been created successfully.";
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
                            servername: $scope.servername,
                            whitelabels: $scope.whitelabels,
                            groups: $scope.groups,
                            botime: $scope.botime,
                            fxtime: $scope.fxtime,
                            email: $scope.emails,
                        }, config)
                        .then(function (data, status, headers, config) {
                            sessionStorage.succ_message = "White Label has been updated successfully.";
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
                    $scope.succ_message = "White Label has been deleted successfully.";
                    sessionStorage.succ_message = "White Label has been deleted successfully..";
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
         $(".page-header h1").text("White Labels");
    }

})();


