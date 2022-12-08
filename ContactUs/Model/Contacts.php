<?php
declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\ContactUs\Model;

use Codem\ContactUs\Api\Data\ContactInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Contacts to initialize the resource model objects
 */
class Contacts extends AbstractModel implements ContactInterface
{

    /**
     * Initialize Resource Model
     */
    public function _construct()
    {
        $this->_init('Codem\ContactUs\Model\ResourceModel\Contacts');
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->getdata(self::NAME);
    }

    /**
     * Set Name
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->getdata(self::EMAIL);
    }

    /**
     * Set Email
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->getdata(self::PHONE_NUMBER);
    }

    /**
     * Set PhoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        return $this->setData(self::PHONE_NUMBER, $phoneNumber);
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->getdata(self::COMMENT);
    }

    /**
     * Set Comment
     */
    public function setComment($comment)
    {
        return $this->setData(self::COMMENT, $comment);
    }
}
