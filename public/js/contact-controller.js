app.controller('contactController', function($scope, $http, $auth, $location, $status, $routeParams) {
    $scope.page.title = 'Contatti'
    $scope.page.meta = null

    $scope.filters = {}

    if ($routeParams.gid) {
        $scope.filters.group_id = $routeParams.gid
    }

    $scope.refresh = () => {
        $http.get('/contacts/', {
            params: $scope.filters
        }).then(res => {
            $scope.contacts = res.data
        })
    }

    $scope.$watch('filters', () => {
        $scope.refresh()
    }, true)

    $scope.delete = (contact) => {
        if (!confirm('Cancellare il contatto '+contact.name+'?'))
            return;
        $status.warning('Elimino ' + contact.name + '...')
        $http.delete('/contacts/' + contact.id).then(res => {
            $status.success('Cancello')
            $scope.refresh()
        })
    }

    $scope.setForm = (contact) => {
        $scope.form = contact ? contact : {}
    }

    $scope.submit = () => {
        var form = $scope.form
        $http.post('/contacts/' + (form.id ? form.id : ''), form).then(res => {
            $status.success('Contatto salvato!')
            $scope.form = null
            $scope.refresh()
        })
    }
})