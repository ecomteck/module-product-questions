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

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class UserType
 * @package Ecomteck\ProductQuestions\Model
 */
class UserType implements OptionSourceInterface
{
    /**
     * Customer's user type code
     */
    const USER_TYPE_CUSTOMER = '1';

    /**
     * Guest's user type code
     */
    const USER_TYPE_GUEST = '2';

    /**
     * Administrator's user type code
     */
    const USER_TYPE_ADMINISTRATOR = '3';

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $res = [];
        foreach ($this->getOptionArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }

    /**
     * Get user type labels array
     *
     * @return array
     */
    public function getOptionArray()
    {
        return [
            self::USER_TYPE_GUEST => __('Guest'),
            self::USER_TYPE_CUSTOMER => __('Customer'),
            self::USER_TYPE_ADMINISTRATOR => __('Administrator')
        ];
    }

    /**
     * Get only user types
     *
     * @return array
     */
    public function getArrayKeys()
    {
        $arrayKeys = [];
        foreach ($this->getOptionArray() as $key => $value) {
            $arrayKeys[] = $key;
        }
        return $arrayKeys;
    }

    /**
     * Retrieve the added by
     *
     * @param int $userTypeId
     * @return string
     */
    public function getUserTypeText($userTypeId)
    {
        foreach ($this->getOptionArray() as $key => $value) {
            if ($key == $userTypeId) {
                return $value->getText();
            }
        }
        return '';
    }

    /**
     * Get user type values array with empty value
     *
     * @param int $flag
     * @param int $userTypeId
     * @return array
     */
    public function getOptionValues($flag = null, $userTypeId = null)
    {
        $options = [];
        if ($userTypeId && in_array($userTypeId, $this->getArrayKeys())) {
            foreach ($this->getOptionArray() as $key => $value) {
                if ($key == $userTypeId) {
                    $options[$key] = $value;
                    return $options;
                }
            }
        }
        $options[''] = __('--Please Select--');
        $i = 0;
        foreach ($this->getOptionArray() as $key => $value) {
            if ($flag && $i == count($this->getOptionArray()) - 1) {
                break;
            }
            $options[$key] = $value;
            $i++;
        }
        return $options;
    }

    /**
     * Retrieve the administrator code
     *
     * @return int
     */
    public function getAdministratorCode()
    {
        return self::USER_TYPE_ADMINISTRATOR;
    }

    /**
     * Retrieve the guest code
     *
     * @return int
     */
    public function getGuestCode()
    {
        return self::USER_TYPE_GUEST;
    }

    /**
     * Retrieve the customer code
     *
     * @return int
     */
    public function getCustomerCode()
    {
        return self::USER_TYPE_CUSTOMER;
    }
}
