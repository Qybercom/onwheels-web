/**
 * Google Maps abstraction layer
 *
 * @version 1.0.2
 * @author Alex Furnica
 */
var Map12 = {
    ok: false,
    settings: {},
    point: {},

    markers: []
};

/**
 * @param container
 * @param point
 * @param opt
 * @returns {google.maps.Marker}
 */
Map12.Mark = function (container, point, opt) {
    opt = opt || {};
        opt.pin = opt.pin || false;

    var settings = {
        position: point,
        map: container
    };

    if (opt.pin)
        settings.icon = {
            url: opt.pin
        };

    var marker = new google.maps.Marker(settings);

    if (opt.unique) {
        var i = 0;

        while (i < Map12.Markers.length) {
            Map12.Markers[i].setMap(null);

            i++;
        }
    }

    Map12.Markers.push(marker);

    return marker;
};

/**
 * @param position
 * @returns {google.maps.LatLng}
 */
Map12.Point = function (position) {
    return new google.maps.LatLng(position.lat, position.lng);
};

/**
 * @param elem
 * @param position
 * @param opt
 */
Map12.Render = function (elem, position, opt) {
    position = position || {};
    position.lat=position.lat || 0;
    position.lng=position.lng || 0;

    opt.click = opt.click || {};
        opt.click.map = opt.click.map || function () {};
        opt.click.marker = opt.click.marker || function () {};

    Map12.point = Map12.Point(position);
    var center = false;

    if (opt.Center)
        center = Map12.Point(center);

    Map12.Settings = {
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: center || Map12.point,
        disableDefaultUI: true,
        disableDoubleClickZoom: true,
        scrollwheel: false,

        zoom: opt.zoom || 14,
        zoomControl: false,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.DEFAULT
        },

        scaleControl: false,
        streetViewControl: false,
        overviewMapControl: false
    };

    Map12.Settings = $.extend(opt, Map12.Settings);

    var check = setInterval(function () {
        if (Map12.ok == false) return;

        var cache = $(elem), container = {}, marker = {};

        cache.each(function (i) {
            container = new google.maps.Map(cache[i], Map12.Settings);
            marker = Map12.Mark(container, Map12.point);

            if (opt.click.map instanceof Function) {
                google.maps.event.addListener(container, 'click', function (e) {
                    opt.click.map({
                        map: container,
                        elem: cache[i],
                        position: {
                            lat: e.latLng.lat(),
                            lng: e.latLng.lng()
                        }
                    });
                });
            }

            if (opt.click.marker instanceof Function) {
                google.maps.event.addListener(marker, 'click', function () {
                    var e = marker.getPosition();

                    opt.click.map({
                        map: container,
                        elem: cache[i],
                        position: {
                            lat: e.lat(),
                            lng: e.lng()
                        }
                    });
                });
            }
        });

        clearInterval(check);
    }, 20);
};