function PatientService(obj)
{
    var self = this;
    self.title = obj.title;
    self.patient_name = obj.patient_name;
    self.user_name = obj.user_name;
    self.date = obj.date;
    self.time = obj.time;
    self.id = obj.id;
    self.price = obj.price;

    self.openPatient = function () {
        var expr = new RegExp('https?://');
        var url =  window.location.host;
        if (url.search(expr) == -1) {
            url = 'http://' + url;
        }
        if (window.location.pathname.split('/')[1]=='app_dev.php') {
            url += window.location.pathname.split('/')[1];
        }
        url += "/patient_service/" + self.id;
        window.location = url;
    };
}