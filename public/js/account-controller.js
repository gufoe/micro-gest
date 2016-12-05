
app.controller('accountController', function($scope, $http, $auth, $location, $status) {
    $auth.enforce()
    $scope.page.title = 'Login'

    $scope.form = {
        name: user().name,
        email: user().email,
    }

    $scope.submit = () => {
        if (!$scope.form.email || !$scope.form.name) {
            $status.error('Campi non validi.')
            return
        }

        $http.post('/users/self', $scope.form).then(
            res => {
                $auth.setUser(res.data)
                $status.info('Impostazioni salvate!')
            }
        )
    }
})