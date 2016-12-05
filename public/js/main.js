app.config(function($routeProvider, $locationProvider) {
    $routeProvider
        .when('/', {
            templateUrl: '/pages/home.html',
            controller: 'homeController'
        })
        // Login/signup
        .when('/login', {
            templateUrl: '/pages/login.html',
            controller: 'loginController'
        })
        // Account settings
        .when('/account', {
            templateUrl: '/pages/account.html',
            controller: 'accountController'
        })
        // Contacts
        .when('/contacts', {
            templateUrl: '/pages/contacts.html',
            controller: 'contactController'
        })
        // Groups
        .when('/groups', {
            templateUrl: '/pages/groups.html',
            controller: 'groupController'
        })

    $locationProvider.html5Mode(true)
})

app.factory('AuthInterceptor', function($rootScope, $q, $status, $location) {
    return {
        responseError: (res) => {

            if (!res.config.errorHandled) {
                switch (res.status) {
                    case 500:
                        $status.error("Errore 500, contattare l'assistenza")
                        break
                    case 403:
                        $status.error('Accesso negato')
                        break

                    case 401:
                        $rootScope.$broadcast('disconnected')
                        break

                    case 400:
                        $status.error(res.data.error ? res.data.error : 'Richiesta non valida')
                        break
                    default:
                        $status.error("Errore sconosciuto, contattare l'assistenza")
                }
            }

            return $q.reject(res)
        }
    }
})

app.config(function($httpProvider) {
    $httpProvider.interceptors.push('AuthInterceptor')
})

app.directive("ngFiles", [function() {
    return {
        scope: {
            ngFiles: "="
        },
        link: function(scope, element, attributes) {
            element.bind("change", function(event) {
                scope.$apply(function() {
                    scope.ngFiles = event.target.files
                })
            })
        }
    }
}])

app.directive("ngFile", [function() {
    return {
        scope: {
            ngFile: "="
        },
        link: function(scope, element, attributes) {
            element.bind("change", function(event) {
                scope.$apply(function() {
                    scope.ngFile = event.target.files[0]
                })
            })
        }
    }
}])

app.filter('bytes', function() {
    return function(bytes, precision) {
        if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return '-'
        if (typeof precision === 'undefined') precision = 1
        var units = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB'],
            number = Math.floor(Math.log(bytes) / Math.log(1024))
        return (bytes / Math.pow(1024, Math.floor(number))).toFixed(precision) + ' ' + units[number]
    }
})

app.filter('datify', function() {
    return function(date) {
        return new Date(date)
    }
})
