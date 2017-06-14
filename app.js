var app = angular.module('loginApp', ['ngRoute','ngResource','ngStorage']);

app.config(function($routeProvider) {
    $routeProvider
    // route page inicial
    .when('/login', {
        templateUrl : 'index.html',
        controller  : 'loginController',
        activetab: 'login'
    })
});