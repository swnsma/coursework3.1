function PatientService(obj)
{
    var self = this;
    self.title = obj.title;
    self.user_name = obj.user_name;
    self.date = obj.date;
    self.time = obj.time;
    self.id = obj.id;

    self.openPatient = function () {
        window.location= 'http://' + window.location.host + '/' + window.location.pathname.split('/')[1]+ '/patient_service/' + self.id;
    };
}

function ViewModel()
{
    var that = this;
    that.sevicesStatic = [];
    that.query = ko.observable('');
    that.services = ko.observableArray([]);
    that.totalCount = ko.observable();
    that.active = ko.observable(false);
    that.search = function(value) {
        that.services.removeAll();
        for (var service in that.sevicesStatic ) {
            if (value == ''
                || that.sevicesStatic[service].user_name.toLowerCase().indexOf(value.toLowerCase()) >= 0
                || that.sevicesStatic[service].title.toLowerCase().indexOf(value.toLowerCase()) >= 0
            ) {
                that.services.push(that.sevicesStatic[service]);
            }
        }
    };
    that.load = function(response) {
        var data = JSON.parse(response);
        that.totalCount(data.total);
        data = data.items;
        for(var item in data) {
            var service = new PatientService(data[item]);
            that.sevicesStatic.push(service);
            that.services.push(service);
        }
        that.query('');
    };
    that.reload = function() {
        that.services.removeAll();
        that.sevicesStatic = [];
        api.loadPatients(that.load, patient_identify, that.active());
    };
    that.activate = function(){
        api.loadPatients(that.load, patient_identify, false);
    };
}

var viewModel = new ViewModel();
viewModel.active.subscribe(viewModel.reload);
viewModel.query.subscribe(viewModel.search);
viewModel.activate();
ko.applyBindings(viewModel);