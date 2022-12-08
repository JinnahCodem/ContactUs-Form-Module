<?php
declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\ContactUs\Block\Customer;

use Magento\Captcha\Block\Captcha;
use Magento\Framework\View\Element\Template;

/**
 *Class of function to show the captcha in the Custom Contactus Form
 */
class Contactus extends Template
{
    /**
     * @return Contactus|void
     */
    protected function _prepareLayout()
    {

        if (!$this->getChildBlock('captcha')) {
            $this->addChild(
                'captcha',
                Captcha::class,
                [
                    'cacheable' => false,
                    'after' => '-',
                    'form_id' => 'contactus_custom_form',
                    'image_width' => 230,
                    'image_height' => 230
                ]
            );
        }

        $this->pageConfig->getTitle()->set(__('Custom ContactUs Form'));
    }
}
