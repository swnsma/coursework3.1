function Role(obj, role, routes) {
    var self = this;
    self.role = role;
    self.title = ko.observable(obj.title);
    self.list = ko.observableArray([]);
    self.edit = ko.observable(false);
    self.bufTtile = '';
    self.editStart = function() {
        self.bufTtile = self.title();
        self.edit(true);
    };
    self.save = function() {
        if (self.bufTtile != self.title()) {
            api.saveRole(function() {
                self.edit(false);
            }, {id: self.role, title: self.title()});
        } else {
            self.edit(false);
        }
    };

    var acl = obj['acl'];
    for(var r in routes)
    {
        if(acl[routes[r].id]) {
            self.list.push(new Acl(routes[r], true, role));
        } else {
            self.list.push(new Acl(routes[r], false, role));
        }
    }
}

function Acl(route, flag, role)
{
    var self = this;
    self.id = route.id;
    self.route = route.route;
    self.role = role;
    self.flag = ko.observable(flag);
    self.saveChanges = function() {
        api.saveAccess(function() {
        }, {route: self.id, role: self.role, allow: self.flag()});
    };
    self.flag.subscribe(self.saveChanges);

}

function ViewModel()
{
    var that = this;
    that.roles = ko.observableArray([]);
    that.routes = ko.observableArray([]);
    that.addNew = ko.observable(false);
    that.newRoleTitle = ko.observable('');
    that.add = function() {
        that.addNew(true);
        that.newRoleTitle('')
    };
    that.save = function() {
        api.saveRole(function(response) {
            if(response.id) {
                var obj = {
                    title: that.newRoleTitle(),
                    acl: {}
                };
                that.roles.push(new Role(obj, response.id, that.routes));
                that.addNew(false);
            }
        }, {title: that.newRoleTitle()});
    };
    that.reset = function() {
        that.addNew(false);
    };
    that.activate = function () {
        api.loadAclList(function(response) {
            response = JSON.parse(response);
            var routes = response['routes'];
            that.routes.push({route:''});
            for (var r in routes) {
                that.routes.push(routes[r]);
            }

            var list = response['list'];
            for (var item in list) {
                that.roles.push(new Role(list[item], item, routes));
            }
        })
    }
}

var viewModel = new ViewModel();
viewModel.activate();
ko.applyBindings(viewModel);