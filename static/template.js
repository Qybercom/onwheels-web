/**
 * Client-side template engine
 *
 * @author Alex Furnica
 * @version 1.0.2
 *
 * @param selector
 * @param tags
 *
 * @constructor
 */
var Template = function (selector, tags) {
    var that = this;

    that.elem = $(selector);
    that.elem.css('display', 'none');

    that._content = that.elem.html();
    that._tags = tags || {};

    /**
     * @param key
     * @param value
     */
    that.Tag = function (key, value) {
        that._tags[key] = value;
    };

    /**
     * @param target
     *
     * @returns {*}
     */
    that.Compile = function (target) {
        var output = that._compile(that._tags, that._content);

        if (target == undefined)
            $(target).html(output);

        return output;
    };

    /**
     * @param tags
     * @param content
     * @param prefix
     *
     * @returns {*}
     *
     * @private
     */
    that._compile = function (tags, content, prefix) {
        var append = '';

        for (key in tags) {
            if (tags[key] == undefined) continue;

            append = (prefix ? prefix : '') + key;

            content = tags[key].constructor == Object// || tags[key].constructor == Array
                ? that._compile(tags[key], content, append + '.')
                : content.replace(new RegExp('{' + append + '}', 'gim'), tags[key].toString());
        }

        return content;
    };
};