function User(obj, roles)
{
    var self = this;
    for (var val in obj) {
        self[val] = obj[val];
    }
    self['name'] += ' ' + self['second_name'];
    self.title = ko.observable(self.title);
    self.edit = ko.observable(false);
    self.editRole = function() {
        self.edit(true);
    };
    self.reject = function() {
        self.edit(false);
    };
    self.roles = ko.observableArray(roles);
    self.newRole = ko.observable();

    self.submit = function() {
        if (self.role != self.newRole().id) {
            api.saveRole(function() {
                self.edit(false);
                self.role = self.newRole().id;
                self.title(self.newRole().title);
            }, self.id, self.newRole().id)
        } else {
            self.edit(false);
        }
    }

}

function ViewModel()
{
    var that = this;
    that.usersStatic = [];
    that.query = ko.observable('');
    that.users = ko.observableArray([]);
    that.search = function(value) {
        that.users.removeAll();
        for (var user in that.usersStatic ) {
            if (value == '' || that.usersStatic[user].name.toLowerCase().indexOf(value.toLowerCase()) >= 0) {
                that.users.push(that.usersStatic[user]);
            }
        }
    };
    that.activate = function(){
      api.loadUsers(function(response) {
          var data = JSON.parse(response);
          var items = data.items;
          var roles = data.roles;
          for(var item in items) {
              var user = new User(items[item], roles);
              that.usersStatic.push(user);
              that.users.push(user);
          }
      })
    }
}

var viewModel = new ViewModel();
viewModel.activate();
viewModel.query.subscribe(viewModel.search);
ko.applyBindings(viewModel);