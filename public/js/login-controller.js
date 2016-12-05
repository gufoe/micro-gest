
app.controller('loginController', function($scope, $http, $auth, $location, $status) {
    $scope.page.title = 'Login'

    $scope.sign_up = false
    $scope.form = {}

    $scope.signup = () => {
        if (!$scope.form.email || !$scope.form.password || !$scope.form.name) {
            $status.error('Campi non validi.')
            return
        }

        $status.info('Signing up...', null)
        $http.post('/users', $scope.form).then(
            res => {
                $scope.signin()
            }
        )
    }

    $scope.signin = () => {
        if (!$scope.form.email || !$scope.form.password) {
            $status.error('Campi non validi.')
            return
        }

        $status.info('Signing in...', null)
        $http.post('/sessions', $scope.form).then(res => {
            console.log('setting token to ', res.data.token)
            $auth.setToken(res.data.token)
            $auth.setUser(res.data.user)
            $status.success('Logged in!')
            $location.path('/')
        })
    }
})