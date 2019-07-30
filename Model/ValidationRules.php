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

use Magento\Framework\Exception\LocalizedException;

/**
 * Class ValidationRules
 * @package Ecomteck\ProductQuestions\Model
 */
class ValidationRules
{
    /**
     * Visibility
     *
     * @var Visibility
     */
    protected $visibility;

    /**
     * UserType
     *
     * @var UserType
     */
    protected $userType;

    /**
     * Status
     *
     * @var Status
     */
    protected $status;

    /**
     * ValidationRules constructor.
     * @param Visibility $visibility
     * @param UserType $userType
     * @param Status $status
     */
    public function __construct(
        Visibility $visibility,
        UserType $userType,
        Status $status
    ) {
        $this->visibility = $visibility;
        $this->userType = $userType;
        $this->status = $status;
    }

    /**
     * @param $value
     * @param $label
     * @throws LocalizedException
     */
    public function validateEmail($value, $label)
    {
        $validator = new \Zend_Validate_EmailAddress();
        $this->validateEmptyValue($value, $label);
        if (!$validator->isValid($value)) {
            throw new LocalizedException(__('"%1" is not a valid email address.', $value));
        }
    }

    /**
     * @param $value
     * @param $label
     * @throws LocalizedException
     */
    public function validateEmptyValue($value, $label)
    {
        if (empty($value)) {
            throw new LocalizedException(
                __('"%1" is required.', $label)
            );
        }
    }

    /**
     * @param $value
     * @param $label
     * @throws LocalizedException
     */
    public function validateIntegerNumber($value, $label)
    {
        if (!empty($value) && !ctype_digit($value)) {
            throw new LocalizedException(
                __('"%1" is not a valid number.', $label)
            );
        }
    }

    /**
     * @param $value
     * @throws LocalizedException
     */
    public function validateVisibility($value)
    {
        $this->validateOptionArray($value, 'Visibility code', $this->visibility->getOptionArray());
    }

    /**
     * @param $value
     * @throws LocalizedException
     */
    public function validateStatus($value)
    {
        $this->validateOptionArray($value, 'Status code', $this->status->getOptionArray());
    }

    /**
     * @param $value
     * @param string $type
     * @throws LocalizedException
     */
    public function validateUserType($value, $type = 'answer')
    {
        switch ($type) {
            case 'question':
                $this->validateOptionArray($value, 'User type code', $this->userType->getOptionArray(), true);
                break;

            case 'answer':
                $this->validateOptionArray($value, 'User type code', $this->userType->getOptionArray());
                break;

            default:
                # code...
                break;
        }
    }

    /**
     * @param $value
     * @param $label
     * @param $optionArray
     * @param bool $isQuestion
     * @throws LocalizedException
     */
    public function validateOptionArray($value, $label, $optionArray, $isQuestion = false)
    {
        $valueKeys = [];

        if ($isQuestion) {
            unset($optionArray[3]);
        }

        foreach ($optionArray as $key => $valueKey) {
            $valueKeys[] = $key;
        }

        $this->validateEmptyValue($value, $label);

        if (!in_array($value, $valueKeys)) {
            throw new LocalizedException(
                __('%1 is not a valid %2.', $label, $label)
            );
        }
    }
}
