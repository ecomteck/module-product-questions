<?php
/**
 * Ecomteck
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the ecomteck.com license that is
 * available through the world-wide-web at this URL:
 * https://ecomteck.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Ecomteck
 * @package     Ecomteck_ProductQuestions
 * @copyright   Copyright (c) 2019 Ecomteck (https://ecomteck.com/)
 * @license     https://ecomteck.com/LICENSE.txt
 */

namespace Ecomteck\ProductQuestions\Model;

/**
 * Class AdminUser
 * @package Ecomteck\ProductQuestions\Model
 */
class AdminUser
{
    /**
     * Backend Auth session model
     *
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $authSession;

    /**
     * @param \Magento\Backend\Model\Auth\Session $authSession
     */
    public function __construct(
        \Magento\Backend\Model\Auth\Session $authSession
    ) {
        $this->authSession = $authSession;
    }

    /**
     * Retrieve the email of admin user
     *
     * @param string $default
     * @return string
     */
    public function getEmail($default = 'ecomteck_ecommerce@magento.com')
    {
        if ($this->authSession->isLoggedIn()) {
            return $this->authSession->getUser()->getEmail();
        }

        return $default;
    }

    /**
     * Retrieve the name of admin user
     *
     * @param string $default
     * @return string
     */
    public function getName($default = 'ecomteck')
    {
        if ($this->authSession->isLoggedIn()) {
            return $this->authSession->getUser()->getFirstname();
        }

        return 'ecomteck';
    }

    /**
     * Retrieve the ID of admin user
     *
     * @return int|null
     */
    public function getID()
    {
        if ($this->authSession->isLoggedIn()) {
            return $this->authSession->getUser()->getUserId();
        }
        return null;
    }
}
