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

namespace Ecomteck\ProductQuestions\Block\Adminhtml\Edit;

/**
 * Class Question Edit Form
 * @package Ecomteck\ProductQuestions\Block\Adminhtml\Question\Edit
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Catalog product factory
     *
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * Core system store model
     *
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

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
     * Form Edit constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Ecomteck\ProductQuestions\Model\Status $questionStatus
     * @param \Ecomteck\ProductQuestions\Model\UserType $questionUserType
     * @param \Ecomteck\ProductQuestions\Model\Visibility $questionVisibility
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Ecomteck\ProductQuestions\Model\Status $questionStatus,
        \Ecomteck\ProductQuestions\Model\UserType $questionUserType,
        \Ecomteck\ProductQuestions\Model\Visibility $questionVisibility,
        array $data = []
    ) {
        $this->customerRepository = $customerRepository;
        $this->_productFactory = $productFactory;
        $this->_systemStore = $systemStore;
        $this->_questionStatus = $questionStatus;
        $this->_questionUserType = $questionUserType;
        $this->_questionVisibility = $questionVisibility;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare edit question form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $question = $this->_coreRegistry->registry('product_questions_data');
        $product = $this->_productFactory->create()->load($question->getEntityPkValue());

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl(
                        'product_questions/*/save',
                        [
                            'question_id' => $this->getRequest()->getParam('question_id')
                        ]
                    ),
                    'method' => 'post',
                ],
            ]
        );
        $fieldset = $form->addFieldset(
            'question_details',
            ['legend' => __('Question Details'), 'class' => 'fieldset-wide']
        );

        $fieldset->addField(
            'product_name',
            'note',
            [
                'label' => __('Product'),
                'text' => '<a href="' . $this->getUrl(
                        'catalog/product/edit',
                        ['id' => $product->getId()]
                    ) . '" onclick="this.target=\'blank\'">' . $this->escapeHtml(
                        $product->getName()
                    ) . '</a>'
            ]
        );

        try {
            $customer = $this->customerRepository->getById($question->getCustomerId());
            $customerText = __(
                '<a href="%1" onclick="this.target=\'blank\'">%2 %3</a> <a href="mailto:%4">(%4)</a>',
                $this->getUrl('customer/index/edit', ['id' => $customer->getId(), 'active_tab' => 'review']),
                $this->escapeHtml($customer->getFirstname()),
                $this->escapeHtml($customer->getLastname()),
                $this->escapeHtml($customer->getEmail())
            );
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $customerText = ($question->getQuestionStoreId() == \Magento\Store\Model\Store::DEFAULT_STORE_ID)
                ? __('Administrator') : __('Guest');
        }
        $fieldset->addField('customer', 'note', ['label' => __('Author'), 'text' => $customerText]);

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
            ['label' => __('Question Detail'), 'required' => true, 'name' => 'question_detail']
        );

        $fieldset->addField(
            'question_author_name',
            'text',
            ['label' => __('Author Name'), 'required' => true, 'name' => 'question_author_name']
        );

        $fieldset->addField(
            'question_author_email',
            'text',
            ['label' => __('Author Email'), 'required' => true, 'name' => 'question_author_email']
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

        $form->setUseContainer(true);
        $form->setValues($question->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}