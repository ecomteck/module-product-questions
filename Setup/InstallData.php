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

namespace Ecomteck\ProductQuestions\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $pageFactory;

    /**
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     */
    public function __construct(
        \Magento\Cms\Model\PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        //Fill table ecomteck_product_questions_status
        $questionAnswerStatuses = [
            \Ecomteck\ProductQuestions\Model\Status::STATUS_APPROVED => 'Approved',
            \Ecomteck\ProductQuestions\Model\Status::STATUS_PENDING => 'Pending',
            \Ecomteck\ProductQuestions\Model\Status::STATUS_NOT_APPROVED => 'Not Approved'
        ];
        foreach ($questionAnswerStatuses as $k => $v) {
            $bind = ['status_id' => $k, 'status_code' => $v];
            $installer->getConnection()->insertOnDuplicate($installer->getTable('ecomteck_product_questions_status'), $bind);
        }

        //Fill table ecomteck_product_questions_visibility
        $questionAnswerStatuses = [
            \Ecomteck\ProductQuestions\Model\Visibility::VISIBILITY_NOT_VISIBLE => 'Not visible',
            \Ecomteck\ProductQuestions\Model\Visibility::VISIBILITY_VISIBLE => 'Visible'
        ];
        foreach ($questionAnswerStatuses as $k => $v) {
            $bind = ['visibility_id' => $k, 'visibility_code' => $v];
            $installer->getConnection()->insertOnDuplicate($installer->getTable('ecomteck_product_questions_visibility'), $bind);
        }

        //Fill table ecomteck_product_questions_user_type
        $questionAnswerStatuses = [
            \Ecomteck\ProductQuestions\Model\UserType::USER_TYPE_GUEST => 'Guest',
            \Ecomteck\ProductQuestions\Model\UserType::USER_TYPE_CUSTOMER => 'Customer',
            \Ecomteck\ProductQuestions\Model\UserType::USER_TYPE_ADMINISTRATOR => 'Administrator'
        ];
        foreach ($questionAnswerStatuses as $k => $v) {
            $bind = ['user_type_id' => $k, 'user_type_code' => $v];
            $installer->getConnection()->insertOnDuplicate($installer->getTable('ecomteck_product_questions_user_type'), $bind);
        }

        //add the product question rules page
        $this->pageFactory->create()
            ->load('product-question-rules', 'identifier')
            ->addData(
                [
                    'title' => 'Product Question Rules',
                    'page_layout' => '1column',
                    'meta_keywords' => 'Product Question Rules',
                    'meta_description' => 'Product Question Rules',
                    'identifier' => 'product-question-rules',
                    'content_heading' => 'Product Question Rules',
                    'content' => 'We will update the rules for the question of products soon. Thanks for coming here.'
                ]
            )->setStores(
                [\Magento\Store\Model\Store::DEFAULT_STORE_ID]
            )->save();
    }
}
