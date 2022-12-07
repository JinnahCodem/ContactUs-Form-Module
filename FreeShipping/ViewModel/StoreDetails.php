<?php declare(strict_types=1);

namespace Codem\FreeShipping\ViewModel;

use Magento\Directory\Model\Currency;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class of functions to get the details from the store configuration
 */
class StoreDetails implements ArgumentInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Currency
     */
    protected $currency;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Currency $currency
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Currency $currency
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */

    public function getStoreCurrencySymbol()
    {
        return $this->currency->getCurrencySymbol();
    }

    /**
     * @return int
     * Get Free Shipping Amount from Configuration
     */
    public function getFreeShippingAmount()
    {
        return (int) $this->scopeConfig->getValue('carriers/freeshipping/free_shipping_subtotal');
    }

    /**
     * @return String
     * Get Initial from Configuration
     */
    public function initialMessage()
    {
        return $this->scopeConfig->getValue('free_shipping/general/initial_message');
    }

    /**
     * @return String
     * Get Intermediate from Configuration
     */
    public function intermediateMessage()
    {
        return $this->scopeConfig->getValue('free_shipping/general/intermediate_message');
    }

    /**
     * @return String
     * Get Success from Configuration
     */
    public function successMessage()
    {
        return $this->scopeConfig->getValue('free_shipping/general/success_message');
    }
}
