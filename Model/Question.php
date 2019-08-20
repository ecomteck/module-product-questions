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
 * Class Question
 * @package Ecomteck\ProductQuestions\Model
 */
class Question extends \Magento\Framework\Model\AbstractModel implements \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
{
    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'ecomteck_product_questions';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Ecomteck\ProductQuestions\Model\ResourceModel\Question::class);
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::QUESTION_ID);
    }

    /**
     * Get question detail
     *
     * @return string
     */
    public function getQuestionDetail()
    {
        return $this->getData(self::QUESTION_DETAIL);
    }

    /**
     * Get question author name
     *
     * @return string
     */
    public function getQuestionAuthorName()
    {
        return $this->getData(self::QUESTION_AUTHOR_NAME);
    }

    /**
     * Get question author email
     *
     * @return string
     */
    public function getQuestionAuthorEmail()
    {
        return $this->getData(self::QUESTION_AUTHOR_EMAIL);
    }

    /**
     * Get question status id
     *
     * @return int
     */
    public function getQuestionStatusId()
    {
        return $this->getData(self::QUESTION_STATUS_ID);
    }

    /**
     * Get question user type id
     *
     * @return int
     */
    public function getQuestionUserTypeId()
    {
        return $this->getData(self::QUESTION_USER_TYPE_ID);
    }

    /**
     * Get customer id
     *
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Get question visibility id
     *
     * @return int
     */
    public function getQuestionVisibilityId()
    {
        return $this->getData(self::QUESTION_VISIBILITY_ID);
    }

    /**
     * Get question store id
     *
     * @return int
     */
    public function getQuestionStoreId()
    {
        return $this->getData(self::QUESTION_STORE_ID);
    }

    /**
     * Get question likes
     *
     * @return int
     */
    public function getQuestionLikes()
    {
        return $this->getData(self::QUESTION_LIKES);
    }

    /**
     * Get question dislikes
     *
     * @return int
     */
    public function getQuestionDislikes()
    {
        return $this->getData(self::QUESTION_DISLIKES);
    }

    /**
     * Get total answers
     *
     * @return int
     */
    public function getTotalAnswers()
    {
        return $this->getData(self::TOTAL_ANSWERS);
    }

    /**
     * Get pending answers
     *
     * @return int
     */
    public function getPendingAnswers()
    {
        return $this->getData(self::PENDING_ANSWERS);
    }

    /**
     * Get product id
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * Get question created by
     *
     * @return int
     */
    public function getQuestionCreatedBy()
    {
        return $this->getData(self::QUESTION_CREATED_BY);
    }

    /**
     * Get question created at
     *
     * @return string
     */
    public function getQuestionCreatedAt()
    {
        return $this->getData(self::QUESTION_CREATED_AT);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setId($id)
    {
        return $this->setData(self::QUESTION_ID, $id);
    }

    /**
     * Set question detail
     *
     * @param string $questionDetail
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setQuestionDetail($questionDetail)
    {
        return $this->setData(self::QUESTION_DETAIL, $questionDetail);
    }

    /**
     * Set question author name
     *
     * @param string $questionAuthorName
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setQuestionAuthorName($questionAuthorName)
    {
        return $this->setData(self::QUESTION_AUTHOR_NAME, $questionAuthorName);
    }

    /**
     * Set question author email
     *
     * @param string $questionAuthorEmail
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setQuestionAuthorEmail($questionAuthorEmail)
    {
        return $this->setData(self::QUESTION_AUTHOR_EMAIL, $questionAuthorEmail);
    }

    /**
     * Set question status id
     *
     * @param int $questionStatusId
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setQuestionStatusId($questionStatusId)
    {
        return $this->setData(self::QUESTION_STATUS_ID, $questionStatusId);
    }

    /**
     * Set question user type id
     *
     * @param int $questionUserTypeId
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setQuestionUserTypeId($questionUserTypeId)
    {
        return $this->setData(self::QUESTION_USER_TYPE_ID, $questionUserTypeId);
    }

    /**
     * Set customer id
     *
     * @param int|null $customerId
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Set question visibility id
     *
     * @param int $questionVisibilityId
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setQuestionVisibilityId($questionVisibilityId)
    {
        return $this->setData(self::QUESTION_VISIBILITY_ID, $questionVisibilityId);
    }

    /**
     * Set question store id
     *
     * @param int $questionStoreId
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setQuestionStoreId($questionStoreId)
    {
        return $this->setData(self::QUESTION_STORE_ID, $questionStoreId);
    }

    /**
     * Set question likes
     *
     * @param int $questionLikes
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setQuestionLikes($questionLikes)
    {
        return $this->setData(self::QUESTION_LIKES, $questionLikes);
    }

    /**
     * Set question dislike
     *
     * @param int $questionDislikes
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setQuestionDislikes($questionDislikes)
    {
        return $this->setData(self::QUESTION_DISLIKES, $questionDislikes);
    }

    /**
     * Set total answers
     *
     * @param int $totalAnswers
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setTotalAnswers($totalAnswers)
    {
        return $this->setData(self::TOTAL_ANSWERS, $totalAnswers);
    }

    /**
     * Set pending answers
     *
     * @param int $pendingAnswers
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setPendingAnswers($pendingAnswers)
    {
        return $this->setData(self::PENDING_ANSWERS, $pendingAnswers);
    }

    /**
     * Set product id
     *
     * @param int $productId
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Set question created by
     *
     * @param int $questionCreatedBy
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setQuestionCreatedBy($questionCreatedBy)
    {
        return $this->setData(self::QUESTION_CREATED_BY, $questionCreatedBy);
    }

    /**
     * Set question created at
     *
     * @param string $questionCreatedAt
     * @return \Ecomteck\ProductQuestions\Api\Data\QuestionInterface
     */
    public function setQuestionCreatedAt($questionCreatedAt)
    {
        return $this->setData(self::QUESTION_CREATED_AT, $questionCreatedAt);
    }
}
