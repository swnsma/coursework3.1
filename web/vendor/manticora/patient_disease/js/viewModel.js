function ViewModel()
{
    var that = this;
    that.diseasesStatic = [];
    that.query = ko.observable('');
    that.diseases = ko.observableArray([]);
    that.totalCount = ko.observable();
    that.active = ko.observable(false);
    that.search = function(value) {
        that.diseases.removeAll();
        for (var disease in that.diseasesStatic ) {
            if (value == ''
                || that.diseasesStatic[disease].user_name.toLowerCase().indexOf(value.toLowerCase()) >= 0
                || that.diseasesStatic[disease].title.toLowerCase().indexOf(value.toLowerCase()) >= 0
            ) {
                that.diseases.push(that.diseasesStatic[disease]);
            }
        }
    };
    that.load = function(response) {
        var data = JSON.parse(response);
        that.totalCount(data.total);
        data = data.items;
        for(var item in data) {
            var disease = new PatientDisease(data[item]);
            that.diseasesStatic.push(disease);
            that.diseases.push(disease);
        }
        that.query('');
    };
    that.reload = function() {
        that.diseases.removeAll();
        that.diseasesStatic = [];
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