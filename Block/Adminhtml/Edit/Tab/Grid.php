<?php

namespace Smart\Loyalty\Block\Adminhtml\Edit\Tab;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Data\Form\FormKey;
use Smart\Loyalty\Model\LoyaltyPointHistoryFactory;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $_coreRegistry = null;
    protected $request;
    protected $_collectionFactory;
    protected $customerRepositoryInterface;
    protected $loyaltyPointHistoryFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Newsletter\Model\ResourceModel\Queue\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param Http $request
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     * @param LoyaltyPointHistoryFactory $loyaltyPointHistoryFactory
     * @param FormKey $formKey
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Newsletter\Model\ResourceModel\Queue\CollectionFactory $collectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        Http $request,
        CustomerRepositoryInterface $customerRepositoryInterface,
        LoyaltyPointHistoryFactory $loyaltyPointHistoryFactory,
        FormKey $formKey,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_collectionFactory = $collectionFactory;
        $this->request = $request;
        $this->formKey = $formKey;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->loyaltyPointHistoryFactory = $loyaltyPointHistoryFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    public function getCustomerId()
    {
        return $this->request->getParam('id');
    }

    public function getPointBalance()
    {
        $id = $this->request->getParam('id');
        $customer =$this->customerRepositoryInterface->getById($id);
        $point = $customer->getCustomAttribute('point_balance')->getValue();
        return $point;
    }

    public function getHistory()
    {
        $id = $this->request->getParam('id');
        $model= $this->loyaltyPointHistoryFactory->create();
        $history = $model->getCollection();
        $history->addFieldToFilter('customer_id', ['eq' => "$id"]);
        return $history;
    }
}
