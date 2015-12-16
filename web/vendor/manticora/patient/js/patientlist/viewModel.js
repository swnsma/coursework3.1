function Patient(obj)
{
    var self = this;
    for (var val in obj) {
        self[val] = obj[val];
    }
    self.patient_phone = self.patient_phone?self.patient_phone:'Not set';

    self['name'] += ' ' + self['second_name'];
    self.openPatient = function () {
        window.location = 'patient/' + self.id;
    };
}

function ViewModel()
{
    var that = this;
    that.patientStatic = [];
    that.query = ko.observable('');
    that.patients = ko.observableArray([]);
    that.search = function(value) {
        that.patients.removeAll();
        for (var patient in that.patientStatic ) {
            if (value == ''
                || that.patientStatic[patient].name.toLowerCase().indexOf(value.toLowerCase()) >= 0
                || that.patientStatic[patient].social_security_number.indexOf(value.toLowerCase()) >= 0
            ) {
                that.patients.push(that.patientStatic[patient]);
            }
        }
    };
    that.activate = function(){
        api.loadPatients(function(response) {
            var data = JSON.parse(response);
            var items = data.items;
            for(var item in items) {
                var patient = new Patient(items[item]);
                that.patientStatic.push(patient);
                that.patients.push(patient);
            }
        })
    }
}

var viewModel = new ViewModel();
viewModel.activate();
viewModel.query.subscribe(viewModel.search);
ko.applyBindings(viewModel);