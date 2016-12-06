app.controller('invoiceController', function($scope, $auth, $http, $status) {
    $auth.enforce()

    $scope.files = []
    $scope.uploading = 0

    $scope.submit = () => {
        $scope.uploading = 0
        var next = 0

        function process() {
            if ((file = $scope.files[next++])) {
                if (file.status && file.status.success) {
                    process()
                } else {
                    $scope.uploading++
                    $scope.upload(file, (data) => {
                        $scope.uploading--
                        process()
                    })
                }
            }
        }

        var max_connections = 4
        for (var i = 0; i < max_connections; i++)
            process()

    }

    $scope.upload = (file, callback) => {
        console.log('upl', file)
        var opts = {
            headers: {
                'Content-Type': undefined
            },
            errorHandled: true,
        }
        var params = objToFormData({ file: file })
        file.status = {
            class: 'text-warning',
            text: 'In caricamento...'
        }
        $http.post('/contacts/invoice', params, opts).then(
            res => {
                file.status = {
                    success: true,
                    class: 'text-success',
                    text: 'Fattura inviata a '+res.data.contact.name
                }
                callback && callback(res.data)
            },
            res => {
                file.status = {
                    success: false,
                    class: 'text-danger',
                }
                if (res.status == 404) {
                    file.status.text = 'Il codice fiscale non è stato trovato nei contatti'
                } else if (res.data.error) {
                    file.status.text = res.data.error
                } else {
                    file.status.text = 'Errore sconosciuto'
                }
                callback && callback(null)
            }
        )
    }
})