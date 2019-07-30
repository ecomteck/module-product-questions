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
namespace Ecomteck\ProductQuestions\Block\Adminhtml\Answer\Edit;

use Magento\Backend\Block\Widget\Context;
use Ecomteck\ProductQuestions\Api\AnswerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GenericButton
 */
class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var AnswerRepositoryInterface
     */
    protected $answerRepository;

    /**
     * GenericButton constructor.
     *
     * @param Context $context
     * @param AnswerRepositoryInterface $answerRepository
     */
    public function __construct(
        Context $context,
        AnswerRepositoryInterface $answerRepository
    ) {
        $this->context = $context;
        $this->answerRepository = $answerRepository;
    }

    /**
     * Get Answer Id
     *
     * @return int|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAnswerId()
    {
        try {
            return $this->answerRepository->getById(
                $this->context->getRequest()->getParam('answer_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
