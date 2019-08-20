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

namespace Ecomteck\ProductQuestions\Model\ResourceModel\Question;

/**
 * Class Collection
 * @package Ecomteck\ProductQuestions\Model\ResourceModel\Question
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'question_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'ecomteck_product_questions_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'product_questions_collection';

    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \Ecomteck\ProductQuestions\Model\Question::class,
            \Ecomteck\ProductQuestions\Model\ResourceModel\Question::class
        );
    }

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        foreach ($this->_items as $item) {
            $item->setStoreId($this->getStoreViewIds($item->getQuestionId()));
        }
        return parent::_afterLoad();
    }

    /**
     * Get the store ids
     *
     * @param int $questionId
     * @return array
     */
    protected function getStoreViewIds($questionId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('ecomteck_product_questions_store')
        )
        ->where('question_id =?', $questionId)
        ->group('store_view_id');
        $result = $connection->fetchAll($select);
        $storesData = [];
        if ($result) {
            foreach ($result as $storeData) {
                $storesData[] = $storeData['store_view_id'];
            }
        }
        return $storesData;
    }

    /**
     * Add store filter
     *
     * @param int|int[] $storeId
     * @return $this
     */
    public function addStoreFilter($storeId)
    {
        $inCond = $this->getConnection()->prepareSqlCondition('store.store_view_id', ['in' => $storeId]);
        $this->getSelect()->join(
            ['store' => $this->getTable('ecomteck_product_questions_store')],
            'main_table.question_id=store.question_id',
            ['store.store_view_id']
        );
        $this->getSelect()->where($inCond);
        return $this;
    }

    /**
     * Add status filter
     *
     * @param int $status
     * @return $this
     */
    public function addStatusFilter($status)
    {
        $this->addFieldToFilter('question_status_id', $status);
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
        $this->addFieldToFilter('question_visibility_id', $visibility);
        return $this;
    }

    /**
     * Add product filter
     *
     * @param int $productId
     * @return $this
     */
    public function addProductIdFilter($productId)
    {
        $this->getSelect()->joinLeft(
            ['shared' => $this->getTable('ecomteck_product_questions_sharing')],
            'main_table.question_id = shared.question_id',
            ['product_id']
        )->orWhere(
            'shared.product_id = ?',
            $productId
        )->group(
            'main_table.question_id'
        );

        return $this;
    }

    /**
     * Add customer filter
     *
     * @param int|string $customerId
     * @return $this
     */
    public function addCustomerFilter($customerId)
    {
        $this->addFieldToFilter('customer_id', $customerId);
        return $this;
    }

    /**
     * Set date order
     *
     * @param string $dir
     * @return $this
     */
    public function setDateOrder($dir = 'DESC')
    {
        $this->setOrder('main_table.question_created_at', $dir);
        return $this;
    }

    /**
     * Set product filter
     *
     * @return $this
     */
    public function setProductName()
    {
        $this->getSelect()->joinLeft(
            ['product' => $this->getTable('catalog_product_entity_varchar')],
            'main_table.product_id = product.entity_id',
            ['product.value as product_name']
        )->where(
            'product.attribute_id = ?',
            73
        );

        return $this;
    }
}
