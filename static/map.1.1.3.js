/**
 * Google Maps abstraction layer
 *
 * @version 1.1.3
 * @author Alex Furnica
 *
 * @param elem
 * @param opt
 *
 * @constructor
 */
var Map = function (elem, opt) {
    opt = opt || {};
        opt.click = opt.click instanceof Function
            ? opt.click
            : function () {};

    var that = this;

    /**
     * @type {jQuery}
     */
    that.Element = $(elem);

    /**
     * @type {Array}
     */
    that.Markers = [];

    /**
     * Google Maps settings
     */
    that.Settings = {
        disableDefaultUI: true,
        disableDoubleClickZoom: true,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: Map.Point(opt.Center || {
            lat: 0,
            lng: 0
        }),

        zoom: opt.zoom || 16,
        zoomControl: false,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.DEFAULT
        },

        scaleControl: false,
        streetViewControl: false,
        overviewMapControl: false
    };

    /**
     * @param position
     */
    that.Center = function (position) {
        that.Settings.center = Map.Point(position);

        that.Element.each(that._render);
    };

    /**
     * @param zoom
     * @param scroll
     */
    that.Zoom = function (zoom, scroll) {
        if (scroll != undefined)
            that.Settings.scrollwheel = scroll;

        that.Settings.zoom = zoom;

        that.Element.each(that._render);
    };

    /**
     * @param point
     * @param opt
     * @returns {google.maps.Marker}
     */
    that.Mark = function (point, opt) {
        opt = opt || {};
            opt.pin = opt.pin || false;
            opt.click = opt.click instanceof Function
                ? opt.click
                : function () {};

        var settings = {
            position: Map.Point(point),
            map: that._map
        };

        if (opt.pin)
            settings.icon = {
                url: opt.pin
            };

        var i = 0, marker = new google.maps.Marker(settings);

        marker._data = opt.data || {};

        while (i < that.Markers.length) {
            if (opt.unique || (opt.filter instanceof Function && !opt.filter(that.Markers[i]))) that.Markers[i].setMap(null);

            i++;
        }

        that.Markers.push(marker);

        google.maps.event.addListener(marker, 'click', function (e) {
            opt.click({
                map: that,
                marker: marker,
                data: marker._data,
                position: {
                    lat: e.latLng.lat(),
                    lng: e.latLng.lng()
                }
            });
        });

        return marker;
    };

    /**
     * @param i
     * @private
     */
    that._render = function (i) {
         that._map = new google.maps.Map(that.Element[i], that.Settings);

         google.maps.event.addListener(that._map, 'click', function (e) {
             opt.click({
                 map: that,
                 position: {
                     lat: e.latLng.lat(),
                     lng: e.latLng.lng()
                 }
             });
         });
    };

    that.Element.each(that._render);
};

/**
 * @param position
 * @returns {google.maps.LatLng}
 */
Map.Point = function (position) {
    return new google.maps.LatLng(position.lat, position.lng);
};

Map.Marker = function (map) {

};

/**
 * @param marker
 * @param opt
 *
 * @constructor
 */
Map.Tooltip = function (marker, ready) {
    ready = ready instanceof Function
        ? ready
        : function () {};

    var that = this;

    /**
     * @type {boolean}
     */
    that.custom = false;

    /**
     * DOM elements of tooltip
     */
    that.element = {
        inner: {},
        outer: {},
        _copy: {}
    };

    /**
     * @type {google.maps.InfoWindow}
     * @private
     */
    that._tooltip = new google.maps.InfoWindow({
        content: 'hello'
    });

    /**
     * Init & open InfoWindow
     */
    that.Open = function () {
        that._tooltip.open(marker.map, marker);
    };

    /**
     * Close InfoWindow
     */
    that.Close = function () {
        that._tooltip.close();
    };

    /**
     * @param html
     */
    that.Content = function (html) {
        if (!that.custom) that.element.inner.html(html);
        else that.element.outer
            .html(html)
            .css({
                'margin-left': '20px',
                'margin-top': (-that.element.outer.height() + 60) + 'px'
            });
    };

    /**
     * Reset all default styles of InfoWindow
     */
    that.Reset = function () {
        that.custom = true;

        that.element.outer
            .empty()
            .css({
                padding: '0px',
                border: '0px',
                height: 'auto',
                width: 'auto'
            });
    };

    /**
     * @param css
     */
    that.css = function (css) {
        that.element.outer.css(css);
    };

    google.maps.event.addListener(that._tooltip, 'domready', function () {
        that.element.inner = $(this.k.contentNode);
        that.element.outer = that.element.inner.parent().parent();
        that.element._copy = that.element.outer.html();

        if (that.custom) {
            that.Reset();
            that.element.outer.html(opt.content);
        }

        ready(that);
    });
};

/**
 * @param map
 * @param opt
 *
 * @constructor
 */
Map.Route = function (map, opt) {
    opt = opt || {};
        opt.geodesic = opt.geodesic || true;
        opt.redraw = opt.redraw || true;
        opt.points = opt.points || [];
        opt.style = opt.style || {
            strokeColor: 'black',
            strokeOpacity: 1.0,
            strokeWeight: 1
        };

    var that = this;

    that.points = [];
    that._line = null;
    that.style = opt.style;

    that.Style = function (style) {
        that.style = $.extend(that.style, style);
    };

    /**
     * @param position
     */
    that.Point = function (position) {
        that.points.push(Map.Point(position));

        if (opt.redraw)
            that.Render();

        that.length = that.CalculateLength();
    };

    that.Points = function (points) {
        if (!(points instanceof Array)) return [];

        var i = 0;

        while (i < points.length) {
            that.points.push(Map.Point(points[i]));

            i++;
        }

        return that.points;
    };

    that.CalculateLength = function () {
        try {
            return google.maps.geometry.spherical.computeLength(that.points);
        }
        catch (e) {
            return 0.0;
        }
    };

    /**
     * Rendering the route
     */
    that.Render = function () {
        that._line = new google.maps.Polyline({
            path: that.points,
            geodesic: opt.geodesic,
            map: map,

            strokeColor: that.style.strokeColor,
            stokeOpacity: that.style.strokeOpacity,
            strokeWeight: that.style.strokeWeight
        });
    };

    /**
     * Hide the line
     */
    that.Hide = function () {
        that._line.setMap(null);
    };

    /**
     * Show the map
     */
    that.Show = function () {
        if (that._line == null) that.Render();

        that._line.setMap(map);
    };

    /**
     * Reset the route
     */
    that.Reset = function () {
        that.points = [];

        if (opt.redraw)
            that.Render();
    };

    /**
     * @type {Array}
     */
    that.Points(opt.points);

    /**
     * @type {number}
     */
    that.length = that.CalculateLength();
};