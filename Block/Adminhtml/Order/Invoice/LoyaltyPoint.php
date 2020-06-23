<?php

namespace Smart\Loyalty\Block\Adminhtml\Order\Invoice;

class LoyaltyPoint extends \Magento\Framework\View\Element\Template
{
    protected $_config;
    protected $_order;
    protected $_source;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Tax\Model\Config $taxConfig,
        array $data = []
    ) {
        $this->_config = $taxConfig;
        parent::__construct($context, $data);
    }

    public function displayFullSummary()
    {
        return true;
    }

    public function getSource()
    {
        return $this->_source;
    }
    public function getStore()
    {
        return $this->_order->getStore();
    }
    public function getOrder()
    {
        return $this->_order;
    }
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }
    public function initTotals()
    {

        $parent = $this->getParentBlock();
        $this->_order = $parent->getOrder();
        $this->_source = $parent->getSource();
        $store = $this->getStore();
        $fee = new \Magento\Framework\DataObject(
            [
                'code' => 'point_used',
                'strong' => false,
                'value' => -$this->_order->getPointUsed(),
                'base_value' => $this->_order->getPointUsed(),
                'label' => __('Loyalty Point Used'),
            ]
        );
        $parent->addTotal($fee, 'point_used');
        return $this;
    }

}
