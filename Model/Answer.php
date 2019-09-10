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

use Magento\Framework\Model\AbstractModel;
use Ecomteck\ProductQuestions\Api\Data\AnswerInterface;

/**
 * Class Answer
 * @package Ecomteck\ProductQuestions\Model
 */
class Answer extends AbstractModel implements AnswerInterface
{
    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'ecomteck_product_answer';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Ecomteck\ProductQuestions\Model\ResourceModel\Answer::class);
    }

    /**
     * Update the answer(s) visibility
     *
     * @param array $answerIds
     * @param int $visibility
     * @return void
     */
    public function massUpdateVisibility($answerIds, $visibility)
    {
        foreach ($answerIds as $answerId) {
            $this->load((int) $answerId)->setAnswerVisibilityId((int) $visibility)->save();
        }
    }

    /**
     * Update the answer(s) status
     *
     * @param array $answerIds
     * @param int $status
     * @return void
     */
    public function massUpdateStatus($answerIds, $status)
    {
        foreach ($answerIds as $answerId) {
            $this->load((int) $answerId)->setAnswerStatusId((int) $status)->save();
        }
    }

    /**
     * Delete the answer(s)
     *
     * @param array $answerIds
     * @return void
     */
    public function massDelete($answerIds)
    {
        foreach ($answerIds as $answerId) {
            $this->load((int) $answerId)->delete();
        }
    }

    /**
     * Retrieve the administrator code
     *
     * @return int
     */
    public function getAdministratorCode()
    {
        return \Ecomteck\ProductQuestions\Model\UserType::USER_TYPE_ADMINISTRATOR;
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::ANSWER_ID);
    }

    /**
     * Get answer detail
     *
     * @return string
     */
    public function getAnswerDetail()
    {
        return $this->getData(self::ANSWER_DETAIL);
    }

    /**
     * Get answer author name
     *
     * @return string
     */
    public function getAnswerAuthorName()
    {
        return $this->getData(self::ANSWER_AUTHOR_NAME);
    }

    /**
     * Get answer author email
     *
     * @return string
     */
    public function getAnswerAuthorEmail()
    {
        return $this->getData(self::ANSWER_AUTHOR_EMAIL);
    }

    /**
     * Get question id
     *
     * @return int
     */
    public function getQuestionId()
    {
        return $this->getData(self::QUESTION_ID);
    }

    /**
     * Get answer status id
     *
     * @return int
     */
    public function getAnswerStatusId()
    {
        return $this->getData(self::ANSWER_STATUS_ID);
    }

    /**
     * Get answer user type id
     *
     * @return int
     */
    public function getAnswerUserTypeId()
    {
        return $this->getData(self::ANSWER_USER_TYPE_ID);
    }

    /**
     * Get answer user id
     *
     * @return int
     */
    public function getAnswerUserId()
    {
        return $this->getData(self::ANSWER_USER_ID);
    }

    /**
     * Get answer created by
     *
     * @return int
     */
    public function getAnswerCreatedBy()
    {
        return $this->getData(self::ANSWER_CREATED_BY);
    }

    /**
     * Get answer visibility id
     *
     * @return int
     */
    public function getAnswerVisibilityId()
    {
        return $this->getData(self::ANSWER_VISIBILITY_ID);
    }

    /**
     * Get answer likes
     *
     * @return int
     */
    public function getAnswerLikes()
    {
        return $this->getData(self::ANSWER_LIKES);
    }

    /**
     * Get answer dislikes
     *
     * @return int
     */
    public function getAnswerDislikes()
    {
        return $this->getData(self::ANSWER_DISLIKES);
    }

    /**
     * Get answer created at
     *
     * @return int
     */
    public function getAnswerCreatedAt()
    {
        return $this->getData(self::ANSWER_CREATED_AT);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ANSWER_ID, $id);
    }

    /**
     * Set answer detail
     *
     * @param string $answerDetail
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     */
    public function setAnswerDetail($answerDetail)
    {
        return $this->setData(self::ANSWER_DETAIL, $answerDetail);
    }

    /**
     * Set answer author name
     *
     * @param string $answerAuthorName
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     */
    public function setAnswerAuthorName($answerAuthorName)
    {
        return $this->setData(self::ANSWER_AUTHOR_NAME, $answerAuthorName);
    }

    /**
     * Set answer author email
     *
     * @param string $answerAuthorEmail
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     */
    public function setAnswerAuthorEmail($answerAuthorEmail)
    {
        return $this->setData(self::ANSWER_AUTHOR_EMAIL, $answerAuthorEmail);
    }

    /**
     * Set question Id
     *
     * @param int $questionId
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     */
    public function setQuestionId($questionId)
    {
        return $this->setData(self::QUESTION_ID, $questionId);
    }

    /**
     * Set answer status id
     *
     * @param int $answerStatusId
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     */
    public function setAnswerStatusId($answerStatusId)
    {
        return $this->setData(self::ANSWER_STATUS_ID, $answerStatusId);
    }

    /**
     * Set answer user type id
     *
     * @param int $answerUserTypeId
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     */
    public function setAnswerUserTypeId($answerUserTypeId)
    {
        return $this->setData(self::ANSWER_USER_TYPE_ID, $answerUserTypeId);
    }

    /**
     * Set answer user id
     *
     * @param int $answerUserId
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     */
    public function setAnswerUserId($answerUserId)
    {
        return $this->setData(self::ANSWER_USER_ID, $answerUserId);
    }

    /**
     * Set answer created by
     *
     * @param int $answerCreatedBy
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     */
    public function setAnswerCreatedBy($answerCreatedBy)
    {
        return $this->setData(self::ANSWER_CREATED_BY, $answerCreatedBy);
    }

    /**
     * Set answer visibility id
     *
     * @param int $answerVisibilityId
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     */
    public function setAnswerVisibilityId($answerVisibilityId)
    {
        return $this->setData(self::ANSWER_VISIBILITY_ID, $answerVisibilityId);
    }

    /**
     * Set answer likes
     *
     * @param int $answerLikes
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     */
    public function setAnswerLikes($answerLikes)
    {
        return $this->setData(self::ANSWER_LIKES, $answerLikes);
    }

    /**
     * Set answer dislikes
     *
     * @param int $answerDislikes
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     */
    public function setAnswerDislikes($answerDislikes)
    {
        return $this->setData(self::ANSWER_DISLIKES, $answerDislikes);
    }

    /**
     * Set answer created at
     *
     * @param int $answerCreatedAt
     * @return \Ecomteck\ProductQuestions\Api\Data\AnswerInterface
     */
    public function setAnswerCreatedAt($answerCreatedAt)
    {
        return $this->setData(self::ANSWER_CREATED_AT, $answerCreatedAt);
    }
}
