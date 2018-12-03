jQuery.noConflict();

var app = angular.module('ArduinoOTAServerApp', ['ui.router', 'ngAnimate', 'ngSanitize', 'uuid4', 'pascalprecht.translate', 'yaru22.angular-timeago', 'ngFileUpload', 'bw.paging', 'angular-loading-bar']);

/////////////////////////////////////////////////////////
// Constantes
/////////////////////////////////////////////////////////
app.constant("PROPERTIES", {
    ENV: 'PROD',
    LANGUAGES: ['en'],
    DEFAULT_LANGUAGE: 'en',
    PROJECT: 'arduinoOTAServer'
})

/////////////////////////////////////////////////////////
// Configuración
/////////////////////////////////////////////////////////

// Translate Provider
.config(['$translateProvider', 'PROPERTIES', function ($translateProvider, PROPERTIES) {

    var inArray = function(needle, haystack) {
        var key = '';
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
        return false;
    };

    $translateProvider.useSanitizeValueStrategy(null);

    // configures staticFilesLoader
    $translateProvider.useStaticFilesLoader({
        prefix: '/bundles/uegmobilearduinootaserver/i18n/locale-',
        suffix: '.json'
    })

    // system language
    .determinePreferredLanguage(function () {
            var preferredLanguage = navigator.language || navigator.browserLanguage || navigator.systemLanguage || navigator.userLanguage;

            if (typeof preferredLanguage === 'string') {
                var code = preferredLanguage.substring(2, 0);

                if (inArray(code, PROPERTIES.LANGUAGES)) {
                    return code;
                } else {
                    return PROPERTIES.DEFAULT_LANGUAGE;
                }
            }

            return null;
     })

    // fallbacks
    .fallbackLanguage([PROPERTIES.DEFAULT_LANGUAGE]);
}])

.config(['cfpLoadingBarProvider', function(cfpLoadingBarProvider) {
    cfpLoadingBarProvider.includeSpinner = false;
}])

// Fix twig template conflicts
.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
});
;

/////////////////////////////////////////////////////////
// App run
/////////////////////////////////////////////////////////
app.run(function($rootScope, appUtil, PROPERTIES) {
    // Registrando servicio appUtil
    $rootScope.appUtil = appUtil;

    //Set environment
    var env = jQuery('body').attr('data-env');
    if(env !== undefined && env === 'debug'){
        $rootScope.environment = 'dev'
    }else{
        $rootScope.environment = 'prod'
    }
});
