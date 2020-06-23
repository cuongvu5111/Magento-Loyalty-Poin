<?php

namespace Smart\Loyalty\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    const CONFIG_PATH = 'smart_loyalty/';

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getGeneralConfig(array $list_fields, $storeId = null)
    {
        $list_value = [];
        foreach ($list_fields as $field) {
            $list_value[$field] = $this->getConfigValue(self::CONFIG_PATH . 'general/' . $field, $storeId);
        }
        return $list_value;
    }
    public function getRuleTypeConfig(array $list_fields, $storeId = null)
    {
        $list_value = [];
        foreach ($list_fields as $field) {
            $list_value[$field] = $this->getConfigValue(self::CONFIG_PATH . 'rule_type/' . $field, $storeId);
        }
        return $list_value;
    }
    public function getPromotionConfig(array $list_fields, $storeId = null)
    {
        $list_value = [];
        foreach ($list_fields as $field) {
            $list_value[$field] = $this->getConfigValue(self::CONFIG_PATH . 'promotion/' . $field, $storeId);
        }
        return $list_value;
    }
}
