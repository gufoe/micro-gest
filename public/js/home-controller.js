app.controller('homeController', function($scope, $http, $auth, $location, $interval) {
    $auth.enforce()

    $scope.refreshGroupEmails = () => {
        $http.get('/groups-emails/').then(res => {
            res.data.updated_at = new Date()
            $scope.group_emails = res.data
        })
    }

    var int = $interval(() => {
        $scope.refreshGroupEmails()
    }, 1000)

    $scope.$on('$destroy', function() { $interval.cancel(int) })

})