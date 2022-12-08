<?php
declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\ContactUs\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Email to send the notification to admin whenever there is enquiry
 */
class Email extends AbstractHelper
{
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
     * @param Context $context
     * @param StateInterface $inlineTranslation
     * @param Escaper $escaper
     * @param TransportBuilder $transportBuilder
     * @param TimezoneInterface $timezoneInterface
     */
    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder,
        TimezoneInterface $timezoneInterface,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->logger = $context->getLogger();
        $this->timezoneInterface = $timezoneInterface;
        $this->scopeConfig = $scopeConfig;

    }

    /**
     * @return mixed
     */
    public function getSendername()
    {
        return $this->scopeConfig->getValue('trans_email/ident_general/name');
    }

    /**
     * @return mixed
     */
    public function getSendermail()
    {
        return $this->scopeConfig->getValue('trans_email/ident_general/email');
    }

    /**
     * @return mixed
     */
    public function getreceivermail()
    {
        return $this->scopeConfig->getValue('trans_email/ident_support/email');
    }

    /**
     * Function to send notification to admin
     */
    public function sendEmail($postValue)
    {
        try {
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => $this->escaper->escapeHtml($this->getSendername()),
                'email' => $this->escaper->escapeHtml($this->getSendermail()),
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('contactus_email_template')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars($postValue)
                ->setFromByScope($sender)
                ->addTo($this->getreceivermail())
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }
}
