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
        window.location= 'http://' + window.location.host + '/' + window.location.pathname.split('/')[1]+ '/patient_service/' + self.id;
    };
}