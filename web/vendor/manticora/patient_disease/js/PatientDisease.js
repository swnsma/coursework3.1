function PatientDisease(obj)
{
    var self = this;
    self.title = obj.title;
    self.patient_name = obj.patient_name;
    self.user_name = obj.user_name;
    self.start = obj.illness_start?obj.illness_start:'Don\'t set.';
    self.end = obj.illness_end?obj.illness_end:'Don\'t set.';
    self.id = obj.id;
    self.healthy = obj.healthy=="1"?'Yes.':'No.';

    self.openPatient = function () {
        var expr = new RegExp('https?://');
        var url =  window.location.host;
        if (url.search(expr) == -1) {
            url = 'http://' + url;
        }
        if (window.location.pathname.split('/')[1]=='app_dev.php') {
            url += window.location.pathname.split('/')[1];
        }
        url += "/patient_disease/" + self.id;
        window.location = url;
    };
}