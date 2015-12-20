function Rejection(obj) {
    var self = this;
    self.label = obj.label;
    self.code = obj.code;
    self.status = obj.status;
    self.flag = ko.observable(self.status!="0");
    self.save = function() {
        api.saveReject(function(){
        }, {code: self.code, status: self.flag()?"1":"0"});
    };
    self.flag.subscribe(self.save);
}

function ViewModel()
{
    var that = this;

    that.services = ko.observableArray([]);
    that.servicesActive = ko.observable(true);
    that.patients = ko.observableArray([]);
    that.patientsActive = ko.observable(true);
    that.rejections = ko.observableArray([]);

    that.name = ko.observable('');
    that.second_name = ko.observable('');
    that.photo = ko.observable('');
    that.email = ko.observable('');

    that.bname = ko.observable('');
    that.bsecond_name = ko.observable('');
    that.bphoto = ko.observable('');
    that.bemail = ko.observable('');

    that.view = ko.observable('');
    that.edit = ko.observable(false);
    that.editStart = function() {
        that.edit(true);
        that.bname(that.name());
        that.bsecond_name(that.second_name());
        that.bphoto(that.photo());
        that.bemail(that.email());
    };

    that.loadServices = function() {
        api.loadServices(function(response) {
            that.services.removeAll();
            response = JSON.parse(response);
            var items = response.items;
            for(var item in items) {
                that.services.push(new PatientService(response.items[item]));
            }
        }, that.servicesActive())
    };

    that.loadPatients = function() {
        api.loadPatients(function(response) {
            that.patients.removeAll();
            response = JSON.parse(response);
            var items = response.items;
            for(var item in items) {
                that.patients.push(new PatientDisease(items[item]));
            }
        }, that.patientsActive())
    };

    that.cancel = function() {
        that.edit(false);
        that.bname('');
        that.bsecond_name('');
        that.bphoto('');
        that.bemail('');
    };

    that.saveChanges = function() {
        if (that.email() == that.bemail()
                && that.photo() == that.bphoto()
                && that.name() == that.bname()
                && that.second_name() == that.bsecond_name()) {
            return that.cancel();
        }

        var data = {
            name: that.bname(),
            second_name: that.bsecond_name(),
            photo: that.bphoto(),
            email: that.bemail()
        };

        api.saveUser(function(){
            that.email(that.bemail());
            that.name(that.bname());
            that.second_name(that.bsecond_name());
            that.photo(that.bphoto());
            that.cancel();
        }, data)
    };

    that.changeLocation = function(location) {
        that.view(location);
    };
    that.activate = function() {
        that.view('myaccount');
        api.loadUser(function(response) {
            response = JSON.parse(response);
            that.name(response.name);
            that.photo(response.photo);
            that.email(response.email);
            that.second_name(response.second_name);
        });
        that.loadServices();
        that.loadPatients();
        api.loadRejections(function(response) {
            response = JSON.parse(response);
            for(var item in response){
                that.rejections.push(new Rejection(response[item]))
            }
        });
    };

    that.isMyaccount = ko.computed(function() {
        return 'myaccount' == that.view();
    });
    that.isServices = ko.computed(function() {
        return 'services' == that.view();
    });
    that.isPatients = ko.computed(function() {
        return 'patients' == that.view();
    });
    that.isRejects = ko.computed(function() {
        return 'rejects' == that.view();
    });

}

viewModel = new ViewModel();
viewModel.servicesActive.subscribe(viewModel.loadServices);
viewModel.patientsActive.subscribe(viewModel.loadPatients);
ko.applyBindings(viewModel);
viewModel.activate();