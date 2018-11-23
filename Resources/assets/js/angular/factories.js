/////////////////////////////////////////////////////////
// Factorías y filtros
/////////////////////////////////////////////////////////
// Utils
app.factory('appUtil', function($rootScope, localStorageService, $http, PROPERTIES, $stateParams, $translate, Upload) {
    return {
        /**
         * Determina el idioma del usuario
         */
        determinePreferredLanguage: function () {
            var preferredLanguage = $stateParams.locale || navigator.language || navigator.browserLanguage || navigator.systemLanguage || navigator.userLanguage;
            if (typeof preferredLanguage === 'string') {
                var code = preferredLanguage.substring(2, 0);
                if (this.inArray(code, PROPERTIES.LANGUAGES)) {
                    return code;
                } else {
                    return PROPERTIES.DEFAULT_LANGUAGE;
                }
            }
            return null;
        },

        setLanguage: function (locale) {
            if (!locale) {
                locale = this.determinePreferredLanguage();
            }
            $translate.use(locale);
        },

        /**
         * Get constant
         */
        parameters: function (e) {
            return PROPERTIES[e];
        },

        /**
         * Comprueba si un array contiene un elemento determinado
         */
        inArray: function (needle, haystack) {
            var key = '';
            for (key in haystack) {
                if (haystack[key] === needle) {
                    return true;
                }
            }
            return false;
        },

        /**
         * Comprueba si un objecto está vacio
         */
        isEmpty: function (obj) {
            if (angular.isUndefined(obj)) return true;
            if (obj === null) return true;
            if (obj.length > 0)    return false;
            if (obj.length === 0)  return true;
            for (var key in obj) {
                if (hasOwnProperty.call(obj, key)) return false;
            }
            return true;
        },

        /**
         * Registra variables globales
         */
        setGlobals: function () {
            // Cargamos información
            if (angular.isUndefined($rootScope.userData) || $rootScope.appUtil.isEmpty($rootScope.userData)) {
                var localStorage = $rootScope.appUtil.localStorage();
                var data = localStorage.get('data');

                $rootScope.userData = {};
                $rootScope.userData['data'] = (data !== null) ? data : [];
            }
        },

        /**
         * Realiza una petición a la API
         */
        api: function (args, retry) {

            var self = this;

            if (args.loading) {
                this.loading().start();
            }

            var symfony_controller = $rootScope.environment === 'dev' ? '/app_dev.php' : '/app.php';
            var url = args.route;
            if (args.login && args.login === true) {
                url = symfony_controller + '/' + url;
            } else if (args.public && args.public === true) {
                url = symfony_controller + '/web-api/public/' + url;
            } else {
                url = symfony_controller + '/web-api/private/' + url;
            }

            var method = (args.method) ? args.method : 'GET';
            var params = (args.data) ? args.data : {};
            var config = {
                method: method,
                url: url,
                responseType: (args.blob?'blob':'json'),
                withCredentials: true
            };

            // Tipos de datos que se enviarón según el método usado
            if (method === 'GET') {
                config['params'] = params;
            } else {
                config['data'] = jQuery.param(params);
            }

            // Cabeceras
            if (args.authorization && $rootScope.accessToken !== '') {
                $http.defaults.headers.common = {};
                $http.defaults.headers.common.Authorization = 'Bearer ' + $rootScope.accessToken;
            }

            // Content Type
            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded;charset=utf-8";
            $http.defaults.headers.put["Content-Type"] = "application/x-www-form-urlencoded;charset=utf-8";
            
            // XMLHttpRequest
            $http.defaults.headers.post["X-Requested-With"] = "XMLHttpRequest";
            $http.defaults.headers.put["X-Requested-With"] = "XMLHttpRequest";

            // Llamada
            var result = $http(config)
                .then(
                    function (data, status, headers, conf) {
                       if (args['success']) {
                            args.success(data, status);
                        }
                    },
                    function (data, status, headers, conf) {
                        if (args['error']) {
                            args.error(data, status);
                        }
                    }
            );
            result['finally'](function () {
                if (args.loading) {
                    self.loading().stop();
                }
                if (args['end']) {
                    args.end();
                }
            });

            return result;
        },
        
        /**
         * Realiza una petición a la API
         */
        apiUpload: function (args, retry) {

            var self = this;

            if (args.loading) {
                this.loading().start();
            }

            var symfony_controller = $rootScope.environment === 'dev' ? '/app_dev.php' : '';
            var url = args.route;
            if (args.login && args.login === true) {
                url = symfony_controller + '/' + url;
            } else if (args.public && args.public === true) {
                url = symfony_controller + '/web-api/public/' + url;
            } else {
                url = symfony_controller + '/web-api/private/' + url;
            }
            console.log('args: '+JSON.stringify(args));
            //Upload files in request
            var result = Upload.upload({
                url: url,
                data: args.data,
                method: 'POST',
                withCredentials: true,
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(function (resp) {
                //console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
                console.log('Success. Response: ' + resp.data);
                if (args['success']) {
                    args.success(resp['data']);
                }
            }, function (resp) {
                console.log('Error status: ' + resp.status);
                if (args['error']) {
                    args.error(resp.data, resp.status);
                }
            }, function (evt) {
                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
                console.log('progress: ' + progressPercentage + '% ');
            });

            result['finally'](function () {
                if (args.loading) {
                    self.loading().stop();
                }
                if (args['end']) {
                    args.end();
                }
            });
            return result;
        },

    };
})
;
