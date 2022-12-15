<?php
declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\MobileLogin\Helper;

use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Email to send the notification to admin whenever change email / mobile number
 */
class Email extends AbstractHelper
{
    const XML_PATH_FORGOT_EMAIL_IDENTITY = 'customer/password/forgot_email_identity';
    const XML_PATH_CHANGE_EMAIL_TEMPLATE = 'customer/account_information/change_email_template';
    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var TimezoneInterface
     */
    protected $timezoneInterface;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Context $context
     * @param StateInterface $inlineTranslation
     * @param Escaper $escaper
     * @param TransportBuilder $transportBuilder
     * @param TimezoneInterface $timezoneInterface
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder,
        TimezoneInterface $timezoneInterface,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->logger = $context->getLogger();
        $this->timezoneInterface = $timezoneInterface;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @param $scopeUrl
     * @return mixed
     */
    public function getScopeValue($scopeUrl, $storeId)
    {
        return $this->scopeConfig->getValue($scopeUrl, ScopeInterface::SCOPE_STORE, $storeId);
    }


    /**
     * Function to send mail to customer when there is change request in email / mobile number
     */
    public function sendEmail($receiverMail, $customer, $storeId)
    {
        try {
            $this->inlineTranslation->suspend();
            $sender = $this->getScopeValue(self::XML_PATH_FORGOT_EMAIL_IDENTITY, $storeId);
            $templateVars = ['customer'=>$customer,'store'=>$this->storeManager->getStore($storeId)];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($this->getScopeValue(self::XML_PATH_CHANGE_EMAIL_TEMPLATE, $storeId))
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => $storeId
                    ]
                )
                ->setTemplateVars($templateVars)
                ->setFromByScope($sender)
                ->addTo($receiverMail)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }
}
