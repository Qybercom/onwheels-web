$(function () {
    App.Place = new Quark.Model();

    App.Place.Form('#place form', {
        beforeSubmit: function (form) {
            form.notice.success.slideUp(300);
            form.notice.error.slideUp(300);
        },

        200: function (data, form) {
            form.notice.success.html('Место/заезд создано успешно').slideDown(500);
        },
        400: function (data, form) {
            form.notice.error.html('Необходимо указать как минимум 2 точки').slideDown(500);
        }
    });

    App.User = new Quark.Model();

    App.User.showLogin = function () {
        App.User.Frame('#user', '#user-template-login');
    };

    App.User.showProfile = function () {
        App.User.Frame('#user', '#user-template-profile');
        App.User.Frame('#menu-authorized', '#user-template-menu');
    };

    App.User.Login = function () {
        Quark.Get('/user/login', {}, {
            200: function (response) {
                App.User.Data(response.data);
                App.User.showProfile();
            },
            305: function (response) {
                window.location.href = response.url;
            }
        });
    };

    App.User.Logout = function () {
        Quark.Get('/user/logout', {}, {
            200: function () {
                App.User.showLogin();
            }
        });
    };

    App.Menu = new Quark.Model();

    App.Menu.Legend = function () {
        App.User.Frame('#menu-content', '#menu-template-legend');
    };

    App.Map = new Map('#map', {
        click: function (e) {

        }
    });
});

var App = {
    Map: {},
    Markers: [],

    User: {},
    Place: {},

    validator: new Quark.Validator({
        ok: function (elem) {
            elem.siblings('.invalid-field').remove();
        },
        error: function (elem, message) {
            elem.before('<div class="invalid-field">' + message + '</div>');
        }
    })
};

/**
 * @param markers
 */
App.Markers.Load = function (markers) {
    if (!(markers instanceof Array)) return;

    var i = 0;

    while (i < markers.length) {
        App.Markers.push(new App.Marker({
            type: markers[i].type,
            position: markers[i].position,
            data: markers[i],
            template: '#pin-template-' + markers[i].type
        }));

        i++;
    }
};

/**
 * @param callback
 */
App.Markers.Each = function (callback) {
    if (!(callback instanceof Function)) return;

    var i = 0;

    while (i < App.Markers.length) {
        callback(App.Markers[i]);

        i++;
    }
};

/**
 * @param opt
 * @constructor
 */
App.Marker = function (opt) {
    opt = opt || {};

    if (opt.type == undefined) return;
    if (opt.position == undefined) return;

    var that = this;

    that.Data = opt.data;
    that.Type = opt.type;
    that.Position = opt.position;

    that._template = new Quark.Template(opt.template, opt.data);

    that._route = new App.Route({
        points: that.Data.navpoints instanceof Array ? that.Data.navpoints : []
    });

    that._marker = new App.Map.Marker({
        position: that.Position,
        icon: that._icon || {
            url: '/static/img/pin_' + that.Type + '.png'
        }
    });

    that._marker.Show();

    that._tooltip = that._marker.Tooltip({
        custom: true,

        ready: function () {
            that._tooltip.Content(that._template.Compile());
        }
    });

    that._tooltip.Open();

    /**
     * Open marker components and attached route
     */
    that.Open = function () {
        App.Markers.Each(function (marker) {
            marker.Close()
        });

        that._route.Show();

        if (that.Data.navpoints instanceof Array && that.Data.navpoints.length != 0) that._marker.Hide();
    };

    /**
     * Close marker components and attached route
     */
    that.Close = function () {
        that._route.Hide();

        if (that.Data.navpoints instanceof Array && that.Data.navpoints.length != 0) that._marker.Show();
    };

    that._marker.click = function () {
        that.Open();
    };
};

/**
 * @param opt
 * @constructor
 */
App.Route = function (opt) {
    opt = opt || {};

    var that = this;

    /**
     * @type Array
     * @private
     */
    that._points = opt.points instanceof Array ? opt.points : [];

    /**
     * @type {Map.Marker}
     * @private
     */
    that._start = new App.Map.Marker({
        position: that._points.length != 0 ? that._points[0] : {},
        icon: that._icon || {
            url: '/static/img/pin_start.png'
        }
    });

    /**
     * @type {Map.Marker}
     * @private
     */
    that._finish = new App.Map.Marker({
        position: that._points.length != 0 ? that._points[that._points.length - 1] : {},
        icon: that._icon || {
            url: '/static/img/pin_finish.png'
        }
    });

    /**
     * @type {Map.Route}
     * @private
     */
    that._route = App.Map.Route({
        points: that._points,
        style: {
            strokeColor: 'rgb(152, 98, 210)',
            strokeWeight: 2
        }
    });

    /**
     * Show route and its components
     */
    that.Show = function () {
        that._start.Show();
        that._finish.Show();

        that._route.Show();
    };

    /**
     * Hide route and its components
     */
    that.Hide = function () {
        that._start.Hide();
        that._finish.Hide();

        that._route.Hide();
    };
};