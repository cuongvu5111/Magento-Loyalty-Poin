<?php

namespace Smart\Loyalty\Observer;

use Magento\Framework\Event\ObserverInterface;

class InvoiceOrder implements ObserverInterface
{
    private $attributes = [
        'point_used',
        'point_earned'
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
        /* @var $order \Magento\Sales\Model\Order */
        $order = $observer->getEvent()->getOrder();

        $grandTotal = (int) $order->getData('grand_total');
        $baseTotalPaid = (int) $order->getData('base_total_paid');

        if (($grandTotal - $baseTotalPaid) < 0.000001) {
            // cong diem
            $pointEarned = $order->getData('point_earned');
            $this->minusPoint($order, $pointEarned);
            // save data to history_table
            $this->history($order->getCustomerId(), $pointEarned, $order->getIncrementId());
        }

        return $this;
    }

    public function minusPoint($order, $pointEarned)
    {
        $id = $order->getCustomerId();
        $customer =$this->customerRepositoryInterface->getById($id);
        $pointCurrent = $customer->getCustomAttribute('point_balance')->getValue();
        $pointBalance = $pointCurrent + $pointEarned;
        $customer->setCustomAttribute('point_balance', $pointBalance);
        $this->customerRepositoryInterface->save($customer);
    }
    public function history($customerId, $amount, $order_id)
    {
        $history = $this->loyaltyPointHistoryFactory->create();
        $data = [
            'customer_id' => $customerId,
            'amount' => +$amount,
            'resource' => 'Point earned for order #' . $order_id . ''
        ];
        $history->setData($data);
        $history->save();
    }
}
