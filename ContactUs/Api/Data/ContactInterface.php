<?php
declare(strict_types=1);

namespace Codem\ContactUs\Api\Data;

/**
 * Interface for getting the ContactUs form data
 */
interface ContactInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const NAME = 'name';
    const EMAIL = 'email';
    const PHONE_NUMBER = 'phone_number';
    const COMMENT = 'comment';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get EntityId
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set EntityId
     * @param $entityId int
     * @return int
     */
    public function setEntityId($entityId);

    /**
     *Get Name
     *
     * @return string
     */
    public function getName();

    /**
     * Set Name
     * @param $name string
     * @return string
     */
    public function setName($name);

    /**
     * Get EmailId
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set EmailId
     * @param $email string
     * @return string
     */
    public function setEmail($email);

    /**
     * Get PhoneNumber
     *
     * @return string
     */
    public function getPhoneNumber();

    /**
     * Set PhoneNumber
     * @param $phoneNumber string
     * @return string
     */
    public function setPhoneNumber($phoneNumber);

    /**
     * Get Comment
     *
     * @return string
     */
    public function getComment();

    /**
     * Set Comment
     * @param $comment string
     * @return string
     */
    public function setComment($comment);
}
