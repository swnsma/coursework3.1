var api = {
    loadPatients: function (successFunction, id, active) {
        console.log(active);
        jQuery.ajax({
            url: '/patient_disease_load/'+id+'/'+(active?0:1),
            type: 'GET',
            success: function(response) {
                successFunction(response);
            },
            error: function (xhr) {
                alert('Error! ');
                console.log(xhr);
            }
        })
    }
};