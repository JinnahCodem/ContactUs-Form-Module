<?php declare(strict_types=1);

namespace Codem\MobileLogin\Plugin;

use Codem\MobileLogin\Model\LoginByMobilenumber;

/**
 * Class AccountManagementPlugin to fetch and return the user mail id using the registered mobile number
 */
class AccountManagementPlugin
{
    /**
     * @var LoginByMobilenumber
     */
    protected $loginByMobilenumber;

    /**
     * @param LoginByMobilenumber $loginByMobilenumber
     */
    public function __construct(
        LoginByMobilenumber $loginByMobilenumber
    ) {
        $this->loginByMobilenumber = $loginByMobilenumber;
    }

    /**
     * @param $subject
     * @param $username
     * @param $password
     * @return array
     */
    public function beforeAuthenticate($subject, $username, $password)
    {
        if ($email = $this->loginByMobilenumber->authenticateByMobilenumber($username, $password)) {
            $username = $email;
        }

        return [$username, $password];
    }
}
