<?php declare(strict_types=1);

/*
 * Copyright (c) 2022-2023 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\CustomAttributes\Plugin;

use Magento\Checkout\Api\PaymentInformationManagementInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;

/**
 * Class PaymentInformationManagement to add payment extension attribute
 */

class PaymentInformationManagement
{

    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param PaymentInformationManagementInterface $subject
     * @param $cartId
     * @param PaymentInterface $paymentMethod
     * @param AddressInterface|null $billingAddress
     * @return void
     * @throws NoSuchEntityException
     */
    public function beforeSavePaymentInformationAndPlaceOrder(
        PaymentInformationManagementInterface $subject,
        $cartId,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ) {
        if ($cartId) {
            $quote = $this->quoteRepository->getActive($cartId);
            $payment_type = $paymentMethod->getExtensionAttributes()->getPaymentType();
            $payment_comment = $paymentMethod->getExtensionAttributes()->getPaymentComment();
            $payment = $quote->getPayment();
            $payment->setPaymentType($payment_type);
            $payment->setPaymentComment($payment_comment);
        }
    }

    /**
     * @param PaymentInformationManagementInterface $subject
     * @param $cartId
     * @param PaymentInterface $paymentMethod
     * @param AddressInterface|null $billingAddress
     * @return void
     * @throws NoSuchEntityException
     */
    public function beforeSavePaymentInformation(
        PaymentInformationManagementInterface $subject,
        $cartId,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ) {
        if ($cartId) {
            $quote = $this->quoteRepository->getActive($cartId);
            $payment_type = $paymentMethod->getExtensionAttributes()->getPaymentType();
            $payment_comment = $paymentMethod->getExtensionAttributes()->getPaymentComment();
            $payment = $quote->getPayment();
            $payment->setPaymentType($payment_type);
            $payment->setPaymentComment($payment_comment);
        }
    }
}
