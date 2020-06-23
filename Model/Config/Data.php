<?php

namespace Smart\Loyalty\Model\Config;

class Data
{

    protected $helperData;

    public function __construct
    (
        \Smart\Loyalty\Helper\Data $helperData
    ) {
        $this->helperData = $helperData;
    }

    public function getRuleTypeConfig ()
    {
        return $this->helperData->getRuleTypeConfig(['rule_type','point_to_earn','conversion_rate','point_step']);
    }

    public function getPromotionConfig ()
    {
        return $this->helperData->getPromotionConfig(['promotion_mode','promotion_fixed','start_date','end_date']);
    }

}
