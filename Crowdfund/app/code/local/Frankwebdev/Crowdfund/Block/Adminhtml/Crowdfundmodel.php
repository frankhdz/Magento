<?php


class Frankwebdev_Crowdfund_Block_Adminhtml_Crowdfundmodel extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_crowdfundmodel";
	$this->_blockGroup = "crowdfund";
	$this->_headerText = Mage::helper("crowdfund")->__("Manage Projects");
	$this->_addButtonLabel = Mage::helper("crowdfund")->__("Add New Project");
	parent::__construct();
	Mage::log('Model Controller');

	//$this->_removeButton('add');

	}

}