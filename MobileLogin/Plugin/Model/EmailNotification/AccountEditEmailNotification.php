<?php declare(strict_types=1);

namespace Codem\MobileLogin\Plugin\Model\EmailNotification;

use Codem\MobileLogin\Helper\Email;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\EmailNotification;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Class AccountEditEmailNotification Plugin to send mail to customer as per mobile number change validation
 */
class AccountEditEmailNotification
{
    /**
     * @var CustomerRepositoryInterface
     */
    protected CustomerRepositoryInterface $existingCustomer;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var Email
     */
    protected $helperEmail;

    /**
     * @param CustomerRepositoryInterface $existingCustomer
     * @param CustomerSession $customerSession
     * @param Email $helperEmail
     */
    public function __construct(CustomerRepositoryInterface $existingCustomer, CustomerSession $customerSession, Email $helperEmail)
    {
        $this->existingCustomer = $existingCustomer;
        $this->customerSession = $customerSession;
        $this->helperEmail = $helperEmail;
    }

    /**
     * @param EmailNotification $subject
     * @param CustomerInterface $savedCustomer
     * @param $origCustomerEmail
     * @param $isPasswordChanged
     * @return void
     */
    public function beforeCredentialsChanged(
        EmailNotification $subject,
        CustomerInterface $savedCustomer,
        $origCustomerEmail,
        $isPasswordChanged = false
    ) {
        $customer = $this->customerSession->getCustomerDataObject();
        $newMobileNumber = $savedCustomer->getCustomAttribute('mobile_number')
            ? $savedCustomer->getCustomAttribute('mobile_number')->getValue()
            : '';
        $oldMobileNumber = $customer->getCustomAttribute('mobile_number')
            ? $customer->getCustomAttribute('mobile_number')->getValue()
            : '';
        if ($newMobileNumber != $oldMobileNumber && $origCustomerEmail == $savedCustomer->getEmail() && !$isPasswordChanged) {
            $this->helperEmail->sendEmail($origCustomerEmail, $customer, $savedCustomer->getStoreId());
        }
    }
}
