<?php 
class Frankwebdev_Crowdfund_Model_Mysql4_Customers_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('crowdfund/customers');
    }
}