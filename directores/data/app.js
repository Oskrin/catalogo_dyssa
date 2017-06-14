var app = angular.module('scotchApp', ['ngRoute','ngResource','ngStorage']);

// configure our routes
app.config(function($routeProvider) {
    $routeProvider
    // route page inicial
    .when('/', {
        templateUrl : 'data/inicio/index.html',
        // controller  : 'mainController',
        activetab: 'inicio'
    })
    // route clientes
    .when('/clientes', {
        templateUrl : 'data/clientes/index.html',
        controller  : 'clientesController',
        activetab: 'clientes'
    })
    // proceso proformas
    .when('/proformas', {
        templateUrl : 'data/proformas/index.html',
        controller  : 'proformasController',
        activetab: 'proformas'
    })
    // proceso pedidos
    .when('/pedidos', {
        templateUrl : 'data/pedidos/index.html',
        controller  : 'pedidosController',
        activetab: 'pedidos'
    })
});


app.run(function ($rootScope, $location, Auth, loginService, $window) {
    var routespermission = ['/'];

    $rootScope.$on('$routeChangeStart', function (event) {
        if (!Auth.isLoggedIn()) {
            event.preventDefault();
            swal({
                title: "Lo sentimos acceso denegado",
                type: "warning",
            });
        } else { } 
    });
});

app.factory('Auth', function($location) {
    var user;
    return {
        setUser : function(aUser) {
            user = aUser;
        },
        isLoggedIn : function() {
            var ruta = $location.path();
            var ruta = ruta.replace("/","");
            var accesos = JSON.parse(Lockr.get('users'));
                accesos.push('inicio');
                accesos.push('');

            var a = accesos.lastIndexOf(ruta);
            if (a < 0) {
                return false;    
            } else {
                return true;
            }
        }
    }
});

app.factory('sessionService', function($http, $location) {
    return {
        set: function(key, value) {
            return sessionStorage.setItem(key, value);
        },
        get: function(key) {
            return sessionStorage.getItem(key);
        },
        destroy: function(key) {
            $http.post('../login/destroy_session_director.php')
            return sessionStorage.removeItem(key);    
        }
    }
});

app.factory('loginService', function($http, $location, sessionService, $window) {
    return {
        salir: function() {
            sessionService.destroy('id_usuario');
            $window.location = '../login'; 
        }, 
        islogged: function() {
            var $checkSessionServer = $http.post('../login/check_session.php');
            return $checkSessionServer;
        }    
    }
});

    