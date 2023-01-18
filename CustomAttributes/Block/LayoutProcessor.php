<?php declare(strict_types=1);

/*
 * Copyright (c) 2023 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\CustomAttributes\Block;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

/**
 * Class LayoutProcess to add Custom Attributes in Shipping Form
 */
class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @param $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {
        $shippingAttributeNote = 'delivery_note';
        $fieldConfigurationNote = [
            'component' => 'Magento_Ui/js/form/element/textarea',
            'config' => [
                'customScope' => 'shippingAddress.extension_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/textarea',
                'tooltip' => [
                    'description' => 'Here you can leave delivery notes',
                ],
            ],
            'dataScope' => 'shippingAddress.extension_attributes' . '.' . $shippingAttributeNote,
            'label' => 'Delivery Notes',
            'provider' => 'checkoutProvider',
            'sortOrder' => 1000,
            'validation' => [
                'required-entry' => true
            ],
            'options' => [],
            'filterBy' => null,
            'customEntry' => null,
            'visible' => true,
            'value' => ''
        ];

        $shippingAttributeLocality = 'locality';
        $fieldConfigurationLocality = [
            'component' => 'Magento_Ui/js/form/element/textarea',
            'config' => [
                'customScope' => 'shippingAddress.extension_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/textarea',
                'tooltip' => [
                    'description' => 'Here you can enter locality',
                ],
            ],
            'dataScope' => 'shippingAddress.extension_attributes' . '.' . $shippingAttributeLocality,
            'label' => 'Locality',
            'provider' => 'checkoutProvider',
            'sortOrder' => 900,
            'validation' => [
                'required-entry' => true
            ],
            'options' => [],
            'filterBy' => null,
            'customEntry' => null,
            'visible' => true,
            'value' => ''
        ];

        $jsLayout['components']['checkout']['children']
        ['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']
        ['children'][$shippingAttributeNote] = $fieldConfigurationNote;

        $jsLayout['components']['checkout']['children']
        ['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']
        ['children'][$shippingAttributeLocality] = $fieldConfigurationLocality;

        return $jsLayout;
    }
}
