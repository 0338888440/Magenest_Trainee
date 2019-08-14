define([
    'ko',
    'uiComponent',
    'mage/url',
    'mage/storage',
], function (ko, Component, urlBuilder,storage) {
    'use strict';
    return Component.extend({

        defaults: {
            template: 'Magenest_KnockoutJs/search',
        },
        movieList: ko.observableArray([]),
        searchMovieName: ko.observable(),
        getMovie: function () {
            var self = this;
            //reset Movie List search become Empty
            self.movieList([]);

            var serviceUrl = urlBuilder.build('knockout/test/searchmovie/name/'+self.searchMovieName());
            return storage.post(
                serviceUrl,
                ''
            ).done(
                function (response) {
                    var arrResponse = response;
                    arrResponse = JSON.parse(arrResponse);
                    for(var i = 0;i < arrResponse.length; i++) {
                        self.movieList.push(JSON.parse(JSON.stringify(arrResponse[i])));
                    }
                }
            ).fail(
                function (response) {
                    alert(response);
                }
            );
        },
    });
});