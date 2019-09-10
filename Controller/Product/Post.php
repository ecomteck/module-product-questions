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

namespace Ecomteck\ProductQuestions\Controller\Product;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\TestFramework\Inspection\Exception;
use Ecomteck\ProductQuestions\Model\Status;
use Ecomteck\ProductQuestions\Model\Visibility;
use Ecomteck\ProductQuestions\Model\UserType;

/**
 * Class Post
 * @package Ecomteck\ProductQuestions\Controller\Product
 */
class Post extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $formKeyValidator;

    /**
     * Generic session
     *
     * @var \Magento\Framework\Session\Generic
     */
    protected $submitSession;

    /**
     * Catalog product model
     *
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Core model store manager interface
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * User Type Model
     *
     * @var UserType
     */
    protected $userType;

    /**
     * Question model
     *
     * @var \Ecomteck\ProductQuestions\Model\QuestionFactory
     */
    protected $questionFactory;

    /**
     * Answer model
     *
     * @var \Ecomteck\ProductQuestions\Model\AnswerFactory
     */
    protected $answerFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Ecomteck\ProductQuestions\Helper\Data
     */
    protected $questionData;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Framework\Session\Generic $submitSession
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Ecomteck\ProductQuestions\Model\QuestionFactory $questionFactory
     * @param \Ecomteck\ProductQuestions\Model\AnswerFactory $answerFactory
     * @param \Ecomteck\ProductQuestions\Helper\Data $questionData
     * @param UserType $userType
     * @param \Magento\Customer\Model\Session $customerSession
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Framework\Session\Generic $submitSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Ecomteck\ProductQuestions\Model\QuestionFactory $questionFactory,
        \Ecomteck\ProductQuestions\Model\AnswerFactory $answerFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Ecomteck\ProductQuestions\Helper\Data $questionData,
        UserType $userType
    ) {
        $this->formKeyValidator = $formKeyValidator;
        $this->submitSession = $submitSession;
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
        $this->questionFactory = $questionFactory;
        $this->answerFactory = $answerFactory;
        $this->userType = $userType;
        $this->customerSession = $customerSession;
        $this->questionData = $questionData;
        parent::__construct($context);
    }

    /**
     * Submit new question and answer action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addError(__('Invalid Form Key.'));
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }

        $data = $this->getRequest()->getPostValue();

        $data['id'] = $this->getRequest()->getParam('id');

        $type = $data['type'];
        $text = 'question';
        $messageSuccess = __('You submitted your %1 for moderation.', $text);

        if ($type == '1' && !$this->questionData->getAllowToWrite()) {
            $this->messageManager->addError(__('You are not allowed to write the question for this product.'));
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }

        if ($type == '2' && !$this->questionData->getAllowToReply()) {
            $this->messageManager->addError(__('You are not allowed to answer the question.'));
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }

        $userCode = $this->userType->getGuestCode();
        if ($this->customerSession->isLoggedIn()) {
            $data['customer_id'] = $this->customerSession->getCustomer()->getEntityId();
            $data['author_name'] = $this->customerSession->getCustomer()->getFirstname().' '.$this->customerSession->getCustomer()->getLastname();
            $data['author_email'] = $this->customerSession->getCustomer()->getEmail();
            $userCode = $this->userType->getCustomerCode();
        }

        switch ($type) {
            case '2':
                $data['answer_detail'] = $data['detail'];
                $data['answer_author_name'] = $data['author_name'];
                $data['answer_author_email'] = $data['author_email'];
                $data['answer_visibility_id'] = Visibility::VISIBILITY_VISIBLE;
                $data['question_id'] = $data['question_id'];
                $data['answer_created_by'] = $userCode;
                $data['answer_store_id'] = $this->storeManager->getStore()->getId();
                $data['store_ids'] = [$this->storeManager->getStore()->getId()];
                $data['answer_user_type_id'] = $userCode;
                $text = 'answer';
                $messageSuccess = __('You submitted your %1 for moderation.', $text);
                if ($this->questionData->getAutoApprovalNewAnswer()) {
                    $data['answer_status_id'] = \Ecomteck\ProductQuestions\Model\Status::STATUS_APPROVED;
                    $messageSuccess = __('You submitted your %1 successfully.', $text);
                }
                $model = $this->answerFactory->create();
                break;
            case '1':
                $data['question_detail'] = $data['detail'];
                $data['question_author_name'] = $data['author_name'];
                $data['question_author_email'] = $data['author_email'];
                $data['question_visibility_id'] = Visibility::VISIBILITY_VISIBLE;
                $data['product_id'] = $data['id'];
                $data['question_created_by'] = $userCode;
                $data['question_store_id'] = $this->storeManager->getStore()->getId();
                $data['store_ids'] = [$this->storeManager->getStore()->getId()];
                $data['question_user_type_id'] = $userCode;
                unset($data['question_id']);
                $model = $this->questionFactory->create();
                if ($this->questionData->getAutoApprovalNewQuestion()) {
                    $data['question_status_id'] = \Ecomteck\ProductQuestions\Model\Status::STATUS_APPROVED;
                    $messageSuccess = __('You submitted your %1 successfully.', $text);
                }
                break;
            default:
                $this->messageManager->addError(__('We can\'t post your %1 right now.', $text));
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
                break;
        }

        try {
            $product = $this->productRepository->getById(
                (int) $data['id'],
                false,
                $this->storeManager->getStore()->getId()
            );
            $model->setData($data)->save();
            $this->messageManager->addSuccess($messageSuccess);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('We can\'t post your %1 right now.', $text));
        }

        $redirectUrl = $this->submitSession->getRedirectUrl(true);
        $resultRedirect->setUrl($redirectUrl ?: $this->_redirect->getRedirectUrl());
        return $resultRedirect;
    }
}
