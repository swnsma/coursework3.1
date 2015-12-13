var api = {
    loadPatients: function (successFunction) {
        jQuery.ajax({
            url: '/patients_load',
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