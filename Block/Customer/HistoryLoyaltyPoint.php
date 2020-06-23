<?php

namespace Smart\Loyalty\Block\Customer;

class HistoryLoyaltyPoint extends \Magento\Framework\View\Element\Template
{
    protected $loyaltyPointHistoryFactory;
    protected $_customerSession;
    protected $customerRepositoryInterface;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Smart\Loyalty\Model\LoyaltyPointHistoryFactory $loyaltyPointHistoryFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        $this->loyaltyPointHistoryFactory = $loyaltyPointHistoryFactory;
        $this->_customerSession = $customerSession;
        $this->customerFactory = $customerFactory;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        parent::__construct($context, $data);
    }

    public function getHistory()
    {
        $id = $this->_customerSession->isLoggedIn();
        $model= $this->loyaltyPointHistoryFactory->create();
        $history = $model->getCollection();
        $history->addFieldToFilter('customer_id', ['eq' => 1]);
        return $history->getData();
    }

    public function getLoyaltyPointBalance()
    {
        $id = $this->_customerSession->getId();
        $customer =$this->customerRepositoryInterface->getById(1);
        return $customer->getCustomAttribute('point_balance')->getValue();
    }
}
