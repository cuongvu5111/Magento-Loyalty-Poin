<?php

namespace Smart\Loyalty\Observer;

use Magento\Framework\Event\ObserverInterface;

class ConvertQuoteToOrder implements ObserverInterface
{
    private $attributes = [
        'point_used',
    ];

    protected $customerRepositoryInterface;
    protected $loyaltyPointHistoryFactory;

    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Smart\Loyalty\Model\LoyaltyPointHistoryFactory $loyaltyPointHistoryFactory
    ) {
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->loyaltyPointHistoryFactory = $loyaltyPointHistoryFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();

        foreach ($this->attributes as $attribute) {
            if ($quote->hasData($attribute)) {
                $order->setData($attribute, $quote->getData($attribute));
            }
        }

        $pointUsed = $order->getData('point_used');
        $this->plusPoint($order, $pointUsed);
        $this->history($order->getCustomerId(), $pointUsed, $order->getIncrementId());
        return $this;
    }

    public function plusPoint($order, $pointUsed)
    {
        $id = $order->getCustomerId();
        $customer =$this->customerRepositoryInterface->getById($id);
        $pointCurrent = $customer->getCustomAttribute('point_balance')->getValue();
        $pointBalance = $pointCurrent - $pointUsed;
        $customer->setCustomAttribute('point_balance', $pointBalance);
        $this->customerRepositoryInterface->save($customer);
    }

    public function history($customerId, $amount, $order_id)
    {
        $history = $this->loyaltyPointHistoryFactory->create();
        $data = [
            'customer_id' => $customerId,
            'amount' => -$amount,
            'resource' => 'Point used for order #' . $order_id . ''
        ];
        $history->setData($data);
        $history->save();
    }
}
