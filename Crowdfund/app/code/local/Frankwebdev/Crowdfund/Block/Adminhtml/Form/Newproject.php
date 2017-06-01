<?php

class Frankwebdev_Crowdfund_Block_Adminhtml_Form_Newproject extends Mage_Adminhtml_Block_Widget_Form_Container {

	public function __construct(){
		parent::__construct();
		$this->_blockGroup = "crowdfund";
		$this->_controller = "adminhtml_form";
		$this->_headerText = Mage::helper('crowdfund')->__('New Project');

		Mage::log("LOAD FORM CONTROLLER");
	}

}