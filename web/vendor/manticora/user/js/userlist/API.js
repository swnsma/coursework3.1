var api = {
    loadUsers: function (successFunction) {
        jQuery.ajax({
            url: '/usersload',
            type: 'GET',
            success: function(response) {
                successFunction(response);
            },
            error: function (xhr) {
                alert('Error! ' + xhr);
            }
        })
    },
    saveRole: function (successFunction, user, role) {
        jQuery.ajax({
            url: '/saveuserrole',
            type: 'POST',
            data: {user: user, role: role},
            success: function(response) {
                successFunction(response);
            },
            error: function (xhr) {
                alert('Error! ' + xhr);
            }
        })
    }
};