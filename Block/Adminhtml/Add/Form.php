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

namespace Ecomteck\ProductQuestions\Block\Adminhtml\Add;

/**
 * Adminhtml add product question form
 *
 * @author   Magento Ecomteck Team <ecomteck@gmail.com>
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Question statuses
     *
     * @var \Ecomteck\ProductQuestions\Model\Status
     */
    protected $_questionStatus = null;

    /**
     * Question User types
     *
     * @var \Ecomteck\ProductQuestions\Model\UserType
     */
    protected $_questionUserType = null;

    /**
     * Question User types
     *
     * @var \Ecomteck\ProductQuestions\Model\Visibility
     */
    protected $_questionVisibility = null;

    /**
     * Core system store model
     *
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Ecomteck\ProductQuestions\Model\Status $questionStatus,
     * @param \Ecomteck\ProductQuestions\Model\UserType $questionUserType
     * @param \Ecomteck\ProductQuestions\Model\Visibility $questionVisibility
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Ecomteck\ProductQuestions\Model\Status $questionStatus,
        \Ecomteck\ProductQuestions\Model\UserType $questionUserType,
        \Ecomteck\ProductQuestions\Model\Visibility $questionVisibility,
        array $data = []
    ) {
        $this->_questionStatus = $questionStatus;
        $this->_questionUserType = $questionUserType;
        $this->_questionVisibility = $questionVisibility;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare add product question form
     *
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $fieldset = $form->addFieldset('add_product_questions_form', ['legend' => __('Question Details')]);

        $fieldset->addField('product_name', 'note', ['label' => __('Product'), 'text' => 'product_name']);

        $fieldset->addField(
            'question_status_id',
            'select',
            [
                'label' => __('Status'),
                'required' => true,
                'name' => 'question_status_id',
                'values' => $this->_questionStatus->getOptionValues()
            ]
        );

        /**
         * Check is single store mode
         */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'question_store_id',
                'select',
                [
                    'label' => __('Store'),
                    'required' => true,
                    'name' => 'question_store_id',
                    'values' => $this->_systemStore->getStoreValuesForForm()
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                \Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element::class
            );
            $field->setRenderer($renderer);
        }

        $fieldset->addField(
            'question_user_type_id',
            'select',
            [
                'label' => __('User type'),
                'required' => true,
                'name' => 'question_user_type_id',
                'values' => $this->_questionUserType->getOptionValues()
            ]
        );

        $fieldset->addField(
            'question_visibility_id',
            'select',
            [
                'label' => __('Visibility'),
                'required' => true,
                'name' => 'question_visibility_id',
                'values' => $this->_questionVisibility->getOptionValues()
            ]
        );

        $fieldset->addField(
            'question_detail',
            'text',
            [
                'name' => 'question_detail',
                'title' => __('Question'),
                'label' => __('Question'),
                'maxlength' => '150',
                'required' => true
            ]
        );

        $fieldset->addField(
            'question_author_name',
            'text',
            [
                'name' => 'question_author_name',
                'title' => __('Author name'),
                'label' => __('Author name'),
                'maxlength' => '30',
                'required' => true
            ]
        );

        $fieldset->addField(
            'question_author_email',
            'text',
            [
                'name' => 'question_author_email',
                'title' => __('Author email'),
                'label' => __('Author email'),
                'maxlength' => '30',
                'required' => true
            ]
        );

        $fieldset->addField(
            'question_likes',
            'text',
            [
                'name' => 'question_likes',
                'title' => __('Likes'),
                'label' => __('Likes'),
                'maxlength' => '10',
                'required' => false
            ]
        );

        $fieldset->addField(
            'question_dislikes',
            'text',
            [
                'name' => 'question_dislikes',
                'title' => __('Dislikes'),
                'label' => __('Dislikes'),
                'maxlength' => '10',
                'required' => false
            ]
        );

        $fieldset->addField('product_id', 'hidden', ['name' => 'entity_pk_value']);

        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('edit_form');
        $form->setAction($this->getUrl('product_questions/question/post'));

        $this->setForm($form);
    }
}
