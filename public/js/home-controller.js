app.controller('homeController', function($scope, $http, $auth, $location, $interval) {


    $scope.refreshGroupEmails = () => {
        $http.get('/groups-emails/').then(res => {
            $scope.group_emails = res.data
            $scope.group_emails.updated_at = new Date()
        })
    }



    var int = $interval(() => {
        console.log('update')
        $scope.refreshGroupEmails()
    }, 1000)

    $scope.$on('$destroy', function() { $interval.cancel(int) })

})