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

namespace Ecomteck\ProductQuestions\Block\Product\View;

class ListView extends \Magento\Framework\View\Element\Template
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $dataObjectFactory;

    /**
     * @var \Ecomteck\ProductQuestions\Model\UserType
     */
    protected $userType;

    /**
     * @var \Ecomteck\ProductQuestions\Model\Config\Source\FormatDateTime
     */
    protected $formatDateTime;

    /**
     * @var \Ecomteck\ProductQuestions\Model\ResourceModel\Answer\CollectionFactory
     */
    protected $answerColFactory;

    /**
     * @var \Ecomteck\ProductQuestions\Helper\Data
     */
    protected $questionData;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     * @param \Ecomteck\ProductQuestions\Model\UserType $userType
     * @param \Ecomteck\ProductQuestions\Model\Config\Source\FormatDateTime $formatDateTime
     * @param \Ecomteck\ProductQuestions\Model\ResourceModel\Answer\CollectionFactory $answerColFactory
     * @param \Ecomteck\ProductQuestions\Helper\Data $questionData
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Ecomteck\ProductQuestions\Model\UserType $userType,
        \Ecomteck\ProductQuestions\Model\Config\Source\FormatDateTime $formatDateTime,
        \Ecomteck\ProductQuestions\Model\ResourceModel\Answer\CollectionFactory $answerColFactory,
        \Ecomteck\ProductQuestions\Helper\Data $questionData,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->userType = $userType;
        $this->formatDateTime = $formatDateTime;
        $this->answerColFactory = $answerColFactory;
        $this->questionData = $questionData;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve the question list asked for the product
     *
     * @param array $data
     */
    public function getQuestionProductList()
    {
        $questions = $this->coreRegistry->registry('ecomteck_question_product');
        $data = [];
        if (!empty($questions)) {
            $data['total_page'] = $questions->getLastPageNumber();
            $data['current_page'] = $questions->getCurPage();
            $data['allow_to_reply'] = $this->questionData->getAllowToReply();
            $data['data'] = null;
            $data['next_url'] = $this->getUrl(
                'question/product/listAjax',
                [
                    '_secure' => $this->getRequest()->isSecure(),
                    'id' => $this->getRequest()->getParam('id'),
                    'page' => $data['current_page'] + 1
                ]
            );
            foreach ($questions as $question) {
                $data['data'][] = $this->getQuestionInfo($question);
            }
        }
        return $data;
    }

    /**
     * Retrieve the question information
     *
     * @param \Ecomteck\ProductQuestions\Model\ResourceModel\Question\CollectionFactory $question
     * @return object
     */
    public function getQuestionInfo($question)
    {
        /** @var \Magento\Framework\DataObjectFactory $dataObjectFactory */
        $questionData = $this->dataObjectFactory->create()
            ->setId($question->getQuestionId())
            ->setProductId($question->getEntityPkValue())
            ->setTitle(nl2br($question->getQuestionDetail()))
            ->setAuthorName(ucwords(strtolower($question->getQuestionAuthorName())))
            ->setFirstCharacter(substr($question->getQuestionAuthorName(), 0, 1))
            ->setLikes($question->getQuestionLikes())
            ->setDislikes($question->getQuestionDislikes())
            ->setAskedBy($this->getAddedBy($question->getQuestionUserTypeId()))
            ->setCreatedAt($this->formatDateTime->formatCreatedAt($question->getQuestionCreatedAt()));
        $answers = [];
        foreach ($this->getAnswerList($question) as $answer) {
            $answers[] = $this->getAnswerInfo($answer);
        }
        $questionData->setAnswers($answers);
        return $questionData->getData();
    }

    /**
     * Retrieve the added by
     *
     * @param int $userTypeId
     * @return string
     */
    protected function getAddedBy($userTypeId)
    {
        return $this->userType->getUserTypeText($userTypeId);
    }

    /**
     * Retrieve the answer information
     *
     * @param \Ecomteck\ProductQuestions\Model\ResourceModel\Answer\CollectionFactory $answer
     * @return array
     */
    protected function getAnswerInfo($answer)
    {
        /** @var \Magento\Framework\DataObjectFactory $dataObjectFactory */
        return $this->dataObjectFactory->create()
            ->setId($answer->getAnswerId())
            ->setContent(nl2br($answer->getAnswerDetail()))
            ->setAuthorName(ucwords(strtolower($answer->getAnswerAuthorName())))
            ->setFirstCharacter(substr($answer->getAnswerAuthorName(), 0, 1))
            ->setLikes($answer->getAnswerLikes())
            ->setDislikes($answer->getAnswerDislikes())
            ->setAnsweredBy($this->getAddedBy($answer->getAnswerUserTypeId()))
            ->setCreatedAt($this->formatDateTime->formatCreatedAt($answer->getAnswerCreatedAt()))
            ->getData();
    }

    /**
     * Get list of answers of the question
     *
     * @param \Ecomteck\ProductQuestions\Model\ResourceModel\Question\CollectionFactory $question
     * @return \Ecomteck\ProductQuestions\Model\ResourceModel\Answer\CollectionFactory
     */
    public function getAnswerList($question)
    {
        $collection = $this->answerColFactory->create()->addFieldToFilter(
            'main_table.question_id', $question->getQuestionId()
        )->addStatusFilter(
            \Ecomteck\ProductQuestions\Model\Status::STATUS_APPROVED
        )->addVisibilityFilter(
            \Ecomteck\ProductQuestions\Model\Visibility::VISIBILITY_VISIBLE
        );

        return $collection;
    }
}
