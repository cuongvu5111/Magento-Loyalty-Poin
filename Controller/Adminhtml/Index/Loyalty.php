<?php

namespace Smart\Loyalty\Controller\Adminhtml\Index;

class Loyalty extends \Magento\Customer\Controller\Adminhtml\Index
{
    public function execute()
    {
        $this->initCurrentCustomer();
        return $this->resultLayoutFactory->create();
    }
}
