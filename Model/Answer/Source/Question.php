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

namespace Ecomteck\ProductQuestions\Model\Answer\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Ecomteck\ProductQuestions\Model\ResourceModel\Question\CollectionFactory as QuestionCollection;

class Question implements OptionSourceInterface
{
    /**
     * @var QuestionCollection
     */
    protected $questionColFactory;

    /**
     * Question constructor.
     * @param QuestionCollection $questionColFactory
     */
    public function __construct(QuestionCollection $questionColFactory)
    {
        $this->questionColFactory = $questionColFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $result = [];
        $questions = $this->questionColFactory->create()->getItems();
        foreach ($questions as $question) {
            $result[] = [
                'value' => $question->getQuestionId(),
                'label' => $question->getQuestionDetail()
            ];
        }
        return $result;
    }
}