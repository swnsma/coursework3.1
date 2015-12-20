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
        window.location= 'http://' + window.location.host + '/' + window.location.pathname.split('/')[1]+ '/patient_disease/' + self.id;
    };
}