<?php   
class Frankwebdev_Crowdfund_Block_Customer extends Mage_Core_Block_Template{   

	public function  __construct(){
     parent::__construct();
     
 	}

	public function  getPagerHtml(){
	     return $this->getChildHtml('pager');
	}

	public function  _prepareLayout(){
	    parent::_prepareLayout();
	   
	   return $this;
	}



}