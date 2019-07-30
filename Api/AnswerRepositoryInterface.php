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
 * Interface AnswerRepositoryInterface
 * @package Ecomteck\ProductQuestions\Api
 */
interface AnswerRepositoryInterface
{
    /**
     * Save Answer.
     *
     * @param \Ecomteck\ProductQuestions\Api\Data\AnswerInterface $answer
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Ecomteck\ProductQuestions\Api\Data\AnswerInterface $answer);

    /**
     * Retrieve answer.
     *
     * @param int $answerId
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($answerId);

    /**
     * Retrieve answers matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete answer.
     *
     * @param \Ecomteck\ProductQuestions\Api\Data\AnswerInterface $answer
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Ecomteck\ProductQuestions\Api\Data\AnswerInterface $answer);

    /**
     * Delete answer by ID.
     *
     * @param int $answerId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($answerId);
}
