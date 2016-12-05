app.controller('groupSendemailController', function($scope, $http, $status, $uibModalInstance, args) {
    $auth.enforce()
    $scope.group = args.group

    $scope.form = {
        group_id: $scope.group.id
    }

    $scope.done = function() {
        $uibModalInstance.close($scope.selected)
    }

    $scope.cancel = function() {
        $uibModalInstance.dismiss('cancel')
    }

    $scope.submit = () => {
        $http.post('groups-emails', $scope.form).then(res => {
            $status.success('Invieremo le vostre email a breve')
            $scope.cancel()
        })
    }
})