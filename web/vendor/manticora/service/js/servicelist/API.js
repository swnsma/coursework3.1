var api = {
    loadDiseas: function (successFunction) {
        jQuery.ajax({
            url: '/services_load',
            type: 'GET',
            success: function(response) {
                successFunction(response);
            },
            error: function (xhr) {
                alert('Error! ' + xhr);
            }
        })
    }
};