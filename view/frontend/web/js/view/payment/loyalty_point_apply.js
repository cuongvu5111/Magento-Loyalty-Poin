/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Smart_Loyalty/js/action/set-coupon-code',
    'Smart_Loyalty/js/action/cancel-coupon'
], function ($, ko, Component, quote, setCouponCodeAction, cancelCouponAction) {
    'use strict';

    var totals = quote.getTotals(),
        pointUsed = ko.observable(null),
        isApplied;
    const point = totals()['total_segments'].filter(function (segment) {
        return segment.code.indexOf('loyalty_point') !== -1;
    });


    if (point[0].value) {
        pointUsed(point[0].value);
    }
    isApplied = ko.observable(point[0].value != null);
    return Component.extend({
        defaults: {
            template: 'Smart_Loyalty/payment/loyalty_point_apply'
        },
        pointUsed: pointUsed,

        /**
         * Applied flag
         */
        isApplied: isApplied,

        /**
         * Coupon code application procedure
         */
        apply: function () {
            //if (this.validate()) {
                setCouponCodeAction(pointUsed(), isApplied);
            //}
        },

        /**
         * Cancel using coupon
         */
        cancel: function () {
            //if (this.validate()) {
                pointUsed('');
                cancelCouponAction(isApplied);
            //}
        },

        /**
         * Coupon form validation
         *
         * @returns {Boolean}
         */
        // validate: function () {
        //     var form = '#loyalty-apply-form';
        //
        //     return $(form).validation() && $(form).validation('isValid');
        // }
    });
});
