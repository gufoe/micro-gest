app.controller('groupController', function($scope, $http, $uibModal, $location, $status, $elementPicker) {
    $auth.enforce()
    $scope.page.title = 'Gruppi'
    $scope.page.meta = null

    $scope.filters = {}

    $scope.refresh = () => {
        $http.get('/groups/', {
            params: $scope.filters
        }).then(res => {
            $scope.groups = res.data
        })
    }

    $scope.$watch('filters', () => {
        $scope.refresh()
    }, true)

    $scope.delete = (group) => {
        if (!confirm('Cancellare il gruppo '+group.name+'?'))
            return;
        $status.warning('Cancello ' + group.name + '...')
        $http.delete('/groups/' + group.id).then(res => {
            $status.success('Eliminato')
            $scope.refresh()
        })
    }

    $scope.setForm = (group) => {
        $scope.form = group ? group : {}
    }

    $scope.addContacts = (group) => {
        var onselect = (selected) => {
            var c = { contacts: selected.lists('id')}
            $http.post('/groups/'+group.id+'/contacts', c).then(res => {
                $scope.refresh()
            })
        }
        $http.get('/groups/' + group.id).then(res => {
            $elementPicker.show({
                url: '/contacts',
                selected: res.data.contacts,
                callback: onselect
            })
        })
    }

    $scope.submit = () => {
        var form = $scope.form
        $status.info('...', null)
        $http.post('/groups/' + (form.id ? form.id : ''), form).then(res => {
            $status.success('Contact saved!')
            $scope.form = null
            $scope.refresh()
        })
    }

    $scope.sendEmail = (group) => {
		var up = $uibModal.open({
			templateUrl: '/pages/group-sendemail.html',
			controller: 'groupSendemailController',
			resolve: {args: () => {
				return {
                    group: group
                }
			}}
		})
    }
})