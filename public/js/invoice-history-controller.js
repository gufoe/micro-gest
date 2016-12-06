app.controller('invoiceHistoryController', function($scope, $auth, $http, $status, $routeParams) {
    $status.info('Caricamento storico...')
    $scope.token = $routeParams.token

    $http.get('contacts/'+$scope.token+'/info').then(res => {
        $scope.contact = res.data
    })
    $http.get('contacts/'+$scope.token+'/invoices').then(res => {
        $scope.invoices = res.data
    })
})