<?php


class Frankwebdev_Crowdfund_Block_Adminhtml_Productsmodel extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	/*$this->_controller = "adminhtml_crowdfundmodel";
	$this->_blockGroup = "crowdfund";
	$this->_headerText = Mage::helper("crowdfund")->__("Manage Projects");
	$this->_addButtonLabel = Mage::helper("crowdfund")->__("Add New Project");
	parent::__construct();
	Mage::log('Model Controller');*/
	$this->_blockGroup = 'crowdfund_products';
        $this->_controller = 'adminhtml_productsmodel';
        $this->_headerText = Mage::helper('crowdfund')->__('Crowdfund - Products');
 
        parent::__construct();
        $this->_removeButton('add');

	//$this->_removeButton('add');

	}

}