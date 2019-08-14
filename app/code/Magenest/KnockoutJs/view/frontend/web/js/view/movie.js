define([
    'ko',
    'uiComponent',
    'mage/url',
    'mage/storage',
], function (ko, Component, urlBuilder, storage) {
    'use strict';
    var id = 1;

    return Component.extend({

        defaults: {
            template: 'Magenest_KnockoutJs/movie',
        },

        movieList: ko.observableArray([]),

        getMovie: function () {
            var self = this;
            var serviceUrl = urlBuilder.build('knockout/test/movie?id=' + id);
            id++;
            return storage.post(
                serviceUrl,
                ''
            ).done(
                function (response) {
                    self.movieList.push(JSON.parse(response));
                }
            ).fail(
                function (response) {
                    alert(response);
                }
            );
        },
    });
});