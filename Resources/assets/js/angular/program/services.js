/////////////////////////////////////////////////////////
// Servicios
/////////////////////////////////////////////////////////

// Program Service
app.service('ProgramService', function($rootScope, $q, PROPERTIES) {

    return {
        getPrograms: function(params){
            var request = $q.defer();

            var limit = params.limit === undefined ? 10 : params.limit;
            var page = params.page === undefined ? 1 : params.page;
            var orderValue = params.orderValue === undefined ? '' : params.orderValue;
            var orderParameter = params.orderParameter === undefined ? '' : params.orderParameter;
            var filterValue = params.orderValue === undefined ? '' : params.filterValue;
            var filterParameter = params.orderParameter === undefined ? '' : params.filterParameter;

            var urlPath = "programs?" +
                "list_programs[page]=" + page +
                "&list_programs[limit]=" + limit +
                "&list_programs[orderValue]=" + orderValue +
                "&list_programs[orderParameter]=" + orderParameter+
                "&list_programs[filterValue]=" + filterValue +
                "&list_programs[filterParameter]=" + filterParameter;

            $rootScope.appUtil.api({
                loading: false,
                method: 'GET',
                public: true,
                route: urlPath,
                success: function(response) {
                    request.resolve(response);
                },
                error: function(response, status) {
                    request.reject(response);
                }
            });

            return request.promise;
        }
    };
})
;
