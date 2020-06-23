/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/totals'
], function (Component, totals) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Smart_Loyalty/summary/checkout_loyalty_point'
        },

        /**
         * @return {*|Boolean}
         */
        isDisplayed: function () {
            return this.getPureValue() != 0; //eslint-disable-line eqeqeq
        },

        /**
         * Get pure value.
         *
         * @return {*}
         */
        getPureValue: function () {
            return totals.getSegment('loyalty_point').value;
        },

        /**
         * @return {*|String}
         */
        getValue: function () {
            return '-' + this.getFormattedPrice(this.getPureValue());
        },

        getTitle: function () {
            return 'Loyalty Point';
        }

    });
});
