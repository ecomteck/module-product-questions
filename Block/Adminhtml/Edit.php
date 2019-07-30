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
namespace Ecomteck\ProductQuestions\Block\Adminhtml;

/**
 * Class Question Edit Form
 * @package Ecomteck\ProductQuestions\Block\Adminhtml\Question
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Review action pager
     *
     * @var \Ecomteck\ProductQuestions\Helper\Action\Pager
     */
    protected $_questionActionPager = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Review model factory
     *
     * @var \Ecomteck\ProductQuestions\Model\QuestionFactory
     */
    protected $_questionFactory;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Ecomteck\ProductQuestions\Helper\Action\Pager $questionActionPager,
        \Ecomteck\ProductQuestions\Model\QuestionFactory $questionFactory,
        array $data = [])
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_questionActionPager = $questionActionPager;
        $this->_questionFactory = $questionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Initialize edit question
     *
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_objectId    = 'question_id';
        $this->_blockGroup  = 'Ecomteck_ProductQuestions';
        $this->_controller  = 'adminhtml';

        /** @var $actionPager \Ecomteck\ProductQuestions\Helper\Action\Pager */
        $actionPager = $this->_questionActionPager;
        $actionPager->setStorageId('product_questions');

        $questionId = $this->getRequest()->getParam('question_id');
        $prevId = $actionPager->getPreviousItemId($questionId);
        $nextId = $actionPager->getNextItemId($questionId);

        if ($prevId !== false) {
            $this->addButton(
                'previous',
                [
                    'label' => __('Previous'),
                    'onclick' => 'setLocation(\'' . $this->getUrl('product_questions/*/*', ['question_id' => $prevId]) . '\')'
                ],
                3,
                10
            );

            $this->addButton(
                'save_and_previous',
                [
                    'label' => __('Save and Previous'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => [
                                'event' => 'save',
                                'target' => '#edit_form',
                                'eventData' => ['action' => ['args' => ['next_item' => $prevId]]],
                            ],
                        ],
                    ]
                ],
                3,
                11
            );
        }
        if ($nextId !== false) {
            $this->addButton(
                'save_and_next',
                [
                    'label' => __('Save and Next'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => [
                                'event' => 'save',
                                'target' => '#edit_form',
                                'eventData' => ['action' => ['args' => ['next_item' => $nextId]]],
                            ],
                        ],
                    ]
                ],
                3,
                100
            );

            $this->addButton(
                'next',
                [
                    'label' => __('Next'),
                    'onclick' => 'setLocation(\'' . $this->getUrl('product_questions/*/*', ['question_id' => $nextId]) . '\')'
                ],
                3,
                105
            );
        }
        $this->buttonList->update('save', 'label', __('Save Question'));
        $this->buttonList->update('save', 'question_id', 'save_button');
        $this->buttonList->update('delete', 'label', __('Delete Question'));

        if ($this->getRequest()->getParam('productId', false)) {
            $this->buttonList->update(
                'back',
                'onclick',
                'setLocation(\'' . $this->getUrl(
                    'catalog/product/edit',
                    ['id' => $this->getRequest()->getParam('productId', false)]
                ) . '\')'
            );
        }

        if ($this->getRequest()->getParam('customerId', false)) {
            $this->buttonList->update(
                'back',
                'onclick',
                'setLocation(\'' . $this->getUrl(
                    'customer/index/edit',
                    ['id' => $this->getRequest()->getParam('customerId', false)]
                ) . '\')'
            );
        }


        $this->buttonList->update(
            'delete',
            'onclick',
            'deleteConfirm(' . '\'' . __(
                'Are you sure you want to do this?'
            ) . '\', ' . '\'' . $this->getUrl(
                '*/*/delete',
                [$this->_objectId => $this->getRequest()->getParam($this->_objectId), 'ret' => 'pending']
            ) . '\'' . ')'
        );

        if ($this->getRequest()->getParam($this->_objectId)) {
            $questionData = $this->_questionFactory->create()->load($this->getRequest()->getParam($this->_objectId));
            $this->_coreRegistry->register('product_questions_data', $questionData);
        }
    }

    /**
     * Get edit question header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $questionData = $this->_coreRegistry->registry('product_questions_data');
        if ($questionData && $questionData->getId()) {
            return __("Edit Review '%1'");
        } else {
            return __('New Question');
        }
    }
}