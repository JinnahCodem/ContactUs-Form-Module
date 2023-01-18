<?php declare(strict_types=1);

/*
 * Copyright (c) 2023 ReCodem Pvt Ltd All rights reserved
*/

namespace Codem\CustomAttributes\Plugin;

use Magento\Checkout\Api\GuestPaymentInformationManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;

/**
 * Class GuestPaymentInformationManagement to add payment extension attribute
 */

class GuestPaymentInformationManagement
{
    /**
     * @var QuoteIdMaskFactory
     */
    protected $quoteIdMaskFactory;

    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @param QuoteIdMaskFactory $quoteRepository
     * @param CartRepositoryInterface $quoteRepository
     */

    public function __construct(
        QuoteIdMaskFactory $quoteIdMaskFactory,
        CartRepositoryInterface $quoteRepository
    ) {
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @inheritdoc
     */

    public function beforeSavePaymentInformationAndPlaceOrder(
        GuestPaymentInformationManagementInterface $subject,
        $cartId,
        $email,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ) {
        if ($cartId) {
            $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
            $quoteId = $quoteIdMask->getQuoteId();
            $quote = $this->quoteRepository->getActive($quoteId);
            $payment_type = $paymentMethod->getExtensionAttributes()->getPaymentType();
            $payment_comment = $paymentMethod->getExtensionAttributes()->getPaymentComment();
            $payment = $quote->getPayment();
            $payment->setPaymentType($payment_type);
            $payment->setPaymentComment($payment_comment);
        }
    }
    /**
     * @inheritdoc
     */

    public function beforeSavePaymentInformation(
        GuestPaymentInformationManagementInterface $subject,
        $cartId,
        $email,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ) {
        if ($cartId) {
            $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
            $quoteId = $quoteIdMask->getQuoteId();
            $quote = $this->quoteRepository->getActive($quoteId);
            $payment_type = $paymentMethod->getExtensionAttributes()->getPaymentType();
            $payment_comment = $paymentMethod->getExtensionAttributes()->getPaymentComment();
            $payment = $quote->getPayment();
            $payment->setPaymentType($payment_type);
            $payment->setPaymentComment($payment_comment);
        }
    }
}
