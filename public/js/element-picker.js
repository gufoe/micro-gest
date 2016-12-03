
app.controller('elementPickerController', function($scope, $http, $route, $uibModalInstance, args) {
	$scope.selected = []
	$scope.page = []
	$scope.url = args.url

	if (args.selected)
		args.selected.each(u => { $scope.selected.push(u) })

	function status(type, msg) {
		if (!type || !msg) {
			$scope.status = null
			return
		}
		$scope.status = {
			type: type,
			msg: msg
		}
	}
	function err(msg) { status('danger', msg) }
	function info(msg) { status('info', msg) }
	function err(msg) { status('danger', msg) }

	$scope.search = () => {
		if (!$scope.query || $scope.query.length < 2) {
			$scope.page = []
			return err('Inserire almeno due caratteri.')
		}
		var query = {
			q: $scope.query
		}
		info('Searching...'+JSON.stringify(query))
		$http.get($scope.url, { params: query }).then(res => {
			$scope.page = res.data
			if (!$scope.page.data.length)
				err('Nessun elemento trovato.')
			else
				status()
		})
	}

	var indexOf = (element) => {
		return $scope.selected.indexOf($scope.selected.byField('id', element.id))
	}

	$scope.isSelected = (element) => {
		var s = $scope.selected
		return (s && indexOf(element) >= 0)
	}

	$scope.select = (element) => {
		var s = $scope.selected
		if (indexOf(element) < 0)
			s.push(element)
	}

	$scope.unselect = (element) => {
		var s = $scope.selected
		if ((i = indexOf(element)) >= 0)
			s.splice(i, 1)
	}


    $scope.done = function() {
        $uibModalInstance.close($scope.selected)
    }

    $scope.cancel = function() {
        $uibModalInstance.dismiss('cancel')
    }
})

app.service('$elementPicker', function($q, $http, $rootScope, $uibModal) {
    var self = this

    this.show = (args) => {

		var up = $uibModal.open({
			templateUrl: '/pages/element-picker.html',
			controller: 'elementPickerController',
			resolve: {args: () => {
				return args
			}}
		})
		args.callback && up.result.then(args.callback)
    }
})