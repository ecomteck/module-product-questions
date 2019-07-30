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

namespace Ecomteck\ProductQuestions\Api;

/**
 * Interface QuestionRepositoryInterface
 * @package Ecomteck\ProductQuestions\Api
 */
interface QuestionRepositoryInterface
{
    /**
     * Save Question.
     *
     * @param \Ecomteck\ProductQuestions\Api\Data\QuestionInterface $question
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Ecomteck\ProductQuestions\Api\Data\QuestionInterface $question);

    /**
     * Retrieve question.
     *
     * @param int $questionId
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($questionId);

    /**
     * Retrieve questions matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete question.
     *
     * @param \Ecomteck\ProductQuestions\Api\Data\QuestionInterface $question
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Ecomteck\ProductQuestions\Api\Data\QuestionInterface $question);

    /**
     * Delete question by ID.
     *
     * @param int $questionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($questionId);
}