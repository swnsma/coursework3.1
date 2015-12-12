function Disease(obj)
{
    var self = this;
    for (var val in obj) {
        self[val] = obj[val];
    }
    self.openDisease = function () {
        window.location = 'disease/' + self.id;
    };
}

function ViewModel()
{
    var that = this;
    that.diseasesStatic = [];
    that.query = ko.observable('');
    that.diseases = ko.observableArray([]);
    that.search = function(value) {
        that.diseases.removeAll();
        for (var disease in that.diseasesStatic ) {
            if (value == '' || that.diseasesStatic[disease].title.toLowerCase().indexOf(value.toLowerCase()) >= 0) {
                that.diseases.push(that.diseasesStatic[disease]);
            }
        }
    };
    that.activate = function(){
        api.loadDiseas(function(response) {
            var data = JSON.parse(response);
            var items = data.items;
            for(var item in items) {
                var disease = new Disease(items[item]);
                that.diseasesStatic.push(disease);
                that.diseases.push(disease);
            }
        })
    }
}

var viewModel = new ViewModel();
viewModel.activate();
viewModel.query.subscribe(viewModel.search);
ko.applyBindings(viewModel);