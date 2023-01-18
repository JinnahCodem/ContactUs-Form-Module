/*
 * Copyright (c) 2023 ReCodem Pvt Ltd All rights reserved
 */

var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'Codem_CustomAttributes/js/action/place-order-mixin': true
            },
            'Magento_Checkout/js/action/set-payment-information-extended': {
                'Codem_CustomAttributes/js/action/set-payment-information-extended-mixin': true
            }
        }
    }
};
