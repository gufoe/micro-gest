app.service('$auth', function($http, $location, $status) {
    var self = this,
        _token = null,
        _user = null

    function refreshHeaders() {
        if (_token)
            $http.defaults.headers.common['X-Auth-Token'] = _token
        else
            $http.defaults.headers.common['X-Auth-Token'] = undefined
    }

    this.getUser = () => {
        if (!_user) {
            try {
                _user = JSON.parse(localStorage.getItem('user'))
            } catch (e) {}
        }
        return _user
    }

    this.getToken = () => {
        if (!_token) {
            try {
                _token = JSON.parse(localStorage.getItem('token'))
            } catch (e) {}
        }
        return _token
    }

    this.setUser = (user) => {
        localStorage.setItem('user', JSON.stringify((_user = user)))
    }

    this.setToken = (token) => {
        localStorage.setItem('token', JSON.stringify((_token = token)))
        refreshHeaders()
    }

    this.enforce = () => {
        if (!this.logged()) {
            $status.error('Autenticazione richiesta')
            $location.path('/login')
            throw 'disconnected'
        }
    }

    this.logged = () => {
        var auth = _token && _user
        return auth
    }

    this.reset = () => {
        this.setToken(null)
        this.setUser(null)
    }

    this.logout = () => {
        $http.delete('/sessions').then(res => {
            this.reset()
            $location.path('/login')
        })
    }

    this.getToken()
    this.getUser()
    refreshHeaders()
})