<?php

declare(strict_types=1);

namespace Codem\ContactUs\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;


/**
* Provides the user data to fill the form.
 */
class ContactUsDetails extends Template implements ArgumentInterface
{
    public function getFormAction()
    {

        return $this->getUrl('contactus/index/submit');

    }
}

