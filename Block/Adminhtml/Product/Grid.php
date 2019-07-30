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

namespace Ecomteck\ProductQuestions\Block\Adminhtml\Product;

/**
 * Adminhtml product grid block
 *
 * @author      Magento Ecomteck Team <ecomteck@gmail.com>
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Grid extends \Magento\Catalog\Block\Adminhtml\Product\Grid
{
    /**
     * Website collection
     *
     * @var \Magento\Store\Model\ResourceModel\Website\CollectionFactory
     */
    protected $_websitesFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory $websiteFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\Product\Type $type
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $status
     * @param \Magento\Catalog\Model\Product\Visibility $visibility
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param \Magento\Store\Model\ResourceModel\Website\CollectionFactory $websitesFactory
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\Product\Type $type,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $status,
        \Magento\Catalog\Model\Product\Visibility $visibility,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Store\Model\ResourceModel\Website\CollectionFactory $websitesFactory,
        array $data = []
    ) {
        $this->_websitesFactory = $websitesFactory;
        parent::__construct(
            $context,
            $backendHelper,
            $websiteFactory,
            $setsFactory,
            $productFactory,
            $type,
            $status,
            $visibility,
            $moduleManager,
            $data
        );
    }

    /**
     * Initialize review
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setRowClickCallback('productQuestion.gridRowClick');
        $this->setUseAjax(true);
    }

    /**
     * Prepare product review grid
     *
     * @return void
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn('name', ['header' => __('Name'), 'index' => 'name']);

        if ((int)$this->getRequest()->getParam('store', 0)) {
            $this->addColumn('custom_name', ['header' => __('Product Store Name'), 'index' => 'custom_name']);
        }

        $this->addColumn('sku', ['header' => __('SKU'), 'index' => 'sku']);

        $this->addColumn('price', ['header' => __('Price'), 'type' => 'currency', 'index' => 'price']);

        $this->addColumn(
            'qty',
            ['header' => __('Quantity'), 'type' => 'number', 'index' => 'qty']
        );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'source' => \Magento\Catalog\Model\Product\Attribute\Source\Status::class,
                'options' => $this->_status->getOptionArray()
            ]
        );

        /**
         * Check is single store mode
         */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $this->addColumn(
                'websites',
                [
                    'header' => __('Websites'),
                    'sortable' => false,
                    'index' => 'websites',
                    'type' => 'options',
                    'options' => $this->_websitesFactory->create()->toOptionHash()
                ]
            );
        }
    }

    /**
     * Get catalog product grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('product_questions/question/productGrid', ['_current' => true]);
    }

    /**
     * Get catalog product row url
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('product_questions/question/jsonProductInfo', ['id' => $row->getId()]);
    }

    /**
     * Prepare mass action
     *
     * @return $this
     */
    protected function _prepareMassaction()
    {
        return $this;
    }
}
