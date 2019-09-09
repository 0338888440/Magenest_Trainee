/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'Magento_Ui/js/form/element/abstract',
    'jquery',
    'ko',
], function (AbstractComponent, $, ko) {
    'use strict';
    return AbstractComponent.extend({
        defaults: {},
        initObservable: function () {
            var self = this;
            self._super();
            self.observe(
                {
                    'editRatingValue': '',
                    'isHover1': false,
                    'isHover2': false,
                    'isHover3': false,
                    'isHover4': false,
                    'isHover5': false,
                    'isCheck1': '',
                    'isCheck2': '',
                    'isCheck3': '',
                    'isCheck4': '',
                    'isCheck5': '',
                },
            );
            return this;
        },
        initialize: function () {
            this._super();
            switch (this.initialValue) {
                case '1':
                    this.isCheck1(true);
                    this.isCheck2(false);
                    this.isCheck3(false);
                    this.isCheck4(false);
                    this.isCheck5(false);
                    break;
                case '2':
                    this.isCheck1(true);
                    this.isCheck2(true);
                    this.isCheck3(false);
                    this.isCheck4(false);
                    this.isCheck5(false);
                    break;
                case '3':
                    this.isCheck1(true);
                    this.isCheck2(true);
                    this.isCheck3(true);
                    this.isCheck4(false);
                    this.isCheck5(false);
                    break;
                case '4':
                    this.isCheck1(true);
                    this.isCheck2(true);
                    this.isCheck3(true);
                    this.isCheck4(true);
                    this.isCheck5(false);
                    break;
                case '5':
                    this.isCheck1(true);
                    this.isCheck2(true);
                    this.isCheck3(true);
                    this.isCheck4(true);
                    this.isCheck5(true);
                    break;
            }
            return this;
        },

        mouseOver1: function () {
            this.isHover1(true);
        },
        mouseOut1: function () {
            this.isHover1(false);
        },
        click1: function (data) {
            this.isCheck1(true);
            this.isCheck2(false);
            this.isCheck3(false);
            this.isCheck4(false);
            this.isCheck5(false);
            var self = this;
            data.source.data.rating = self.editRatingValue();
            return true;
        },

        mouseOver2: function () {
            this.isHover1(true);
            this.isHover2(true);
        },
        mouseOut2: function () {
            this.isHover1(false);
            this.isHover2(false);
        },
        click2: function (data) {
            this.isCheck1(true);
            this.isCheck2(true);
            this.isCheck3(false);
            this.isCheck4(false);
            this.isCheck5(false);
            var self = this;
            data.source.data.rating = self.editRatingValue();
            return true;
        },

        mouseOver3: function () {
            this.isHover1(true);
            this.isHover2(true);
            this.isHover3(true);
        },
        mouseOut3: function () {
            this.isHover1(false);
            this.isHover2(false);
            this.isHover3(false);
        },
        click3: function (data) {
            this.isCheck1(true);
            this.isCheck2(true);
            this.isCheck3(true);
            this.isCheck4(false);
            this.isCheck5(false);
            var self = this;
            data.source.data.rating = self.editRatingValue();
            return true;
        },

        mouseOver4: function () {
            this.isHover1(true);
            this.isHover2(true);
            this.isHover3(true);
            this.isHover4(true);
        },
        mouseOut4: function () {
            this.isHover1(false);
            this.isHover2(false);
            this.isHover3(false);
            this.isHover4(false);
        },
        click4: function (data) {
            this.isCheck1(true);
            this.isCheck2(true);
            this.isCheck3(true);
            this.isCheck4(true);
            this.isCheck5(false);
            var self = this;
            data.source.data.rating = self.editRatingValue();
            return true;
        },

        mouseOver5: function () {
            this.isHover1(true);
            this.isHover2(true);
            this.isHover3(true);
            this.isHover4(true);
            this.isHover5(true);
        },
        mouseOut5: function () {
            this.isHover1(false);
            this.isHover2(false);
            this.isHover3(false);
            this.isHover4(false);
            this.isHover5(false);
        },
        click5: function (data) {
            this.isCheck1(true);
            this.isCheck2(true);
            this.isCheck3(true);
            this.isCheck4(true);
            this.isCheck5(true);
            var self = this;
            data.source.data.rating = self.editRatingValue();
            return true;
        },
    });
});
