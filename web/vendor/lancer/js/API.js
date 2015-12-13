var api = {
    loadAclList: function (successFunction) {
        jQuery.ajax({
            url: '/acl_load',
            type: 'GET',
            success: function(response) {
                successFunction(response);
            },
            error: function (xhr) {
                alert('Error! ' + xhr);
            }
        })
    },
    saveRole: function(successFunction, data) {
        jQuery.ajax({
            url: '/acl_save_role',
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
    saveAccess: function(successFunction, data) {
        jQuery.ajax({
            url: '/acl_save_access',
            type: 'POST',
            data: data,
            success: function(response) {
                successFunction(response);
            },
            error: function (xhr) {
                alert('Error! ' + xhr);
            }
        })
    }
};