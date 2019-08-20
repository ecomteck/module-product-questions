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

namespace Ecomteck\ProductQuestions\Model\ResourceModel\Answer;

/**
 * Class Collection
 * @package Ecomteck\ProductQuestions\Model\ResourceModel\Answer
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'answer_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'ecomteck_product_answer_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'product_answer_collection';

    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \Ecomteck\ProductQuestions\Model\Answer::class,
            \Ecomteck\ProductQuestions\Model\ResourceModel\Answer::class
        );
    }

    /**
     * Add status filter
     *
     * @param int $status
     * @return $this
     */
    public function addStatusFilter($status)
    {
        $this->addFilter(
            'answer_status_id',
            $this->getConnection()->quoteInto('main_table.answer_status_id=?', $status),
            'string'
        );
        return $this;
    }

    /**
     * Add visibility filter
     *
     * @param int $visibility
     * @return $this
     */
    public function addVisibilityFilter($visibility)
    {
        $this->addFilter(
            'answer_visibility_id',
            $this->getConnection()->quoteInto('main_table.answer_visibility_id=?', $visibility),
            'string'
        );
        return $this;
    }
}
