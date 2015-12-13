function Service(obj)
{
    var self = this;
    for (var val in obj) {
        self[val] = obj[val];
    }
    self.openService = function () {
        window.location = 'service/' + self.id;
    };
}

function ViewModel()
{
    var that = this;
    that.servicesStatic = [];
    that.query = ko.observable('');
    that.services = ko.observableArray([]);
    that.search = function(value) {
        that.services.removeAll();
        for (var service in that.servicesStatic ) {
            if (value == '' || that.servicesStatic[service].title.toLowerCase().indexOf(value.toLowerCase()) >= 0) {
                that.services.push(that.servicesStatic[service]);
            }
        }
    };
    that.activate = function(){
        api.loadDiseas(function(response) {
            var data = JSON.parse(response);
            var items = data.items;
            for(var item in items) {
                var service = new Service(items[item]);
                that.servicesStatic.push(service);
                that.services.push(service);
            }
        })
    }
}

var viewModel = new ViewModel();
viewModel.activate();
viewModel.query.subscribe(viewModel.search);
ko.applyBindings(viewModel);