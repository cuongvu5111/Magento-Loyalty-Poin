<?php

namespace Smart\Loyalty\Model\ResourceModel\LoyaltyPointHistory;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Smart\Loyalty\Model\LoyaltyPointHistory',
            'Smart\Loyalty\Model\ResourceModel\LoyaltyPointHistory');
    }
}
