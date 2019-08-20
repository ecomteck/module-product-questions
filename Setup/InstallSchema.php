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

use Magento\Framework\DB\Adapter\AdapterInterface;
use Ecomteck\ProductQuestions\Model\UserType;
use Ecomteck\ProductQuestions\Model\Status;
use Ecomteck\ProductQuestions\Model\Visibility;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        //Drop tables if exist
        $installer->getConnection()->dropTable($installer->getTable('ecomteck_product_questions_sharing'));
        $installer->getConnection()->dropTable($installer->getTable('ecomteck_product_questions_status'));
        $installer->getConnection()->dropTable($installer->getTable('ecomteck_product_questions_store'));
        $installer->getConnection()->dropTable($installer->getTable('ecomteck_product_questions_user_type'));
        $installer->getConnection()->dropTable($installer->getTable('ecomteck_product_questions_visibility'));
        $installer->getConnection()->dropTable($installer->getTable('ecomteck_product_answers'));
        $installer->getConnection()->dropTable($installer->getTable('ecomteck_product_questions'));

        /**
         * Create table 'ecomteck_product_questions_status'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ecomteck_product_questions_status'))
            ->addColumn(
                'status_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Status id'
            )
            ->addColumn(
                'status_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false],
                'Status code'
            )
            ->setComment('Product Question and Answer statuses');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ecomteck_product_questions_user_type'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ecomteck_product_questions_user_type'))
            ->addColumn(
                'user_type_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'User Type id'
            )
            ->addColumn(
                'user_type_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false],
                'User Type code'
            )
            ->setComment('Product Question and Answer User Types');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ecomteck_product_questions_visibility'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ecomteck_product_questions_visibility'))
            ->addColumn(
                'visibility_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Visibility id'
            )
            ->addColumn(
                'visibility_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false],
                'Visibility code'
            )
            ->setComment('Product Question and Answer Visibilities');
        $installer->getConnection()->createTable($table);

        /**
         * Create the table 'ecomteck_product_questions'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('ecomteck_product_questions')
        )->addColumn(
            'question_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            null,
            ['identity' => true, 'nullable' => false, 'unsigned' => true, 'primary' => true],
            'Question ID'
        )->addColumn(
            'question_detail',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            \Magento\Framework\DB\Ddl\Table::MAX_TEXT_SIZE,
            ['nullable' => true],
            'Content of question'
        )->addColumn(
            'question_author_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['default' => null, 'nullable' => false],
            'Question Author Name'
        )->addColumn(
            'question_author_email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['default' => null, 'nullable' => false],
            'Email of asker'
        )->addColumn(
            'question_status_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => Status::STATUS_PENDING],
            'Status code'
        )->addColumn(
            'question_user_type_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => UserType::USER_TYPE_GUEST],
            'User code'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true, 'default' => null],
            'Customer ID'
        )->addColumn(
            'question_visibility_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => Visibility::VISIBILITY_VISIBLE],
            'Visibility code'
        )->addColumn(
            'question_store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'default' => '0'],
            'Question asked in the store id'
        )->addColumn(
            'question_likes',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Total number of likes'
        )->addColumn(
            'question_dislikes',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Total number of dislikes'
        )->addColumn(
            'total_answers',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Total number of answers'
        )->addColumn(
            'pending_answers',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Total number of pending answers'
        )->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Product ID'
        )->addColumn(
            'question_created_by',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => UserType::USER_TYPE_GUEST],
            'User code'
        )->addColumn(
            'question_created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Question create date'
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_questions',
                'question_status_id',
                'ecomteck_product_questions_status',
                'status_id'
            ),
            'question_status_id',
            $installer->getTable('ecomteck_product_questions_status'),
            'status_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_questions',
                'question_user_type_id',
                'ecomteck_product_questions_user_type',
                'user_type_id'
            ),
            'question_user_type_id',
            $installer->getTable('ecomteck_product_questions_user_type'),
            'user_type_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_questions',
                'question_created_by',
                'ecomteck_product_questions_user_type',
                'user_type_id'
            ),
            'question_created_by',
            $installer->getTable('ecomteck_product_questions_user_type'),
            'user_type_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_questions',
                'question_visibility_id',
                'ecomteck_product_questions_visibility',
                'visibility_id'
            ),
            'question_visibility_id',
            $installer->getTable('ecomteck_product_questions_visibility'),
            'visibility_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_questions',
                'question_store_id',
                'store',
                'store_id'
            ),
            'question_store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_questions',
                'product_id',
                'catalog_product_entity',
                'entity_id'
            ),
            'product_id',
            $installer->getTable('catalog_product_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_questions',
                'customer_id',
                'customer_entity',
                'entity_id'
            ),
            'customer_id',
            $installer->getTable('customer_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('ecomteck_product_questions'),
                ['question_detail', 'question_author_name', 'question_author_email'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['question_detail', 'question_author_name', 'question_author_email'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Question information of product'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ecomteck_product_questions_store'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('ecomteck_product_questions_store')
        )->addColumn(
            'store_view_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'default' => '0'],
            'Question shared in the store id'
        )->addColumn(
            'question_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            null,
            ['nullable' => false, 'unsigned' => true],
            'Question ID'
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_questions_store',
                'store_view_id',
                'store',
                'store_id'
            ),
            'store_view_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_questions_store',
                'question_id',
                'ecomteck_product_questions',
                'question_id'
            ),
            'question_id',
            $installer->getTable('ecomteck_product_questions'),
            'question_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Product Question To Store Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create the table 'ecomteck_product_answers'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('ecomteck_product_answers')
        )->addColumn(
            'answer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            null,
            ['identity' => true, 'nullable' => false, 'unsigned' => true, 'primary' => true],
            'Answer ID'
        )->addColumn(
            'answer_detail',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            \Magento\Framework\DB\Ddl\Table::MAX_TEXT_SIZE,
            ['nullable' => false],
            'Content of answer'
        )->addColumn(
            'answer_author_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['default' => null, 'nullable' => false],
            'author_name of respondent'
        )->addColumn(
            'answer_author_email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['default' => null, 'nullable' => false],
            'Email of respondent'
        )->addColumn(
            'question_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            null,
            ['nullable' => false, 'unsigned' => true],
            'Question ID'
        )->addColumn(
            'answer_status_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => Status::STATUS_PENDING],
            'Status code'
        )->addColumn(
            'answer_user_type_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => UserType::USER_TYPE_GUEST],
            'User code'
        )->addColumn(
            'answer_user_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true, 'default' => null],
            'User ID'
        )->addColumn(
            'answer_created_by',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => UserType::USER_TYPE_GUEST],
            'User code'
        )->addColumn(
            'answer_visibility_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => Visibility::VISIBILITY_VISIBLE],
            'Visibility code'
        )->addColumn(
            'answer_likes',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Total number of likes'
        )->addColumn(
            'answer_dislikes',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Total number of dislikes'
        )->addColumn(
            'answer_created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Answer create date'
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_answers',
                'answer_status_id',
                'ecomteck_product_questions_status',
                'status_id'
            ),
            'answer_status_id',
            $installer->getTable('ecomteck_product_questions_status'),
            'status_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_answers',
                'answer_user_type_id',
                'ecomteck_product_questions_user_type',
                'user_type_id'
            ),
            'answer_user_type_id',
            $installer->getTable('ecomteck_product_questions_user_type'),
            'user_type_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_answers',
                'answer_created_by',
                'ecomteck_product_questions_user_type',
                'user_type_id'
            ),
            'answer_created_by',
            $installer->getTable('ecomteck_product_questions_user_type'),
            'user_type_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_answers',
                'answer_visibility_id',
                'ecomteck_product_questions_visibility',
                'visibility_id'
            ),
            'answer_visibility_id',
            $installer->getTable('ecomteck_product_questions_visibility'),
            'visibility_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_answers',
                'question_id',
                'ecomteck_product_questions',
                'question_id'
            ),
            'question_id',
            $installer->getTable('ecomteck_product_questions'),
            'question_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('ecomteck_product_answers'),
                ['answer_detail', 'answer_author_name', 'answer_author_email'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['answer_detail', 'answer_author_name', 'answer_author_email'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Answer\'s information of a question on a product'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ecomteck_product_questions_sharing'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('ecomteck_product_questions_sharing')
        )->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Product ID'
        )->addColumn(
            'question_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            null,
            ['nullable' => false, 'unsigned' => true],
            'Question ID'
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_questions_sharing',
                'product_id',
                'catalog_product_entity',
                'entity_id'
            ),
            'product_id',
            $installer->getTable('catalog_product_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(
                'ecomteck_product_questions_sharing',
                'question_id',
                'ecomteck_product_questions',
                'question_id'
            ),
            'question_id',
            $installer->getTable('ecomteck_product_questions'),
            'question_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Product Question To Product Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
