<?php declare(strict_types=1);

/*
 * Copyright (c) 2023 ReCodem Pvt Ltd All rights reserved
*/

namespace Codem\CustomAttributes\Observer;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\DataObject\Copy;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

/**
 * Class SaveOrderBeforeSalesModelQuote to copy the extension attributes from quote table to order table
 */
class SaveOrderBeforeSalesModelQuote implements ObserverInterface
{

    /**
     * @var Copy
     */
    public $objectCopyService;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var AddressRepositoryInterface
     */
    public $addressRepository;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @param Copy $objectCopyService
     * @param LoggerInterface $logger
     * @param AddressRepositoryInterface $addressRepository
     * @param Session $session
     */
    public function __construct(
        Copy $objectCopyService,
        LoggerInterface $logger,
        AddressRepositoryInterface $addressRepository,
        Session $session
    ) {
        $this->objectCopyService = $objectCopyService;
        $this->logger = $logger;
        $this->addressRepository = $addressRepository;
        $this->customerSession = $session;
    }

    /**
     * @param Observer $observer
     * @return $this|void
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();

        $shippingAddress = $quote->getShippingAddress();

        $payment = $quote->getPayment();

        if ($this->customerSession->isLoggedIn()) {
            $cus_shipping_address = $this->addressRepository->getById($shippingAddress->getCustomerAddressId());
            $cus_shipping_address->setCustomAttribute('delivery_note', $shippingAddress->getDeliveryNote());
            $cus_shipping_address->setCustomAttribute('locality', $shippingAddress->getLocality());
            $this->addressRepository->save($cus_shipping_address);
        }

        $this->logger->debug($shippingAddress->getDeliveryNote());

        $order->getShippingAddress()->setDeliveryNote($shippingAddress->getDeliveryNote());
        $order->getShippingAddress()->setLocality($shippingAddress->getLocality());

        $order->getPayment()->setPaymentType($payment->getPaymentType());
        $order->getPayment()->setPaymentComment($payment->getPaymentComment());

        return $this;
    }
}
