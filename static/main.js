var app = {
    map: {},
    place: {
        elem: {}
    },
    user: {
        elem: {},
        data: {}
    },
    pin: {},
    route: {
        template: {
            create: {}
        }
    },
    template: {
        place: {
            create: {}
        },
        user: {
            login: {},
            profile: {}
        }
    }
};

$(function () {
    app.user.elem = $('#user');
    app.place.elem = $('#place');

    app.map = new Map('#map', {
        click: function (e) {
            console.log(e.position);
        }
    });

    app.map.Zoom(14, true);
    app.map.Center({
        lat: 46.9842927059675,
        lng: 28.852930068969727
    });

    app.template.place.create = new Template('#place-template-create');
    app.template.user.login = new Template('#user-template-login');
    app.template.user.profile = new Template('#user-template-profile');
});

app.user.profile = function () {
    app.template.user.profile._tags = app.user.data;
    app.user.elem.html(app.template.user.profile.Compile());
};

app.user.login = function () {
    app.user.elem.html(app.template.user.login.Compile());
};

app.user.Login = function () {
    $.ajax({
        url: '/user/login',
        dataType: 'json',
        data: {
            ajax: true
        },

        error: function (data) {
            console.log(data);
        },

        success: function (response) {
            switch (response.status) {
                case 305:
                    window.location.href = response.url;
                    break;

                case 200:
                    app.user.data = response.data;
                    app.user.profile();
                    break;

                default:
                    console.log(response);
                    break;
            }
        }
    });
};

app.user.Logout = function () {
    $.ajax({
        url: '/user/logout',
        dataType: 'json',

        error: function (data) {
            console.log(data);
        },

        success: function (response) {
            if (response.status == 200) app.user.login();
            else console.log(response);
        }
    });
};

app.route.create = function () {
    app.route.clear();

    app.template.place.create._tags.place = {
        name: '',
        date: '',
        time: ''
    };

    app.place.elem.html(app.template.place.create.Compile());
};

app.route.clear = function (id) {
    var i = 0, markers = app.map.Find(Map.Marker, function (item) {
        return (id == undefined || item.data._id == id) && item.data.route instanceof Map.Route;
    });

    while (i < markers.length) {
        markers[i].data.route.Hide();
        markers[i].data.finish.Hide();
        markers[i].Icon({
            url: '/static/img/pin_' + markers[i].data.type + '.png'
        });

        i++;
    }
};

app.mark = function (type, opt) {
    opt = opt || {};

    if (app.pin[type] == undefined) return;

    opt.data = opt.data || {};
        opt.data.type = opt.data.type || type;

    app.pin[type]._template = {
        pin: new Template('#pin-template-' + type, opt.data),
        place: new Template('#place-template-' + type, opt.data)
    };

    var marker = app.map.Marker({
        position: opt.position,
        data: opt.data,
        click: app.pin[type].click,
        icon: {
            url: '/static/img/pin_' + type + '.png'
        }
    });

    marker.Show();

    if (app.pin[type].add instanceof Function) app.pin[type].add(marker);
};


app.pin.start = {
    add: function () {},
    click: function () {}
};

app.pin.finish = {
    add: function () {},
    click: function () {}
};

app.pin.race = {
    add: function (marker) {
        marker.data.route = app.map.Route({
            points: marker.data.navpoints,
            style: {
                strokeColor: 'rgb(112, 48, 160)',
                strokeWeight: 2
            }
        });

        marker.data.finish = app.map.Marker({
            position: marker.data.navpoints[marker.data.navpoints.length - 1],
            icon: {
                url: '/static/img/pin_finish.png'
            }
        });
    },
    click: function (marker) {
        marker.Icon({
            url: '/static/img/pin_start.png'
        });

        app.pin.race._template.place.Tag('length', (marker.data.route.length / 1000).toFixed(2));
        app.pin.race._template.place.Tag('members', marker.data.participants.length);

        app.place.elem.html(app.pin.race._template.place.Compile());

        marker.data.route.Show();
        marker.data.finish.Show();
    }
};

app.pin.source = {
    add: function () {},
    click: function () {}
};

app.pin.store = {
    add: function () {},
    click: function () {}
};

app.pin.studio = {
    add: function () {},
    click: function () {}
};

app.pin.rnr = {
    add: function (marker) {
        /*var tooltip = new Map.Tooltip(marker, function (e) {
            e.Reset();
            e.Content(app.pin.rnr._template.pin.Compile());
        });

        tooltip.Open();*/
    },
    click: function (marker) {}
};