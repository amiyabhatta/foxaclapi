(function () {

    'use strict';

    angular
            .module('authApp')
            .controller('UserController', UserController);

    function UserController($http, $scope, $state, $window, $filter, $location, $timeout) {
        var vm = this;
        vm.users;
        vm.error;
        $scope.succ_message = '';
        $scope.modulename = 'User Listing';
        $scope.fullname = '';
        $scope.username = '';
        $scope.email = '';
        $scope.password = '';
        $scope.confirmpassword = '';
        $scope.lst = [];
        $scope.server_id = '';
        $scope.module = 'user';
        $scope.servers = '';
        var token = localStorage.AuthUser;

        vm.getUsers = function () {
            $http.get('api/authenticate').success(function (users) {
                vm.users = users;
            }).error(function (error) {
                vm.error = error;
            });
        };
        vm.getUser = function () {

            // Using $location service
            var url = $location.search();
            if (url.userid !== undefined) {
                $http.get('api/v1/users/' + url.userid, {
                    headers: {
                        "Authorization": 'Bearer ' + token
                    }
                }).then(function (response) {
                    $scope.user_data = response.data.data[0];
                    $scope.manager_id = $scope.user_data.manager_id;
                            $scope.username = $scope.user_data.name;
                            $scope.email = $scope.user_data.email;
                            $scope.groups = $scope.user_data.groups;
                            $scope.server_id = $scope.user_data.server_id;

                    angular.forEach($scope.servers, function (value, key) {
                        value.checked = false;
                        angular.forEach($scope.server_id, function (val, key2) {
                            if (value.id === val.server_id)
                                value.checked = true;
                        });
                    });
                }, function (error) {

                });
            }
        };


        vm.getServers = function () {
            $scope.showLoader = true;
            $http.get('api/v1/serverlist', {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {
                $scope.servers = response.data;
                $scope.showLoader = false;
            }, function (error) {

            });
        };

        $scope.change = function (list, obj) {

            if (obj) {
                $scope.lst[list.id] = list;
            } else {
                $scope.lst[list.id] = '';
            }
        };
        
        $scope.changeWl = function (list, obj) {
console.log(list.obj);
            if (obj) {
                $scope.lst[list.id] = list;
            } else {
                $scope.lst[list.id] = '';
            }
        };

        //saving new user

        $scope.createUsers = function () {
            $scope.server_id = '';
            angular.forEach($scope.servers, function (ser) {
                if (ser.checked) {
                    $scope.server_id += ser.id + ',';
                }
            });

            var url = $location.search();
            var uri;
            if (url.userid !== undefined) {
                uri = 'api/v1/users/' + url.userid;
            } else {
                uri = 'api/v1/register';
            }

            $scope.fullname = $scope.fullname;
            $scope.username = $scope.username;
            $scope.email = $scope.email;
            $scope.password = $scope.password;
            $scope.confirmpassword = $scope.confirmpassword;
            $scope.serv = '';


            // use $.param jQuery function to serialize data from JSON 
            var data = $.param({
                user_manager_id: $scope.manager_id,
                user_name: $scope.username,
                user_email: $scope.email,
                password: $scope.password,
                conifrm_password: $scope.confirmpassword,
                server_id: $scope.server_id
            });

            var config = {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }

            if (url.userid === undefined) {
                $http.post(uri,
                        {user_manager_id: $scope.manager_id,
                            user_name: $scope.username,
                            user_email: $scope.email,
                            groups:$scope.groups,
                            password: $scope.password,
                            conifrm_password: $scope.confirmpassword,
                            server_id: $scope.server_id
                        }, config)
                        .then(function (data, status, headers, config) {
                            $scope.fullname = '';
                            $scope.username = '';
                            $scope.email = '';
                            $scope.groups = '';
                            $scope.password = '';
                            $scope.confirmpassword = '';
                            sessionStorage.succ_message = "User has been created successfully.";
                            $state.go('home');
                        })
                        .catch(function (response, status, header, config) {
                            $scope.err_message = '';
                            angular.forEach(response.data, function (errmessage, key) {
                                angular.forEach(errmessage, function (mesg, key) {
                                    $scope.err_message += mesg + "\n";
                                })
                            })
                            $timeout(function () {
                                $scope.err_message = '';
                            }, 4000); // 4 seconds
                        });

            } else {

                $http.put(uri,
                        {user_manager_id: $scope.manager_id,
                            user_name: $scope.username,
                            user_email: $scope.email,
                            groups: $scope.groups,
                            password: $scope.password,
                            confirm_password: $scope.confirmpassword,
                            server_id: $scope.server_id
                        }, config)
                        .then(function (data, status, headers, config) {
                            $scope.fullname = '';
                            $scope.username = '';
                            $scope.email = '';
                            $scope.groups = '';
                            $scope.password = '';
                            $scope.confirmpassword = '';
                            sessionStorage.succ_message = "User has been updated successfully.";
                            $state.go('home');
                        })
                        .catch(function (response, status, header, config) {
                            $scope.err_message = '';
                            angular.forEach(response.data, function (errmessage, key) {
                                angular.forEach(errmessage, function (mesg, key) {
                                    $scope.err_message += mesg + "\n";
                                })
                            })
                            $timeout(function () {
                                $scope.err_message = '';
                            }, 4000); // 4 seconds                            
                        });
            }
        };


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
                    sessionStorage.succ_message = "Record has been deleted successfully..";
                    // $state.go('gateways');
                    //
                    $state.go($state.current, {}, {reload: true});

                }, function (error) {
                    $scope.err_message = "Unable to delete the record.";
                    //$state.go('gateways');                    
                });
            }
        }

        vm.clearData = function () {
            $scope.fullname = '';
            $scope.username = '';
            $scope.password = '';
            $scope.confirmpassword = '';
            $scope.user_data = '';
            $scope.manager_id = '';
            $scope.email = '';
            $scope.server_id = '';
            $scope.ser = '';
            $scope.groups = '';
            angular.forEach($scope.servers, function (value, key) {
                        value.checked = false;
                        angular.forEach($scope.server_id, function (val, key2) {
                            
                                value.checked = false;
                        })
                    });
        }
        $scope.resetData = function () {
            vm.clearData();
        }
        //reset wl setting data
        $scope.resetWlSettingData = function () {
            vm.clearData(); 
        }
        
        vm.checkLogin = function () {
            var token = localStorage.AuthUser;
            if (token === '' || token === undefined){
                $window.location.href = '/login';
            }
        }();
        
        
        vm.getServerList = function () {
            var url = $location.search();
            $scope.showLoader = true;
            $http.get('api/v1/getserverlist/'+url.userid, {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {
                $scope.serverslist = response.data;
                $scope.showLoader = false;
            }, function (error) {

            });
        }
        
        vm.getWhitelableSettings = function () {
            
            $(".page-header h1").text("Last Trade Settings");
            
            var url = $location.search();
            $scope.settings = {};
            $scope.settings.flag = {};
            $scope.settings.groups = {};
            $scope.settings.botime = {};
            $scope.settings.fxtime = {};
            $scope.settings.wlid = {};
            
            $scope.showLoader = true;
            $http.get('api/v1/getwhitelablesettings/'+url.userid, {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }).then(function (response) {
                $scope.responsedata = response.data;
                $scope.managerid = $scope.responsedata.managerid;
                $scope.wlsettings = $scope.responsedata.whitelablesettings;
                
                angular.forEach($scope.wlsettings, function(item){
                   $scope.settings.flag[item.wlid] =item.flag;
                   $scope.settings.groups[item.wlid] =item.groups;
                   $scope.settings.botime[item.wlid] =item.botime;
                   $scope.settings.fxtime[item.wlid] =item.fxtime;
                   $scope.settings.wlid[item.wlid]   =item.wlid;
                })
                
                $scope.showLoader = false;
            }, function (error) { 

            });
        };
        
        $scope.saveSettings = function () {
            var url = $location.search();
            $scope.showLoader = true;     
            $scope.json = angular.toJson($scope.settings);
            
            var config = {
                headers: {
                    "Authorization": 'Bearer ' + token
                }
            }
           
                $http.post('api/v1/assignwhitelable/'+url.userid,
                        {whitelablesettings: $scope.json
                        }, config)
                        .then(function (data, status, headers, config) {
                            
                            sessionStorage.succ_message = "Setting has been saved successfully.";
                            $scope.succ_message = "Setting has been saved successfully.";
                            $state.go('home');
                        })
                        .catch(function (response, status, header, config) {
                            $scope.err_message = '';
                            angular.forEach(response.data, function (errmessage, key) {
                                angular.forEach(errmessage, function (mesg, key) {
                                    $scope.err_message += mesg + "\n";
                                })
                            })
                            $timeout(function () {
                                $scope.err_message = '';
                            }, 4000); // 4 seconds
                        });
            
            
            $scope.savedata = {};
            angular.forEach($scope.settings.flags, function(item,key){
                $scope.savedata[key] = [ {
                        flag:$scope.settings.flag[key], 
                        groups:$scope.settings.groups[key], 
                        botime:$scope.settings.botime[key], 
                        fxtime:$scope.settings.fxtime[key]                  
                    }]
             })
             $scope.json1 = angular.toJson($scope.savedata);
             
        };
       

    }

})();