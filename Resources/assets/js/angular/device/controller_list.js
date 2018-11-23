/////////////////////////////////////////////////////////
// Controllers
/////////////////////////////////////////////////////////

// User
app.controller('ArduinoDeviceListCtrl', function($scope, $rootScope, DeviceService, $translate, $compile, $timeout, $filter, $window)
{

    $scope.initProgramTable = function(){
        console.log('initProgramTable!');

        if(!$translate.isReady()){
            setTimeout(function() { $scope.initProgramTable(); }, 100);
            return;
        }
        
        var lang = jQuery("meta[http-equiv=content-language]").attr("content");
        var url_lang = "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json"
        if (lang == "es") {
            url_lang = "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json";
        }

        var buttons = "<button class='edit-button'>Edit</button>";

        // Setup #centers_table DataTable
        var table = jQuery("#users_table").DataTable({
            "processing": true, // Indicador de procesamiento
            "serverSide": true, // Procesamiento del lado servidor
            "stateSave": false, //
            "select": false,    // Para seleccionar elementos en una tabla, por defecto es false
            "ajax": $scope.loadUsers,
            "columns":[
                { "data": "first_name" },
                { "data": "last_name" },
                { "data": "username" },
                { "data": "roleNames" , "render": function ( data, type, row, meta ) {
                    return $translate.instant(data);
                    }
                },
                { "data": "is_enabled" , "render": function (data) {
                    return data ? '<a class="ui green empty circular label"></a>':'<a class="ui red empty circular label"></a>';
                }
                },
                { "data": null, "defaultContent": buttons},
            ],
            "language": {
                "url": url_lang
            },
        });

        // Setup - add a text input to each footer cell
        jQuery('#users_table tfoot th').each( function (i) {
            var title = jQuery(this).text();
            var searchType = jQuery(this).attr('search-type');
            if(title && searchType == 'text'){
                jQuery(this).html('<input type="text" id="search_'+i+'" placeholder="Search '+title+'" />');
            } else if (title && searchType == 'bool'){
                jQuery(this).html('<div class="ui selection dropdown upward" ><input type="hidden" id="search_'+i+'"><i class="dropdown icon"></i><i class="remove icon"></i><div class="default text">Search '+title+'</div><div class="menu"><div class="item" data-value="1">On</div><div class="item" data-value="0">Off</div></div></div>');
            } else if (title && searchType == 'roles_type'){
                jQuery(this).html('<div class="ui selection dropdown upward" ><input type="hidden" id="search_'+i+'"><i class="dropdown icon"></i><i class="remove icon"></i><div class="default text">Search '+title+'</div><div class="menu"><div class="item" data-value="ROLE_ADMIN">'+$translate.instant('ROLE_ADMIN')+'</div><div class="item" data-value="ROLE_USER">'+$translate.instant('ROLE_USER')+'</div></div></div>');
            } else {
                jQuery(this).html('');
            }
        });

        jQuery('.ui.dropdown .remove.icon').on('click', function(e){
            jQuery(this).parent('.dropdown').dropdown('clear');
            //console.log('clear');
            e.stopPropagation();
        });

        // Apply the search
        table.columns().every( function (i) {
            var that = this;
            jQuery('#search_'+i, this.footer() ).on( 'keyup change', function () {

                if ( that.search() !== this.value ) {
                    that
                        .search( this.value )
                        .draw();
                }
            });
        });

        // Click button on cell
        jQuery('#users_table tbody').on( 'click', 'button', function () {
            var data = table.row( jQuery(this).parents('tr') ).data();
            $window.location.href = data.web_url_edit;
        });

        jQuery('.ui.dropdown.upward').dropdown({'direction': 'upward'});
    };

    $scope.loadUsers = function (data, callback, settings) {

        // Filtering params
        var filterValue = [];
        var filterParameter = [];

        for (var i=0 ; i < data.columns.length; i++){
            if(data.columns[i].search.value.length > 0){
                //console.log('filtramos por '+i+' '+data.columns[i].search.value);
                filterValue.push(data.columns[i].search.value);
                filterParameter.push(data.columns[i].data);
            }
        }
        if(data.search.value.length > 0){
            filterValue.push(data.search.value);
            filterParameter.push('tablesearch');
        }

        // Ordering params
        var orderValue = [];
        var orderParameter = [];
        for (var j=0 ; j < data.order.length; j++){
            orderValue.push(data.order[j].dir);
            orderParameter.push(data.columns[data.order[j].column].data);
        }

        var params = {
            'page': (data['start'] / data['length'])+1,
            'limit': data['length'],
            'orderValue': orderValue.join(","),
            'orderParameter': orderParameter.join(","),
            'filterValue': JSON.stringify(filterValue),
            'filterParameter': filterParameter.join(",")
        };

        UserService.getUsers(params).then(
            //success
            function (data) {
                var result = {
                    'recordsTotal': data['data']['total'],
                    'recordsFiltered': data['data']['total'],
                    'data': data['data']['_embedded']['items']
                };
                //console.log(JSON.stringify(result));
                callback(result);
            },
            //error
            function (data, status, headers, conf) {
                console.log('error: ' + JSON.stringify(data));
                var result = {
                    'error': data
                };
                callback(result);
            }

        );

    };
})
;
