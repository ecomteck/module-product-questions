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
 * Adminhtml add Product Question main block
 *
 * @author Magento Ecomteck Team <ecomteck@gmail.com>
 */
class Add extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Initialize add product question
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_blockGroup = 'Ecomteck_ProductQuestions';
        $this->_controller = 'adminhtml';
        $this->_mode = 'add';

        $this->buttonList->update('save', 'label', __('Save Question'));
        $this->buttonList->update('save', 'id', 'save_button');

        $this->buttonList->update('reset', 'id', 'reset_button');

        $this->_formScripts[] = '
            require(["prototype"], function(){
                toggleParentVis("add_product_questions_form");
                toggleVis("save_button");
                toggleVis("reset_button");
            });
        ';

        // @codingStandardsIgnoreStart
        $this->_formInitScripts[] = '
            require(["jquery","prototype"], function(jQuery){
            window.productQuestion = function() {
                return {
                    productInfoUrl : null,
                    formHidden : true,
                    gridRowClick : function(data, click) {
                        if(Event.findElement(click,\'TR\').title){
                            productQuestion.productInfoUrl = Event.findElement(click,\'TR\').title;
                            productQuestion.loadProductData();
                            productQuestion.showForm();
                            productQuestion.formHidden = false;
                        }
                    },
                    loadProductData : function() {
                        jQuery.ajax({
                            type: "GET",
                            url: productQuestion.productInfoUrl,
                            data: {
                                form_key: FORM_KEY
                            },
                            showLoader: true,
                            success: productQuestion.reqSuccess,
                            error: productQuestion.reqFailure
                        });
                    },
                    showForm : function() {
                        toggleParentVis("add_product_questions_form");
                        toggleVis("productGrid");
                        toggleVis("save_button");
                        toggleVis("reset_button");
                    },
                    reqSuccess :function(response) {
                        if( response.error ) {
                            alert(response.message);
                        } else if( response.id ){
                            $("product_id").value = response.id;

                            $("product_name").innerHTML = \'<a href="' .
            $this->getUrl(
                'catalog/product/edit'
            ) .
            'id/\' + response.id + \'" target="_blank">\' + response.name + \'</a>\';
                        } else if ( response.message ) {
                            alert(response.message);
                        }
                    }
                }
            }();
            
            });
           //]]>
        ';
        // @codingStandardsIgnoreEnd
    }

    /**
     * Get add new question header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __('New Question');
    }
}
