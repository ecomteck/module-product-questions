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

use Magento\Framework\Exception\NoSuchEntityException;

class LikeDislike extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        return parent::__construct($context);
    }

    /**
     * Like and Dislike action
     *
     * @return \Magento\Framework\Controller\Result\JsonFactory|\Magento\Framework\Controller\Result\ForwardFactory
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $data = null;

        if ($this->getRequest()->isAjax()) {
            if ($this->getRequest()->getParam('onType')) {
                $data = $this->processUpdate(\Ecomteck\ProductQuestions\Model\Answer::class, \Ecomteck\ProductQuestions\Model\ResourceModel\Answer::class, 'answer_id');
            }
        }

        return $result->setData($data);
    }

    /**
     * Process updating for the both like and dislike of the question and answer.
     *
     * @param string $model
     * @param string $resourceModel
     * @param string $column
     * @return array
     */
    protected function processUpdate($model, $resourceModel, $column)
    {
        $model = $this->_objectManager->create($model);
        $model = $model->load((int) $this->getRequest()->getParam('commentID'));
        $result['error'] = true;
        $result['total_number'] = null;
        $result['message'] = __('You are the man');

        if ($model->getData($column)) {
            if ($this->getRequest()->getParam('type') == 'like') {
                $result['total_number'] = $this->_objectManager->create($resourceModel)->updateLikes($model->getData($column));
            }
            if ($this->getRequest()->getParam('type') == 'dislike') {
                $result['total_number'] = $this->_objectManager->create($resourceModel)->updateDislikes($model->getData($column));
            }
            $result['error'] = false;
        }

        return $result;
    }
}
