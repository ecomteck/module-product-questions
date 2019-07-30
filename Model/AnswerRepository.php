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

use Ecomteck\ProductQuestions\Api\Data;
use Ecomteck\ProductQuestions\Api\AnswerRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Ecomteck\ProductQuestions\Model\ResourceModel\Answer as ResourceAnswer;
use Ecomteck\ProductQuestions\Model\ResourceModel\Answer\CollectionFactory as AnswerCollectionFactory;

/**
 * Class AnswerRepository
 * @package Ecomteck\ProductQuestions\Model
 */
class AnswerRepository implements AnswerRepositoryInterface
{
    /**
     * @var ResourceAnswer
     */
    protected $resource;

    /**
     * @var AnswerFactory
     */
    protected $answerFactory;

    /**
     * @var AnswerCollectionFactory
     */
    protected $answerCollectionFactory;

    /**
     * @var Data\AnswerSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \Ecomteck\ProductQuestions\Api\Data\AnswerInterfaceFactory
     */
    protected $dataAnswerFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * AnswerRepository constructor.
     * @param ResourceAnswer $resource
     * @param AnswerFactory $answerFactory
     * @param Data\AnswerInterfaceFactory $dataAnswerFactory
     * @param AnswerCollectionFactory $answerCollectionFactory
     * @param Data\AnswerSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param CollectionProcessorInterface|null $collectionProcessor
     */
    public function __construct(
        ResourceAnswer $resource,
        AnswerFactory $answerFactory,
        Data\AnswerInterfaceFactory $dataAnswerFactory,
        AnswerCollectionFactory $answerCollectionFactory,
        Data\AnswerSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->resource = $resource;
        $this->answerFactory = $answerFactory;
        $this->answerCollectionFactory = $answerCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataAnswerFactory = $dataAnswerFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    /**
     * Save Answer data
     *
     * @param \Ecomteck\ProductQuestions\Api\Data\AnswerInterface $answer
     * @return Answer
     * @throws CouldNotSaveException
     */
    public function save(\Ecomteck\ProductQuestions\Api\Data\AnswerInterface $answer)
    {
        try {
            $this->resource->save($answer);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the answer: %1', $exception->getMessage()),
                $exception
            );
        }
        return $answer;
    }

    /**
     * Load Answer data by given Answer Identity
     *
     * @param string $answerId
     * @return Answer
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($answerId)
    {
        $answer = $this->answerFactory->create();
        $answer->load($answerId);
        if (!$answer->getId()) {
            throw new NoSuchEntityException(__('The answer with the "%1" ID doesn\'t exist.', $answerId));
        }
        return $answer;
    }

    /**
     * Load Answer data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \Ecomteck\ProductQuestions\Model\ResourceModel\Answer\Collection $collection */
        $collection = $this->answerCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var Data\AnswerSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete Answer
     *
     * @param \Ecomteck\ProductQuestions\Api\Data\AnswerInterface $answer
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\Ecomteck\ProductQuestions\Api\Data\AnswerInterface $answer)
    {
        try {
            $this->resource->delete($answer);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the answer: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * Delete Answer by given Answer Identity
     *
     * @param string $answerId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($answerId)
    {
        return $this->delete($this->getById($answerId));
    }

    /**
     * Retrieve collection processor
     *
     * @deprecated 102.0.0
     * @return CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Ecomteck\ProductQuestions\Model\Api\SearchCriteria\AnswerCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }
}