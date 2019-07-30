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

namespace Ecomteck\ProductQuestions\Controller\Adminhtml\Answer;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 * @package Ecomteck\ProductQuestions\Controller\Adminhtml\Answer
 */
class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Ecomteck_ProductQuestions::answer_save';

    /**
     * @var \Ecomteck\ProductQuestions\Model\AnswerFactory
     */
    private $answerFactory;

    /**
     * @var \Ecomteck\ProductQuestions\Api\AnswerRepositoryInterface
     */
    private $answerRepository;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param \Ecomteck\ProductQuestions\Model\AnswerFactory|null $answerFactory
     * @param \Ecomteck\ProductQuestions\Api\QuestionRepositoryInterface|null $answerRepository
     */
    public function __construct(
        Action\Context $context,
        \Ecomteck\ProductQuestions\Model\AnswerFactory $answerFactory = null,
        \Ecomteck\ProductQuestions\Api\QuestionRepositoryInterface $answerRepository = null
    ) {
        $this->answerFactory = $answerFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Ecomteck\ProductQuestions\Model\AnswerFactory::class);
        $this->answerRepository = $answerRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Ecomteck\ProductQuestions\Api\AnswerRepositoryInterface::class);
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /** @var \Ecomteck\ProductQuestions\Model\Question $model */
            $model = $this->answerFactory->create();

            $id = $this->getRequest()->getParam('answer_id');
            if ($id) {
                try {
                    $model = $this->answerRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This answer no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                unset($data['answer_id']);
            }
            $model->setData($data);

            $this->_eventManager->dispatch(
                'ecomteck_product_answers_prepare_save',
                ['answer' => $model, 'request' => $this->getRequest()]
            );

            try {
                $this->answerRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the answer.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the answer.'));
            }
            return $resultRedirect->setPath('*/*/edit', ['answer_id' => $this->getRequest()->getParam('answer_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
