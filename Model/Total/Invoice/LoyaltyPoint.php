<?php

namespace Smart\Loyalty\Model\Total\Invoice;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Weee\Helper\Data as WeeeHelper;
use Magento\Weee\Model\Total\Invoice\Weee;

class LoyaltyPoint extends \Magento\Sales\Model\Order\Invoice\Total\AbstractTotal
{
    /**
     * Weee data
     *
     * @var WeeeHelper
     */
    protected $_weeeData = null;

    /**
     * Instance of serializer.
     *
     * @var Json
     */
    private $serializer;

    /**
     * Constructor
     *
     * By default is looking for first argument as array and assigns it as object
     * attributes This behavior may change in child classes
     *
     * @param WeeeHelper $weeeData
     * @param array $data
     * @param Json|null $serializer
     */
    public function __construct(
    WeeeHelper $weeeData,
    array $data = [],
    Json $serializer = null
) {
    $this->_weeeData = $weeeData;
    $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
    parent::__construct($data);
}

    /**
     * Collect Weee amounts for the invoice
     *
     * @param  \Magento\Sales\Model\Order\Invoice $invoice
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
{
    $store = $invoice->getStore();
    $order = $invoice->getOrder();

    $pointUsed = $order->getPointUsed();
    $invoice->setGrandTotal($invoice->getGrandTotal() - $pointUsed);
    $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $pointUsed);

    return $this;
}
}
