<?php

namespace Smart\Loyalty\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class LoyaltyPointHistory extends AbstractDb
{


    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('smart_loyalty_history', 'id');
    }


}

