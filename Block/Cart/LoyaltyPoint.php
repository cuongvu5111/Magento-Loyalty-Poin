<?php

namespace Smart\Loyalty\Block\Cart;

use Magento\Customer\Api\CustomerRepositoryInterface;

class LoyaltyPoint extends \Magento\Checkout\Block\Cart\AbstractCart
{
    protected $customerRepository;

    protected $customerFactory;

    protected $customers;

    protected $customerRepositoryInterface;

    /**
     * LoyaltyPoint constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magento\Customer\Model\Customer $customers
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\Customer $customers,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        array $data = []
    ) {
        $this->_customerSession = $customerSession;
        $this->customerFactory = $customerFactory;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->customers = $customers;
        $this->_isScopePrivate = true;
        parent::__construct($context, $customerSession, $checkoutSession, $data);

    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getCouponCode()
    {
        return $this->getQuote()->getCouponCode();
    }

    /**
     * @return bool|int|null
     */
    public function getLoggedinCustomerId()
    {
        if ($this->_customerSession->isLoggedIn()) {
            return $this->_customerSession->getId();
        }
        return false;
    }

    /**
     * @return bool|\Magento\Customer\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomerData()
    {
        if ($this->_customerSession->isLoggedIn()) {
            return $this->_customerSession->getCustomerData();
        }
        return false;
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLoyaltyPointBalance()
    {
        $id = $this->_customerSession->getId();
        $customer =$this->customerRepositoryInterface->getById($id);
        $customerPoint = $customer->getCustomAttribute('point_balance');
        if($customerPoint != null){
            return $customerPoint->getValue();
        }
        return 0;
    }

    public function isApplyPoint()
    {
        if ($this->getQuote()->getPointUsed() > 0 ) {
            return true;
        }
        return false;
    }

    public function getPointUsed()
    {
        return $this->getQuote()->getPointUsed();
    }
}
