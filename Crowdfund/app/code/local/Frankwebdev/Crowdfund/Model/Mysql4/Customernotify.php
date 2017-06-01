<?php
class Frankwebdev_Crowdfund_Model_Mysql4_Customernotify extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("crowdfund/customernotify", "id");
    }
}