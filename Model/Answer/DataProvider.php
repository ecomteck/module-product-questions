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

namespace Ecomteck\ProductQuestions\Model\Answer;

/**
 * Class DataProvider
 * @package Ecomteck\ProductQuestions\Model\Answer
 */
class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    /**
     * @var \Ecomteck\ProductQuestions\Model\ResourceModel\Answer\Collection
     */
    protected $collection;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \Ecomteck\ProductQuestions\Model\ResourceModel\Answer\CollectionFactory $answerCollectionFactory
     * @param array $meta
     * @param array $data
     * @param \Magento\Ui\DataProvider\Modifier\PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Ecomteck\ProductQuestions\Model\ResourceModel\Answer\CollectionFactory $answerCollectionFactory,
        array $meta = [],
        array $data = [],
        \Magento\Ui\DataProvider\Modifier\PoolInterface $pool = null
    ) {
        $this->collection = $answerCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var $answer \Ecomteck\ProductQuestions\Model\Answer */
        foreach ($items as $answer) {
            $this->loadedData[$answer->getId()] = $answer->getData();
        }

        if (!empty($data)) {
            $answer = $this->collection->getNewEmptyItem();
            $answer->setData($data);
            $this->loadedData[$answer->getId()] = $answer->getData();
        }

        return $this->loadedData;
    }
}