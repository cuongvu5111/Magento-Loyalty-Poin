<?php

namespace Smart\Loyalty\Block\Order;

class LoyaltyPoint extends \Magento\Framework\View\Element\Template
{
    protected $_totals;
    protected $_order = null;
    protected $_coreRegistry = null;
    protected $_source;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _beforeToHtml()
    {
        $this->_initTotals();
        foreach ($this->getLayout()->getChildBlocks($this->getNameInLayout()) as $child) {
            if (method_exists($child, 'initTotals') && is_callable([$child, 'initTotals'])) {
                $child->initTotals();
            }
        }
        return parent::_beforeToHtml();
    }

    public function getStore()
    {
        return $this->_order->getStore();
    }

    public function getOrder()
    {
        if ($this->_order === null) {
            if ($this->hasData('order')) {
                $this->_order = $this->_getData('order');
            } elseif ($this->_coreRegistry->registry('current_order')) {
                $this->_order = $this->_coreRegistry->registry('current_order');
            } elseif ($this->getParentBlock()->getOrder()) {
                $this->_order = $this->getParentBlock()->getOrder();
            }
        }
        return $this->_order;
    }

    public function getSource()
    {
        return $this->getOrder();
    }

    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_order = $parent->getOrder();
        $this->_source = $parent->getSource();

        $store = $this->getStore();

        $point_used = new \Magento\Framework\DataObject(
            [
                'code' => 'point_used',
                'strong' => false,
                'value' => -$this->_source->getPointUsed(),
                'label' => __('Loyalty Point (' . $this->_source->getPointUsed() . ' point)'),
            ]
        );

        $parent->addTotal($point_used, 'point_used');

        return $this;
    }
}
