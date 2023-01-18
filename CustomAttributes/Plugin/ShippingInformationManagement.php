<?php declare(strict_types=1);

namespace Codem\CustomAttributes\Plugin;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement as ShippingInformationManagementOld;
use Magento\Quote\Api\CartRepositoryInterface;

/**
 * Class ShippingInformationManagement shipping extension attributes in the quote table
 */
class ShippingInformationManagement
{
    /**
     * @var CartRepositoryInterface
     */
    public $cartRepository;

    /**
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        CartRepositoryInterface $cartRepository
    ) {
        $this->cartRepository = $cartRepository;
    }

    public function beforesaveAddressInformation(ShippingInformationManagementOld $subject, $cartId, ShippingInformationInterface $addressInformation)
    {
        $quote = $this->cartRepository->getActive($cartId);
        $deliveryNote = $addressInformation->getShippingAddress()->getExtensionAttributes()->getDeliveryNote();
        $locality = $addressInformation->getShippingAddress()->getExtensionAttributes()->getLocality();
        $address = $quote->getShippingAddress();
        $address->setDeliveryNote($deliveryNote);
        $address->setLocality($locality);
        $this->cartRepository->save($quote);
        return [$cartId, $addressInformation];
    }
}
