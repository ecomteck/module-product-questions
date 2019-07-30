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

namespace Ecomteck\ProductQuestions\Block\Customer;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Customer Questions list block
 */
class ListCustomer extends \Magento\Customer\Block\Account\Dashboard
{
    /**
     * Product questions collection
     *
     * @var \Ecomteck\ProductQuestions\Model\ResourceModel\Question\Collection
     */
    protected $collection;

    /**
     * Question resource model
     *
     * @var \Ecomteck\ProductQuestions\Model\ResourceModel\Question\CollectionFactory
     */
    protected $questionColFactory;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var \Ecomteck\ProductQuestions\Model\ResourceModel\Answer\CollectionFactory
     */
    protected $answerColFactory;

    /**
     * @var \Ecomteck\ProductQuestions\Model\Config\Source\FormatDateTime
     */
    protected $formatDateTime;

    /**
     * @var \Ecomteck\ProductQuestions\Model\UserType
     */
    protected $userType;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $dataObjectFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param \Ecomteck\ProductQuestions\Model\ResourceModel\Question\CollectionFactory $collectionFactory
     * @param \Ecomteck\ProductQuestions\Model\ResourceModel\Answer\CollectionFactory $answerColFactory
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param \Ecomteck\ProductQuestions\Model\Config\Source\FormatDateTime $formatDateTime
     * @param \Ecomteck\ProductQuestions\Model\UserType $userType
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        \Ecomteck\ProductQuestions\Model\ResourceModel\Question\CollectionFactory $collectionFactory,
        \Ecomteck\ProductQuestions\Model\ResourceModel\Answer\CollectionFactory $answerColFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Ecomteck\ProductQuestions\Model\Config\Source\FormatDateTime $formatDateTime,
        \Ecomteck\ProductQuestions\Model\UserType $userType,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        array $data = []
    ) {
        $this->questionColFactory = $collectionFactory;
        $this->answerColFactory = $answerColFactory;
        $this->formatDateTime = $formatDateTime;
        $this->userType = $userType;
        $this->dataObjectFactory = $dataObjectFactory;
        parent::__construct(
            $context,
            $customerSession,
            $subscriberFactory,
            $customerRepository,
            $customerAccountManagement,
            $data
        );
        $this->currentCustomer = $currentCustomer;
    }

    /**
     * Get html code for toolbar
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Initializes toolbar
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        if ($this->getQuestions()) {
            $toolbar = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'customer_review_list.toolbar'
            )->setCollection(
                $this->getQuestions()
            );

            $this->setChild('toolbar', $toolbar);
        }
        return parent::_prepareLayout();
    }

    /**
     * Get reviews
     *
     * @return bool|\Ecomteck\ProductQuestions\Model\ResourceModel\Question\Collection
     */
    public function getQuestions()
    {
        if (!($customerId = $this->currentCustomer->getCustomerId())) {
            return false;
        }
        if (!$this->collection) {
            $this->collection = $this->questionColFactory->create();
            $this->collection
                ->addStoreFilter($this->_storeManager->getStore()->getId())
                ->addCustomerFilter($customerId)
                ->setProductName()
                ->setDateOrder();
        }
        return $this->collection;
    }

    /**
     * Get product link
     *
     * @param int $productId
     * @return string
     */
    public function getProductUrl($productId)
    {
        return $this->getUrl('catalog/product/view/', ['id' => $productId]);
    }

    /**
     * Format date in short format
     *
     * @param string $date
     * @return string
     */
    public function dateFormat($date)
    {
        return $this->formatDateTime->formatCreatedAt($date);
    }

    /**
     * Get list of answers of the question
     *
     * @param int $questionId
     * @return array
     */
    public function getAnswerList($questionId)
    {
        $collection = $this->answerColFactory->create()->addFieldToFilter(
            'main_table.question_id', (int) $questionId
        )->addStatusFilter(
            \Ecomteck\ProductQuestions\Model\Status::STATUS_APPROVED
        )->addVisibilityFilter(
            \Ecomteck\ProductQuestions\Model\Visibility::VISIBILITY_VISIBLE
        );

        $answers = [];
        foreach ($collection as $answer) {
            $answers[] = $this->getAnswerInfo($answer);
        }

        return $answers;
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
            ->setAnsweredBy($this->userType->getUserTypeText($answer->getAnswerUserTypeId()))
            ->setCreatedAt($this->formatDateTime->formatCreatedAt($answer->getAnswerCreatedAt()))
            ->getData();
    }

    /**
     * Get URL for likes and dislikes
     *
     * @return string
     */
    public function getLikeDislikeUrl()
    {
        return $this->getUrl(
            'question/product/likeDislike',
            [
                '_secure' => $this->getRequest()->isSecure()
            ]
        );
    }
}
