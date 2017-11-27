(function() {

    'use strict';

    angular
        .module('authApp', ['ui.router', 'satellizer'])
        .config(function($stateProvider, $urlRouterProvider, $authProvider) {

            // Satellizer configuration that specifies which API
            // route the JWT should be retrieved from
            $authProvider.loginUrl = '/api/v1/uilogin';

            // Redirect to the auth state if any other states
            // are requested other than users
            $urlRouterProvider.otherwise('/auth');

            $stateProvider
                .state('auth', {
                    url: '/auth',
                    templateUrl: '../views/authView.html',
                    controller: 'AuthController as auth'
                })
                .state('home', {
                    url: '/home',
                    templateUrl: '../views/dashboard.html',
                    controller: 'DashboardController as dashboard'
                })
                .state('createUser', {
                    url: '/createUser',
                    templateUrl: '../views/createUser.html',
                    controller: 'UserController as user'
                })
                .state('users', {
                    url: '/users',
                    templateUrl: '../views/userView.html',
                    controller: 'UserController as user'
                })
                .state('roles', {
                    url: '/roles/?roleid',
                    templateUrl: '../views/roleManagement.html',
                    controller: 'RoleController as role',
                    params: { id: null, }
                })
                .state('createRole', {
                    url: '/createRole',
                    templateUrl: '../views/createRole.html',
                    controller: 'RoleController as role'
                })
                .state('permissions', {
                    url: '/permissions',
                    templateUrl: '../views/permissionManagement.html',
                    controller: 'PermissionController as permission'
                })
                .state('servers', {
                    url: '/servers',
                    templateUrl: '../views/serverManagement.html',
                    controller: 'ServerController as server'
                })
                .state('gateways', {
                    url: '/gateways',
                    templateUrl: '../views/gatewayManagement.html',
                    controller: 'GatewayController as gateway'
                });
        });
})();
