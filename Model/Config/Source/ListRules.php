<?php


namespace Smart\Loyalty\Model\Config\Source;


class ListRules
{
    public function toOptionArray()
    {
        return [
            ['value' => '1', 'label' => __('Fixed Point')],
            ['value' => '2', 'label' => __('By Rate')],
            ['value' => '3', 'label' => __('By Step')],
        ];
    }
}
