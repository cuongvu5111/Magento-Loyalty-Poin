<?php
namespace Smart\Loyalty\Model\Total;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

class LoyaltyPoint extends AbstractTotal
{
    protected $customerRepositoryInterface;
    protected $customerSession;
    protected $customerFactory;
    protected $priceCurrency;

    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->setCode('loyalty_point');
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->customerSession = $customerSession;
        $this->customerFactory = $customerFactory;
        $this->priceCurrency = $priceCurrency;
    }

    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        $pointBalance = 0;


        //get point balance
        $id = $this->customerSession->getId();
        $customer =$this->customerRepositoryInterface->getById($id);
        $customerPoint = $customer->getCustomAttribute('point_balance');
        if($customerPoint != null ){
            $pointBalance = $customerPoint->getValue();
        }

        $pointUsed = $quote->getPointUsed();
        $countTotal = $quote->getData('subtotal');
        // check
        if ($pointBalance < $pointUsed) {
            return $this;
        }
        if ($pointUsed > $countTotal) {
            $pointUsed = $countTotal;
        }
        $pointUsedBase = $this->priceCurrency->convert($pointUsed);
        // save to grand total
        if ($pointUsed <= $total->getBaseGrandTotal()) {
            $total->setGrandTotal($total->getGrandTotal() - $pointUsedBase);
            $total->setBaseGrandTotal($total->getBaseGrandTotal() - $pointUsed);
        } else {
            $total->setBaseGrandTotal(0);
            $total->setGrandTotal(0);
        }
        return $this;

    }

    protected function clearValues(Total $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);
    }

    public function fetch(Quote $quote, Total $total)
    {
        return [
            'code' => $this->getCode(),
            'title' => 'Loyalty Point',
            'value' => $quote->getPointUsed()
        ];
    }

    public function getLabel()
    {
        return __('Loyalty Point');
    }

    public function setLoyaltyPointBalance($pointBalance)
    {
        $id = $this->customerSession->getId();
        $customer = $this->customerFactory->create()->load($id)->getDataModel();
        $customer->setCustomAttribute('point_balance', $pointBalance);
        $this->customerRepositoryInterface->save($customer);
    }
}
