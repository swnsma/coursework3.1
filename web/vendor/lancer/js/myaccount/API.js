var api = {
    loadUser: function (successFunction) {
        jQuery.ajax({
            url: '/user_load',
            type: 'GET',
            success: function(response) {
                successFunction(response);
            },
            error: function (xhr) {
                alert('Error! ' + xhr);
            }
        })
    },
    saveUser: function (successFunction, data) {
        jQuery.ajax({
            url: '/user_save_changes',
            type: 'POST',
            data: data,
            success: function(response) {
                successFunction(response);
            },
            error: function (xhr) {
                alert('Error! ' + xhr);
            }
        })
    },
    loadServices: function (successFunction, active) {
        jQuery.ajax({
            url: '/patient_service_load_user/'+(active?1:0),
            type: 'GET',
            success: function(response) {
                successFunction(response);
            },
            error: function (xhr) {
                alert('Error! ' + xhr);
            }
        })
    },
    loadPatients: function (successFunction, active) {
        jQuery.ajax({
            url: '/patient_disease_load_user/'+(active?0:1),
            type: 'GET',
            success: function(response) {
                successFunction(response);
            },
            error: function (xhr) {
                alert('Error! ' + xhr);
            }
        })
    },
    loadRejections: function (successFunction) {
        jQuery.ajax({
            url: '/reject_load',
            type: 'GET',
            success: function(response) {
                successFunction(response);
            },
            error: function (xhr) {
                alert('Error! ' + xhr);
            }
        })
    },
    saveReject: function (successFunction, data) {
        jQuery.ajax({
            url: '/reject_save',
            type: 'POST',
            data: data,
            success: function (response) {
                successFunction(response);
            },
            error: function (xhr) {
                alert('Error! ' + xhr);
            }
        })
    }
};