app.controller('mainController', function($rootScope, $scope, $http, $auth, $location, $status, $window) {
    $scope.config = {
        title: 'Gestionale'
    }
    $scope.page = {
        title: null,
        meta: null,
    }
    $window.logged = $scope.logged = $auth.logged
    $window.logout = $scope.logout = $auth.logout

    $rootScope.$on('disconnected', () => {
        $auth.reset()
        $status.error('Sessione scaduta')
        $location.path('/login')
    })

    $window.user = $scope.user = () => {
        return $auth.getUser()
    }

    $scope.isMenu = path => {
        if ($location.path() == path)
            return true
        return false
    }
})