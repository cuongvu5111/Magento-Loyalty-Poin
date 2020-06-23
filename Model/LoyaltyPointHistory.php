<?php

namespace Smart\Loyalty\Model;

use \Magento\Framework\Model\AbstractModel;

class LoyaltyPointHistory extends AbstractModel
{


    /**
     * Initialize resource model
     * @return void
     */
    public function _construct()
    {
        $this->_init('Smart\Loyalty\Model\ResourceModel\LoyaltyPointHistory');
    }


}

